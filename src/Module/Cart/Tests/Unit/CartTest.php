<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Event\ProductRemoveFromCartEvent;
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
        $event = $eventCollection[0];
        self::assertInstanceOf(CartCreatedEvent::class, $event);
        /* @var CartCreatedEvent $event */
        self::assertTrue($event->getAggregateRootId()->equals($event->getAggregateRootId()));
    }

    public function testWhenAddProductToCart(): void
    {
        $cart = Cart::create(Uuid::uuid4());
        $cart->publishEvents();
        $product = ProductMother::create('20', 'PLN');

        $cart->addProductToCart($product);

        $eventCollection = $cart->getEventCollection();
        $event = $eventCollection[0];
        self::assertInstanceOf(ProductAddedToCartEvent::class, $event);
        /* @var ProductAddedToCartEvent $event */
        self::assertTrue($event->getAggregateRootId()->equals($event->getAggregateRootId()));
        self::assertTrue($event->getProduct()->equals($product));
    }

    public function testWhenAddedToManyProductToCart(): void
    {
        $cart = Cart::create(Uuid::uuid4());

        $this->expectException(CartException::class);

        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
    }

    public function testWhenAddedToCartApplyDiscount(): void
    {
        $cart = Cart::create(Uuid::uuid4());

        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $products = $cart->getProducts();
        $sum = 0;
        foreach ($products as $product) {
            $sum += (float) $product->getPrice()->getPrice()->getAmount();
        }
        self::assertSame(30.0, $sum);
    }

    public function testDiscountCleaning(): void
    {
        $cart = Cart::create(Uuid::uuid4());
        $product = ProductMother::create('20', 'PLN');
        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $cart->addProductToCart(ProductMother::create('20', 'PLN'));
        $cart->addProductToCart($product);
        $cart->removeProductFromCart($product);
        $products = $cart->getProducts();
        $sum = 0;
        foreach ($products as $product) {
            $sum += (float) $product->getPrice()->getPrice()->getAmount();
        }
        self::assertSame(40.0, $sum);
    }

    public function testWhenRemoveFromCart(): void
    {
        $cart = Cart::create(Uuid::uuid4());
        $product = ProductMother::create('20', 'PLN');
        $cart->addProductToCart($product);
        $cart->publishEvents();

        $cart->removeProductFromCart($product);

        $eventCollection = $cart->getEventCollection();
        $event = $eventCollection[0];
        self::assertInstanceOf(ProductRemoveFromCartEvent::class, $event);
        /* @var ProductRemoveFromCartEvent $event */
        self::assertTrue($event->getAggregateRootId()->equals($event->getAggregateRootId()));
        self::assertTrue($event->getProduct()->equals($product));
    }
}
