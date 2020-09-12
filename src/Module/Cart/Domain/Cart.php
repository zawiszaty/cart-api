<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

use App\Infrastructure\Domain\AggregateRoot;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Exception\CartException;
use Ramsey\Uuid\UuidInterface;
use SplObjectStorage;

final class Cart extends AggregateRoot
{
    private CartId $id;

    /** @var SplObjectStorage|Product[] */
    private SplObjectStorage $products;

    private UuidInterface $userId;

    public function __construct()
    {
        parent::__construct();
        $this->products = new SplObjectStorage();
    }

    public static function create(UuidInterface $user): self
    {
        $cart = new self();
        $cart->record(new CartCreatedEvent(CartId::generate(), $user));

        return $cart;
    }

    public function addProductToCart(Product $product): void
    {
        if (3 === $this->products->count()) {
            throw CartException::fromToManyProducts();
        }
        $this->record(new ProductAddedToCartEvent($this->getCardId(), $product));
    }

    public function getCardId(): CartId
    {
        return $this->id;
    }

    public function apply(DomainEvent $event): void
    {
        if ($event instanceof CartCreatedEvent) {
            $this->id = $event->getAggregateRootId();
            $this->userId = $event->getUserId();
        }
        if ($event instanceof ProductAddedToCartEvent) {
            $this->products->attach($event->getProduct());
        }
    }
}
