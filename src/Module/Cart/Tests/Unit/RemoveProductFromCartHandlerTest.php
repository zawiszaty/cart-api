<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Application\RemoveProductFromCart\RemoveProductFromCartCommand;
use App\Module\Cart\Application\RemoveProductFromCart\RemoveProductFromCartHandler;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\Event\ProductRemoveFromCartEvent;
use App\Module\Cart\Domain\Exception\CartException;
use App\Module\Cart\Infrastructure\Repository\InMemoryCartRepository;
use App\Module\Cart\Tests\Unit\TestDoubles\CartFixture;
use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Infrastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Shared\IO\ProductException;
use App\Module\Catalog\Shared\ModuleCatalogApi;
use App\Module\Catalog\Tests\TestDoubles\ProductMother;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class RemoveProductFromCartHandlerTest extends TestCase
{
    private InMemoryCartRepository $cartRepository;

    private InmemoryProductRepository $productRepository;

    private ModuleCatalogApi $catalogApi;

    private RemoveProductFromCartHandler $handler;

    private Cart $cart;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartRepository    = new InMemoryCartRepository();
        $this->productRepository = new InmemoryProductRepository();
        $this->catalogApi        = new ModuleCatalogApi($this->productRepository);
        $this->handler           = new RemoveProductFromCartHandler(
            $this->cartRepository,
            $this->catalogApi
        );
        $cartFixture             = CartFixture::create();
        $this->cart              = $cartFixture->getCart();
        $this->product           = ProductMother::create('test');
        $cartFixture->withProduct($this->product->getProductId()->toString());
        $this->productRepository->save($this->product);
        $this->cartRepository->save($this->cart);
    }

    public function testWhenRemoveProductFromCart(): void
    {
        ($this->handler)(new RemoveProductFromCartCommand(
            $this->cart->getCardId()->getId(),
            $this->product->getProductId()->getId()
        ));

        $events = $this->cartRepository->getEvents()[(string) $this->cart->getCardId()];
        self::assertCount(3, $events);
        self::assertInstanceOf(ProductRemoveFromCartEvent::class, $events[2]);
    }

    public function testWhenProductIsMissing(): void
    {
        $this->expectException(ProductException::class);

        ($this->handler)(new RemoveProductFromCartCommand(
            $this->cart->getCardId()->getId(),
            Uuid::uuid4()
        ));
    }

    public function testWhenCartIsMissing(): void
    {
        $this->expectException(CartException::class);

        ($this->handler)(new RemoveProductFromCartCommand(
            Uuid::uuid4(),
            $this->product->getProductId()->getId()
        ));
    }
}
