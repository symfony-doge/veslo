<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Workflow\Vacancy\Research;

use Bunny\Channel;
use Bunny\Client;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Workflow\Workflow;
use Veslo\AppBundle\Exception\Workflow\Conveyor\DistributeException;
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
     * @param Payload $payload Data to be passed through workflow
     *
     * @return void
     *
     * @throws Exception
     */
    public function send(Payload $payload): void
    {
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
     * Distributes payload data among the queues for conveyor processing via workflow
     *
     * @param Payload $payload    Data to be passed through workflow
     * @param array   $queueNames Queue names for publishing
     *
     * @return void
     *
     * @throws Exception
     */
    private function distribute(Payload $payload, array $queueNames): void
    {
        if (!$this->amqpClient->isConnected()) {
            $this->amqpClient->connect();
        }

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
                    'Payload distribution failed.',
                    [
                        'message'   => $e->getMessage(),
                        'payload'   => $this->serializer->normalize($payload),
                        'channel'   => $this->serializer->normalize($channel),
                        'queueName' => $queueName,
                    ]
                );

                throw DistributeException::withQueueName($queueName);
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

        $this->logger->info('Payload publishing...', ['queueName' => $queueName, 'message' => $message]);
    }
}
