<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Application\AddProductToCart\AddProductToCartCommand;
use App\Module\Cart\Application\AddProductToCart\AddProductToCartHandler;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Exception\CartException;
use App\Module\Cart\Infrastructure\Availability\ProductAvailabilityFinder;
use App\Module\Cart\Infrastructure\Repository\InMemoryCartRepository;
use App\Module\Cart\Tests\Unit\TestDoubles\CartFixture;
use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Infrastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Shared\ModuleCatalogApi;
use App\Module\Catalog\Tests\TestDoubles\ProductMother;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class AddProductToCartHandlerTest extends TestCase
{
    private InMemoryCartRepository $cartRepository;

    private InmemoryProductRepository $productRepository;

    private ModuleCatalogApi $catalogApi;

    private AddProductToCartHandler $handler;

    private Cart $cart;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartRepository    = new InMemoryCartRepository();
        $this->productRepository = new InmemoryProductRepository();
        $this->catalogApi        = new ModuleCatalogApi($this->productRepository);
        $this->handler           = new AddProductToCartHandler(
            $this->cartRepository,
            $this->catalogApi,
            new ProductAvailabilityFinder($this->catalogApi)
        );
        $this->cart              = CartFixture::create()->getCart();
        $this->product           = ProductMother::create();
        $this->productRepository->save($this->product);
        $this->cartRepository->save($this->cart);
    }

    public function testWhenAddProductToCart(): void
    {
        ($this->handler)(new AddProductToCartCommand(
            $this->cart->getCardId()->getId(),
            $this->product->getProductId()->getId()
        ));

        $events = $this->cartRepository->getEvents()[(string) $this->cart->getCardId()];
        self::assertCount(2, $events);
        self::assertInstanceOf(ProductAddedToCartEvent::class, $events[1]);
    }

    public function testWhenProductIsNotAvailable(): void
    {
        $this->expectException(CartException::class);

        ($this->handler)(new AddProductToCartCommand(
            $this->cart->getCardId()->getId(),
            Uuid::uuid4()
        ));
    }

    public function testWhenCartIsMissing(): void
    {
        $this->expectException(CartException::class);

        ($this->handler)(new AddProductToCartCommand(
            Uuid::uuid4(),
            $this->product->getProductId()->getId()
        ));
    }
}
