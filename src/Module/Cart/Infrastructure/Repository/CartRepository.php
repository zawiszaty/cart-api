<?php

declare(strict_types=1);

namespace App\Module\Cart\Infrastructure\Repository;

use App\Infrastructure\EventStore\EventStore;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;

final class CartRepository implements CartRepositoryInterface
{
    private EventStore $eventStore;
    private string $aggregateClass;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
        $this->aggregateClass = Cart::class;
    }

    public function save(Cart $cart): void
    {
        foreach ($cart->getEventCollection() as $event) {
            $this->eventStore->addEvent($event, $this->aggregateClass);
        }

        $cart->publishEvents();
    }

    public function get(CartId $cartId): ?Cart
    {
        return $this->eventStore->getAggregate($cartId, $this->aggregateClass);
    }
}
