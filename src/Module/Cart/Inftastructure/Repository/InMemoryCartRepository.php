<?php

declare(strict_types=1);

namespace App\Module\Cart\Inftastructure\Repository;

use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;
use Ramsey\Uuid\Uuid;

final class InMemoryCartRepository implements CartRepositoryInterface
{
    private \SplObjectStorage $events;

    public function __construct()
    {
        $this->events = new \SplObjectStorage();
    }

    public function save(Cart $cart): void
    {
        $this->events->attach(...$cart->getEventCollection());
        $cart->publishEvents();
    }

    public function get(CartId $cartId): Cart
    {
        return Cart::create(Uuid::uuid4());
    }

    public function getEvents(): \SplObjectStorage
    {
        return $this->events;
    }
}
