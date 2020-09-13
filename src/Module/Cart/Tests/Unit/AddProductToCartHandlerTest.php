<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Application\AddProductToCart\AddProductToCartCommand;
use App\Module\Cart\Application\AddProductToCart\AddProductToCartHandler;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Inftastructure\Repository\InMemoryCartRepository;
use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductPrice;
use App\Module\Catalog\Inftastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Shared\ModuleCatalogApi;
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

    protected function setUp()
    {
        parent::setUp();
        $this->cartRepository = new InMemoryCartRepository();
        $this->productRepository = new InmemoryProductRepository();
        $this->catalogApi = new ModuleCatalogApi($this->productRepository);
        $this->handler = new AddProductToCartHandler(
            $this->cartRepository,
            $this->catalogApi
        );
        $this->cart = Cart::create(Uuid::uuid4());
        $this->product = Product::create(ProductName::fromString('test'), ProductPrice::fromString('20', 'PLN'));
        $this->productRepository->save($this->product);
        $this->cartRepository->save($this->cart);
    }

    public function testWhenAddProductToCart(): void
    {
        ($this->handler)(new AddProductToCartCommand(
            $this->cart->getCardId()->getId(),
            $this->product->getProductId()->getId()
        ));

        $events = $this->cartRepository->getEvents();
        self::assertSame(2, count($events));
        self::assertInstanceOf(ProductAddedToCartEvent::class, $events[1]);
    }

    public function testWhenProductIsNotAvailable(): void
    {
        ($this->handler)(new AddProductToCartCommand(
            $this->cart->getCardId()->getId(),
            Uuid::uuid4()
        ));

        $events = $this->cartRepository->getEvents();
        self::assertSame(2, count($events));
    }
}
