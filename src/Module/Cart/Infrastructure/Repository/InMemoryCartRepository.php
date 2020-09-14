<?php

declare(strict_types=1);

namespace App\Module\Cart\Infrastructure\Repository;

use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;

final class InMemoryCartRepository implements CartRepositoryInterface
{
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function save(Cart $cart): void
    {
        foreach ($cart->getEventCollection() as $event) {
            $this->events[$cart->getCardId()->getId()
                ->toString()][] = $event;
        }
        $cart->publishEvents();
    }

    public function get(CartId $cartId): ?Cart
    {
        if (isset($this->events[$cartId->getId()->toString()])) {
            return Cart::restore($this->events[$cartId->getId()->toString()]);
        }

        return null;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
