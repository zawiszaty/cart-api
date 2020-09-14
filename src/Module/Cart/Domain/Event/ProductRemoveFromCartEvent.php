<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\Event;

use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Domain\ProductPrice;
use Ramsey\Uuid\Uuid;

final class ProductRemoveFromCartEvent extends DomainEvent
{
    private CartId $cardId;

    private Product $product;

    public function __construct(CartId $cardId, Product $product)
    {
        parent::__construct(Uuid::uuid4());
        $this->cardId = $cardId;
        $this->product = $product;
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->cardId;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId()->toString(),
            'cart_id' => $this->getAggregateRootId()->getId()->toString(),
            'product' => [
                'id' => $this->product->getProductId()->getId()->toString(),
                'price' => $this->product->getPrice()->getPrice()->getAmount(),
                'snapshot_price' => $this->product->getProductPriceSnapshot()->getPrice()->getAmount(),
                'currency' => $this->product->getPrice()->getPrice()->getCurrency()->getCode(),
                'snapshot_currency' => $this->product->getProductPriceSnapshot()->getPrice()->getCurrency()->getCode(),
            ],
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        $product = (new Product(
            ProductId::fromString($payload['product']['id']),
            ProductPrice::fromString($payload['product']['price'], $payload['product']['currency'])
        ))->withPriceSnapshot(ProductPrice::fromString($payload['product']['snapshot_price'], $payload['product']['snapshot_currency']));
        $event = new self(CartId::fromString($payload['cart_id']), $product);
        $event->eventId = Uuid::fromString($payload['event_id']);

        return $event;
    }
}
