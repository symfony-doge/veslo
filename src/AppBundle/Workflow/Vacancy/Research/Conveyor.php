<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Workflow\Vacancy\Research;

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Workflow\Workflow;
use Veslo\AppBundle\Exception\Workflow\Conveyor\ConnectionFailedException;
use Veslo\AppBundle\Exception\Workflow\Conveyor\DistributionFailedException;
use Veslo\AppBundle\Workflow\Vacancy\Research\Conveyor\Payload;

/**
 * Manages data exchange between workers using workflow
 *
 * @see https://symfony.com/doc/current/components/workflow.html
 */
class Conveyor
{
    /**
     * Logger as it is
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * State machine, represent business process
     *
     * @var Workflow
     */
    private $workflow;

    /**
     * Converts data in the appropriate format
     *
     * @var Serializer
     */
    private $serializer;

    /**
     * Communicates with message broker
     *
     * @var Client
     */
    private $amqpClient;

    /**
     * Prefix for workflow-related queues
     *
     * @var string
     */
    private $queuePrefix;

    /**
     * Conveyor constructor.
     *
     * @param LoggerInterface $logger      Logger as it is
     * @param Workflow        $workflow    State machine, represent business process
     * @param Serializer      $serializer  Converts data in the appropriate format
     * @param Client          $amqpClient  Communicates with message broker
     * @param string          $queuePrefix Prefix for workflow-related queues
     */
    public function __construct(
        LoggerInterface $logger,
        Workflow $workflow,
        Serializer $serializer,
        Client $amqpClient,
        string $queuePrefix
    ) {
        $this->logger      = $logger;
        $this->workflow    = $workflow;
        $this->serializer  = $serializer;
        $this->amqpClient  = $amqpClient;
        $this->queuePrefix = $queuePrefix;
    }

    /**
     * Sends payload data to queues for processing according to configured workflow transitions
     *
     * @param object $dto Data to be passed through workflow
     *
     * @return void
     */
    public function send(object $dto): void
    {
        $payload     = new Payload($dto);
        $transitions = $this->workflow->getEnabledTransitions($payload);

        if (empty($transitions)) {
            return;
        }

        $queueNames = [];

        foreach ($transitions as $transition) {
            $transitionName = $transition->getName();
            $queueNames[]   = $this->queuePrefix . $transitionName;
        }

        $this->distribute($payload, $queueNames);
    }

    /**
     * Returns data transfer object filled up with data from queues according to configured workflow transitions
     *
     * @param string $dtoClass Class of data transfer object
     *
     * @return object|null
     */
    public function receive(string $dtoClass): ?object
    {
        // TODO: if (!in_array($dtoName, $this->dtoNames))

        $dto = new $dtoClass;

        $transitions = $this->workflow->getEnabledTransitions(new Payload($dto));

        // Space for multiple queue logic here.
        $transition = array_shift($transitions);

        $transitionName = $transition->getName();
        $queueName      = $this->queuePrefix . $transitionName;

        return $this->get($dtoClass, $queueName);
    }

    /**
     * Distributes payload data among the queues for conveyor processing via workflow
     *
     * @param Payload $payload    Data to be passed through workflow
     * @param array   $queueNames Queue names for publishing
     *
     * @return void
     *
     * @throws DistributionFailedException
     */
    private function distribute(Payload $payload, array $queueNames): void
    {
        $this->ensureConnection();

        $channel = $this->amqpClient->channel();
        $channel->txSelect();

        foreach ($queueNames as $queueName) {
            try {
                $channel->queueDeclare($queueName);
                $this->publish($payload, $channel, $queueName);
            } catch (Exception $e) {
                $channel->txRollback();

                $channel->close()->then(function () {
                    $this->amqpClient->disconnect();
                });

                $this->logger->critical(
                    'Payload publish failed.',
                    [
                        'message'   => $e->getMessage(),
                        'payload'   => $this->serializer->normalize($payload),
                        'queueName' => $queueName,
                    ]
                );

                throw DistributionFailedException::withQueueName($queueName);
            }
        }

        $channel->txCommit();

        $normalizedPayload = $this->serializer->normalize($payload);
        $this->logger->info('Payload distributed.', ['queueNames' => $queueNames, 'payload' => $normalizedPayload]);
    }

    /**
     * Publishes payload data to queue for conveyor processing via workflow
     *
     * @param Payload $payload   Data to be passed through workflow
     * @param Channel $channel   Channel for communication with message broker
     * @param string  $queueName Queue name for publishing
     *
     * @return void
     */
    private function publish(Payload $payload, Channel $channel, string $queueName): void
    {
        $data    = $payload->getData();
        $message = $this->serializer->serialize($data, 'json');

        $channel->publish($message, ['content_type' => 'application/json'], '', $queueName);
    }

    // TODO: extract into gateway +descr
    private function get(string $dtoClass, string $queueName): ?object
    {
        $this->ensureConnection();

        $channel = $this->amqpClient->channel();
        $channel->queueDeclare($queueName);

        $message = $channel->get($queueName, true);

        if (!$message instanceof Message) {
            return null;
        }

        return $this->serializer->deserialize($message->content, $dtoClass,'json');
    }

    /**
     * Ensures connection with message broker is established
     *
     * @return void
     *
     * @throws ConnectionFailedException
     */
    private function ensureConnection(): void
    {
        if ($this->amqpClient->isConnected()) {
            return;
        }

        try {
            $this->amqpClient->connect();
        } catch (Exception $e) {
            throw ConnectionFailedException::withPrevious($e);
        }
    }
}
