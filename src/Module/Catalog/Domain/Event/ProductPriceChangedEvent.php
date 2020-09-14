<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain\Event;

use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductPrice;
use Ramsey\Uuid\Uuid;

final class ProductPriceChangedEvent extends DomainEvent
{
    private ProductId $productId;

    private ProductPrice $price;

    public function __construct(ProductId $productId, ProductPrice $price)
    {
        parent::__construct(Uuid::uuid4());
        $this->productId = $productId;
        $this->price = $price;
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->productId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId()->toString(),
            'id' => $this->getProductId()->getId()->toString(),
            'price' => $this->getPrice()->getPrice()->getAmount(),
            'currency' => $this->getPrice()->getPrice()->getCurrency()->getCode(),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        $event = new self(
            ProductId::fromString($payload['id']),
            ProductPrice::fromString($payload['price'], $payload['currency'])
        );
        $event->eventId = Uuid::fromString($payload['event_id']);

        return $event;
    }
}
