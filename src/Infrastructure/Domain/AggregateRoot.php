<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

abstract class AggregateRoot
{
    /** @var DomainEvent[] */
    private array $eventCollection;
    protected bool $deleted = false;

    public function __construct()
    {
        $this->eventCollection = [];
    }

    public function record(DomainEvent $event): void
    {
        $this->eventCollection[] = $event;
        $this->apply($event);
    }

    public function getEventCollection(): array
    {
        return $this->eventCollection;
    }

    public function publishEvents(): void
    {
        $this->eventCollection = [];
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

    public function isDeleted(): bool
    {
        return $this->deleted;
    }
}
