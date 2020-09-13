<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\Unit;

use App\Module\Catalog\Domain\Event\ProductCreatedEvent;
use App\Module\Catalog\Domain\Event\ProductNameChangedEvent;
use App\Module\Catalog\Domain\Event\ProductPriceChangedEvent;
use App\Module\Catalog\Domain\Event\ProductRemovedEvent;
use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductPrice;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    public function testWhenProductCreate(): void
    {
        $productName = ProductName::fromString('test');
        $price       = ProductPrice::fromString('2', 'PLN');
        $product     = Product::create($productName, $price);

        $events = $product->getEventCollection();
        $event  = $events[0];
        self::assertInstanceOf(ProductCreatedEvent::class, $event);
        /** @var ProductCreatedEvent $event */
        self::assertTrue($productName->equals($event->getName()));
        self::assertTrue($price->equals($event->getPrice()));
    }

    public function testWhenProductNameUpdated(): void
    {
        $productName = ProductName::fromString('test');
        $price       = ProductPrice::fromString('2', 'PLN');
        $product     = Product::create($productName, $price);
        $product->publishEvents();

        $newProductName = ProductName::fromString('test2');
        $product->changeName($newProductName);

        $events = $product->getEventCollection();
        $event  = $events[0];
        self::assertInstanceOf(ProductNameChangedEvent::class, $event);
        /** @var ProductNameChangedEvent $event */
        self::assertTrue($newProductName->equals($event->getName()));
    }

    public function testWhenProductPriceUpdated(): void
    {
        $productName = ProductName::fromString('test');
        $price       = ProductPrice::fromString('2', 'PLN');
        $product     = Product::create($productName, $price);
        $product->publishEvents();
        $newProductPrice = ProductPrice::fromString('20', 'PLN');

        $product->changePrice($newProductPrice);

        $events = $product->getEventCollection();
        $event  = $events[0];
        self::assertInstanceOf(ProductPriceChangedEvent::class, $event);
        /** @var ProductPriceChangedEvent $event */
        self::assertTrue($newProductPrice->equals($event->getPrice()));
    }

    public function testWhenProductDeleted(): void
    {
        $productName = ProductName::fromString('test');
        $price       = ProductPrice::fromString('2', 'PLN');
        $product     = Product::create($productName, $price);
        $product->publishEvents();

        $product->remove();

        $events = $product->getEventCollection();
        $event  = $events[0];
        self::assertInstanceOf(ProductRemovedEvent::class, $event);
        /** @var ProductRemovedEvent $event */
        self::assertTrue($product->isDeleted());
        self::assertTrue($product->getProductId()->equals($event->getAggregateRootId()));
    }
}
