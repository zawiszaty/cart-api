<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use SplObjectStorage;

abstract class AggregateRoot
{
    /** @var SplObjectStorage|DomainEvent[] */
    private SplObjectStorage $eventCollection;

    public function __construct()
    {
        $this->eventCollection = new SplObjectStorage();
    }

    public function record(DomainEvent $event): void
    {
        $this->eventCollection->attach($event);
        $this->apply($event);
    }

    public function getEventCollection(): SplObjectStorage
    {
        return $this->eventCollection;
    }

    public function publishEvents(): void
    {
        $this->eventCollection = new SplObjectStorage();
    }

    public static function restore(array $domainEvents): AggregateRoot
    {
        $aggregate = new static();

        foreach ($domainEvents as $domainEvent) {
            $aggregate->apply($domainEvent);
        }

        return $aggregate;
    }

    abstract public function apply(DomainEvent $event): void;
}
