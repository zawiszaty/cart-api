<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Exception\CartException;
use App\Module\Cart\Tests\Unit\TestDoubles\ProductMother;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CartTest extends TestCase
{
    public function testWhenCartIsCreate(): void
    {
        $cart = Cart::create(Uuid::uuid4());

        $eventCollection = $cart->getEventCollection();
        $event           = $eventCollection->current();
        self::assertInstanceOf(CartCreatedEvent::class, $event);
        /** @var CartCreatedEvent $event */
        self::assertTrue($event->getAggregateRootId()->equals($event->getAggregateRootId()));
    }

    public function testWhenAddProductToCart(): void
    {
        $cart = Cart::create(Uuid::uuid4());
        $cart->publishEvents();
        $product = ProductMother::create('test', '20', 'PLN');

        $cart->addProductToCart($product);

        $eventCollection = $cart->getEventCollection();
        $event           = $eventCollection->current();
        self::assertInstanceOf(ProductAddedToCartEvent::class, $event);
        /** @var ProductAddedToCartEvent $event */
        self::assertTrue($event->getAggregateRootId()->equals($event->getAggregateRootId()));
        self::assertTrue($event->getProduct()->equals($product));
    }

    public function testWhenAddedToManyProductToCart(): void
    {
        $cart = Cart::create(Uuid::uuid4());

        $this->expectException(CartException::class);

        $cart->addProductToCart(ProductMother::create('test1', '20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('test2', '20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('test3', '20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('test4', '20', 'PLN'));
    }
}
