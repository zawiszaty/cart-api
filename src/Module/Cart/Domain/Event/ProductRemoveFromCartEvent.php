<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\Event;

use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\Product;
use Ramsey\Uuid\Uuid;

final class ProductRemoveFromCartEvent extends DomainEvent
{
    private CartId $cardId;

    private Product $product;

    public function __construct(CartId $cardId, Product $product)
    {
        parent::__construct(Uuid::uuid4());
        $this->cardId  = $cardId;
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
}
