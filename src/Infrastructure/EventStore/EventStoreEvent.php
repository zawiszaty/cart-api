<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_store_event")
 */
final class EventStoreEvent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $aggregateRootId;

    /**
     * @ORM\Column(type="string")
     */
    private string $aggregateName;

    /**
     * @ORM\Column(type="text")
     */
    private string $event;

    /**
     * @ORM\Column(type="string")
     */
    private string $eventName;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private bool $relayed;

    public function __construct(UuidInterface $aggregateRootId, string $aggregateName, string $event, string $eventName)
    {
        $this->aggregateRootId = $aggregateRootId;
        $this->aggregateName   = $aggregateName;
        $this->event           = $event;
        $this->eventName       = $eventName;
        $this->relayed         = false;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAggregateRootId(): UuidInterface
    {
        return $this->aggregateRootId;
    }

    public function getAggregateName(): string
    {
        return $this->aggregateName;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function relay(): void
    {
        $this->relayed = true;
    }
}
