<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Infrastructure\Domain\DomainEvent;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitProjector implements Projector
{
    private const PROJECTION = 'projection';
    private AMQPChannel $channel;

    public function __construct(
        AMQPStreamConnection $connection
    ) {
        $this->channel = $connection->channel();
        $this->channel->exchange_declare(self::PROJECTION, 'fanout', false, false, false);
    }

    public function apply(DomainEvent $event): void
    {
        $payload = $event->toArray();

        $this->channel->basic_publish(new AMQPMessage(json_encode($payload)), self::PROJECTION, get_class($event));
    }
}
