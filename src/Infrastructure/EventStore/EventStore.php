<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Infrastructure\Domain\AggregateRoot;
use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use SplObjectStorage;

interface EventStore
{
    /** @return DomainEvent[]|SplObjectStorage */
    public function getEvents(): SplObjectStorage;

    public function addEvent(DomainEvent $event, string $aggregateClass): void;

    /** @return DomainEvent[]|SplObjectStorage */
    public function getAggregateEvents(AggregateRootId $id, string $aggregateClass): SplObjectStorage;

    public function getAllAggregatesByType(string $aggregate): array;

    public function getAggregate(AggregateRootId $withId, string $aggregate): AggregateRoot;
}
