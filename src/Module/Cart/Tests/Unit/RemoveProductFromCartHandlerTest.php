<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Application\RemoveProductFromCart\RemoveProductFromCartCommand;
use App\Module\Cart\Application\RemoveProductFromCart\RemoveProductFromCartHandler;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\Event\ProductRemoveFromCartEvent;
use App\Module\Cart\Domain\Exception\CartException;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Infrastructure\Repository\InMemoryCartRepository;
use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductPrice;
use App\Module\Catalog\Infrastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Shared\IO\ProductException;
use App\Module\Catalog\Shared\ModuleCatalogApi;
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

    protected function setUp()
    {
        parent::setUp();
        $this->cartRepository = new InMemoryCartRepository();
        $this->productRepository = new InmemoryProductRepository();
        $this->catalogApi = new ModuleCatalogApi($this->productRepository);
        $this->handler = new RemoveProductFromCartHandler(
            $this->cartRepository,
            $this->catalogApi
        );
        $this->cart = Cart::create(Uuid::uuid4());
        $this->product = Product::create(ProductName::fromString('test'), ProductPrice::fromString('20', 'PLN'));
        $this->cart->addProductToCart(new \App\Module\Cart\Domain\Product(
            ProductId::fromString($this->product->getProductId()->getId()->toString()),
            \App\Module\Cart\Domain\ProductPrice::fromString($this->product->getPrice()->getPrice()
                ->getAmount(), $this->product->getPrice()->getPrice()->getCurrency()->getCode())
        ));
        $this->productRepository->save($this->product);
        $this->cartRepository->save($this->cart);
    }

    public function testWhenRemoveProductFromCart(): void
    {
        ($this->handler)(new RemoveProductFromCartCommand(
            $this->cart->getCardId()->getId(),
            $this->product->getProductId()->getId()
        ));

        $events = $this->cartRepository->getEvents()[$this->cart->getCardId()->getId()->toString()];
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
