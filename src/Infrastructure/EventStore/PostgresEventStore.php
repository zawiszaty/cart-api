<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Infrastructure\Domain\AggregateRoot;
use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use Doctrine\ORM\EntityManagerInterface;
use SplObjectStorage;

final class PostgresEventStore implements EventStore
{
    private EntityManagerInterface $entityManager;

    private EventStoreEventRepository $eventRepository;

    public function __construct(EntityManagerInterface $entityManager, EventStoreEventRepository $eventRepository)
    {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    public function getEvents(): SplObjectStorage
    {
        // TODO: Implement getEvents() method.
    }

    public function addEvent(DomainEvent $event, string $aggregateClass): void
    {
        $eventStoreEvent = new EventStoreEvent(
            $event->getAggregateRootId()->getId(),
            $aggregateClass,
            serialize($event),
            get_class($event),
        );
        $this->entityManager->persist($eventStoreEvent);
        $this->entityManager->flush();
    }

    public function getAggregateEvents(AggregateRootId $id, string $aggregateClass): SplObjectStorage
    {
        // TODO: Implement getAggregateEvents() method.
    }

    public function getAllAggregatesByType(string $aggregate): array
    {
        // TODO: Implement getAllAggregatesByType() method.
    }

    public function getAggregate(AggregateRootId $withId, string $aggregate): AggregateRoot
    {
        $events = $this->eventRepository->findBy([
            'aggregateRootId' => $withId->getId(),
        ]);
        /** @var EventStoreEvent[] $aggregateEvents */
        $domainEvents = [];

        /** @var EventStoreEvent $event */
        foreach ($events as $event) {
            $domainEvents[] = unserialize($event->getEvent());
        }

        return call_user_func($aggregate.'::restore', $domainEvents);
    }
}
