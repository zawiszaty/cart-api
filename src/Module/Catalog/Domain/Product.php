<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain;

use App\Infrastructure\Domain\AggregateRoot;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Catalog\Domain\Event\ProductCreatedEvent;
use App\Module\Catalog\Domain\Event\ProductNameChangedEvent;
use App\Module\Catalog\Domain\Event\ProductPriceChangedEvent;
use App\Module\Catalog\Domain\Event\ProductRemovedEvent;

final class Product extends AggregateRoot
{
    private ProductId $productId;

    private ProductName $productName;

    private ProductPrice $price;

    public static function create(ProductName $productName, ProductPrice $price): self
    {
        $product = new static();
        $product->record(new ProductCreatedEvent(
            ProductId::generate(),
            $productName,
            $price
        ));

        return $product;
    }

    public function changeName(ProductName $productName): void
    {
        $this->record(new ProductNameChangedEvent(
            $this->productId,
            $productName
        ));
    }

    public function changePrice(ProductPrice $price): void
    {
        $this->record(new ProductPriceChangedEvent(
            $this->productId,
            $price
        ));
    }

    public function remove(): void
    {
        $this->record(new ProductRemovedEvent($this->productId));
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getProductName(): ProductName
    {
        return $this->productName;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    public function apply(DomainEvent $event): void
    {
        if ($event instanceof ProductCreatedEvent)
        {
            $this->productId   = $event->getAggregateRootId();
            $this->price       = $event->getPrice();
            $this->productName = $event->getName();
        }

        if ($event instanceof ProductNameChangedEvent)
        {
            $this->productName = $event->getName();
        }

        if ($event instanceof ProductPriceChangedEvent)
        {
            $this->price = $event->getPrice();
        }

        if ($event instanceof ProductRemovedEvent)
        {
            $this->deleted = true;
        }
    }
}
