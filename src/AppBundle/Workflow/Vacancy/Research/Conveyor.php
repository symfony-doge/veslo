<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Workflow\Vacancy\Research;

use Bunny\Channel;
use Bunny\Client;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Workflow\Workflow;
use Veslo\AppBundle\Workflow\Vacancy\Research\Conveyor\Payload;

/**
 * Manages data exchange between workers using workflow
 *
 * @see https://symfony.com/doc/current/components/workflow.html
 */
class Conveyor
{
    /**
     * State machine, represent business process
     *
     * @var Workflow
     */
    private $workflow;

    /**
     * Serializes data in the appropriate format
     *
     * @var SerializerInterface
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
     * @param Workflow            $workflow    State machine, represent business process
     * @param SerializerInterface $serializer  Serializes data in the appropriate format
     * @param Client              $amqpClient  Communicates with message broker
     * @param string              $queuePrefix Prefix for workflow-related queues
     */
    public function __construct(
        Workflow $workflow,
        SerializerInterface $serializer,
        Client $amqpClient,
        string $queuePrefix
    ) {
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
                $this->amqpClient->disconnect();

                throw $e;
            }
        }

        $channel->txCommit();
        $this->amqpClient->disconnect();
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

        $channel->publish($message, [], '', $queueName);
    }
}
