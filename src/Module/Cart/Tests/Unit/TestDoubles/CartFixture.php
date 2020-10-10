<?php

declare(strict_types=1);


namespace App\Module\Cart\Tests\Unit\TestDoubles;


use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Domain\ProductPrice;
use Ramsey\Uuid\Uuid;

final class CartFixture
{
    private Cart $cart;

    private Product $product;

    public static function create(): self
    {
        $fixture       = new self();
        $fixture->cart = Cart::create(Uuid::uuid4());

        return $fixture;
    }

    public function withProduct(string $productId, string $amount = '20', string $currency = 'PLN'): self
    {
        $this->product = new Product(ProductId::fromString($productId), ProductPrice::fromString($amount, $currency));
        $this->cart->addProductToCart($this->product);

        return $this;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
