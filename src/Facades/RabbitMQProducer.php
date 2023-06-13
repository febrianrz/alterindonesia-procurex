<?php

namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Interfaces\TaskInterface;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQProducer
{
    /**
     * @var AMQPStreamConnection
     */
    protected AMQPStreamConnection $connection;

    /**
     * @var AMQPChannel
     */
    protected AMQPChannel $channel;

    public function __construct ()
    {
        // set connection
        try {
            $this->connection = new AMQPStreamConnection(
                config("procurex.rabbitMQ.host"),
                config("procurex.rabbitMQ.port"),
                config("procurex.rabbitMQ.user"),
                config("procurex.rabbitMQ.password"),
                config("procurex.rabbitMQ.vhost")
            );
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
        }
    }

    /**
     * @throws \Exception
     */
    public function test (): void
    {
        $exchange = config('procurex.rabbitMQ.exchange');
        $routingKey = config('procurex.rabbitMQ.routing_key');
        try {
            $this->channel = $this->connection->channel();
            $this->channel->exchange_declare(
                config('procurex.rabbitMQ.exchange'),
                AMQPExchangeType::DIRECT,
                false,
                true,
                false
            );
            $payload = new AMQPMessage(
                json_encode([
                    'message'   => 'test message procurex',
                ]),
                array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
            );
            $this->channel->basic_publish(
                $payload,
                config('procurex.rabbitMQ.exchange'),
                config('procurex.rabbitMQ.routing_key'),
            );

            $this->channel->wait_for_pending_acks();
            // clear connection
            $this->channel->close();
            $this->connection->close();
            Log::info("RabbitMQProducer: test success - ex: {$exchange} - routing: {$routingKey}");
        } catch (\Exception $e){
            Log::error("RabbitMQProducer: test error - ex: {$exchange} - routing: {$routingKey} msg: {$e->getMessage()}");
        }
    }

    public function publishTask (TaskInterface $task): void
    {
        $exchange = config('procurex.rabbitMQ.exchange');
        $routingKey = config('procurex.rabbitMQ.routing_key');
        try {
            $this->channel = $this->connection->channel();

            $this->channel->exchange_declare(
                $exchange,
                AMQPExchangeType::DIRECT,
                false,
                true,
                false
            );
            $payload = new AMQPMessage(
                json_encode($task->payload()),
                array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
            );
            $this->channel->basic_publish(
                $payload,
                $exchange,
                $routingKey,
            );

            $this->channel->wait_for_pending_acks();
            // clear connection
            $this->channel->close();
            $this->connection->close();
            Log::info("RabbitMQProducer: publishTask success - ex: {$exchange} - routing: {$routingKey}".json_encode($task->payload()));
        } catch (\Exception $e){
            Log::error("RabbitMQProducer: publishTask error - ex: {$exchange} - routing: {$routingKey}");
        }
    }

    public static function sendTask(TaskInterface $task){
        $rb = new RabbitMQProducer();
        $rb->publishTask($task);
    }
}
