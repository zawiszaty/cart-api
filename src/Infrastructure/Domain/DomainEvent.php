<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use Ramsey\Uuid\UuidInterface;

abstract class DomainEvent
{
    protected UuidInterface $eventId;

    public function __construct(UuidInterface $eventId)
    {
        $this->eventId = $eventId;
    }

    public function getEventId(): UuidInterface
    {
        return $this->eventId;
    }

    abstract public function getAggregateRootId(): AggregateRootId;

    abstract public function toArray(): array;

    abstract public static function fromArray(array $payload): self;
}
