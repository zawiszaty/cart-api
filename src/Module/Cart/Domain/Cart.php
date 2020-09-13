<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

use App\Infrastructure\Domain\AggregateRoot;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Event\ProductRemoveFromCartEvent;
use App\Module\Cart\Domain\Exception\CartException;
use App\Module\Cart\Domain\ProductPriceModifier\ProductPriceModifierFactory;
use Ramsey\Uuid\UuidInterface;
use SplObjectStorage;

final class Cart extends AggregateRoot
{
    private CartId $id;

    /** @var SplObjectStorage|Product[] */
    private SplObjectStorage $products;

    private UuidInterface $userId;

    private ProductPriceModifierFactory $priceModifiers;

    public function __construct()
    {
        parent::__construct();
        $this->products = new SplObjectStorage();
        $this->priceModifiers = new ProductPriceModifierFactory();
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

    public function removeProductFromCart(Product $product): void
    {
        if (0 === $this->products->count()) {
            throw CartException::fromEmptyCart();
        }
        $this->record(new ProductRemoveFromCartEvent($this->getCardId(), $product));
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
            $this->products = $this->priceModifiers->modify($this->products);
        }
        if ($event instanceof ProductRemoveFromCartEvent) {
            $this->products->detach($event->getProduct());
            $productSnapshots = new SplObjectStorage();

            foreach ($this->products as $product) {
                $productSnapshots->attach($product->withPrice(ProductPrice::fromString(
                    $product->getProductPriceSnapshot()->getPrice()->getAmount(),
                    $product->getProductPriceSnapshot()->getPrice()->getCurrency()->getCode()
                )));
            }
            $this->products = $this->priceModifiers->modify($productSnapshots);
        }
    }

    public function getProducts()
    {
        return $this->products;
    }
}
