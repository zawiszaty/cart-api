<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Infrastructure\Domain\DomainEvent;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitProjector implements Projector
{
    private const SHARED_EVENTS = 'shared_events';
    private AMQPChannel $channel;

    public function __construct(
        AMQPStreamConnection $connection
    )
    {
        $this->channel = $connection->channel();
        $this->channel->exchange_declare(self::SHARED_EVENTS, 'fanout', false, false, false);
    }

    public function apply(DomainEvent $event): void
    {
        $payload = $event->toArray();

        $this->channel->basic_publish(new AMQPMessage(json_encode($payload)), self::SHARED_EVENTS, get_class($event));
    }
}
