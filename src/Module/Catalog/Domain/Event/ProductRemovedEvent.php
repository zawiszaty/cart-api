<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain\Event;

use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Catalog\Domain\ProductId;
use Ramsey\Uuid\Uuid;

final class ProductRemovedEvent extends DomainEvent
{
    private ProductId $productId;

    public function __construct(ProductId $productId)
    {
        parent::__construct(Uuid::uuid4());
        $this->productId = $productId;
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->productId;
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId()->toString(),
            'id' => $this->getAggregateRootId()->getId()->toString(),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        $event = new self(
            ProductId::fromString($payload['id']),
        );
        $event->eventId = Uuid::fromString($payload['event_id']);

        return $event;
    }
}
