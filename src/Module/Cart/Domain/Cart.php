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

    public function __construct(CartId $id, UuidInterface $userId, SplObjectStorage $products)
    {
        parent::__construct();
        $this->id       = $id;
        $this->products = $products;
        $this->userId   = $userId;
    }

    public static function create(UuidInterface $user): self
    {
        $cart = new self(CartId::generate(), $user, new SplObjectStorage());
        $cart->record(new CartCreatedEvent(CartId::generate(), $user));

        return $cart;
    }

    public function addProductToCart(Product $product): void
    {
        if (3 === $this->products->count())
        {
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
        if ($event instanceof ProductAddedToCartEvent)
        {
            $this->products->attach($event->getProduct());
        }
    }
}
