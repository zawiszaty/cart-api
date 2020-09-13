<?php

declare(strict_types=1);

namespace App\Module\Cart\Inftastructure\Repository;

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
        $this->events = array_merge($this->events, $cart->getEventCollection());
        $cart->publishEvents();
    }

    public function get(CartId $cartId): Cart
    {
        return Cart::restore($this->events);
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
