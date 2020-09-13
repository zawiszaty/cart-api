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

final class Cart extends AggregateRoot
{
    private CartId $id;

    /** @var array|Product[] */
    private array $products;

    private UuidInterface $userId;

    private ProductPriceModifierFactory $priceModifiers;

    public function __construct()
    {
        parent::__construct();
        $this->products = [];
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
        if (3 === count($this->products)) {
            throw CartException::fromToManyProducts();
        }

        $this->record(new ProductAddedToCartEvent($this->getCardId(), $product));
    }

    public function removeProductFromCart(Product $product): void
    {
        if (0 === count($this->products)) {
            throw CartException::fromEmptyCart();
        }

        if (false === isset($this->products[$product->getProductId()->getId()->toString()])) {
            throw CartException::fromMissingProduct();
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
            $this->products[$event->getProduct()->getProductId()->getId()->toString()] = $event->getProduct();
            $this->products = $this->priceModifiers->modify($this->products);
        }
        if ($event instanceof ProductRemoveFromCartEvent) {
            unset($this->products[$event->getProduct()->getProductId()->getId()->toString()]);
            $productSnapshots = [];

            foreach ($this->products as $product) {
                $productSnapshots[$product->getProductId()->getId()
                    ->toString()] = $product->withPrice(ProductPrice::fromString(
                        $product->getProductPriceSnapshot()->getPrice()->getAmount(),
                        $product->getProductPriceSnapshot()->getPrice()->getCurrency()->getCode()
                    ));
            }
            $this->products = $this->priceModifiers->modify($productSnapshots);
        }
    }

    public function getProducts()
    {
        return $this->products;
    }
}
