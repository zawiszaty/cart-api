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

    private Projector $projector;

    public function __construct(EntityManagerInterface $entityManager, EventStoreEventRepository $eventRepository, Projector $projector)
    {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
        $this->projector = $projector;
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
            json_encode($event->toArray(), JSON_THROW_ON_ERROR),
            get_class($event),
        );
        $this->entityManager->persist($eventStoreEvent);
        $this->entityManager->flush();
        $this->projector->apply($event);
    }

    public function getAggregateEvents(AggregateRootId $id, string $aggregateClass): SplObjectStorage
    {
        // TODO: Implement getAggregateEvents() method.
    }

    public function getAllAggregatesByType(string $aggregate): array
    {
        // TODO: Implement getAllAggregatesByType() method.
    }

    public function getAggregate(AggregateRootId $withId, string $aggregate): ?AggregateRoot
    {
        $events = $this->eventRepository->findBy([
            'aggregateRootId' => $withId->getId(),
        ]);

        if (0 === count($events)) {
            return null;
        }
        /** @var EventStoreEvent[] $aggregateEvents */
        $domainEvents = [];

        /** @var EventStoreEvent $event */
        foreach ($events as $event) {
            $domainEvents[] = call_user_func($event->getEventName().'::fromArray', json_decode($event->getEvent(), true, 512, JSON_THROW_ON_ERROR));
        }

        return call_user_func($aggregate.'::restore', $domainEvents);
    }
}
