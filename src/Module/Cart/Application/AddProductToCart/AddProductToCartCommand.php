<?php

declare(strict_types=1);

namespace App\Module\Cart\Application\AddProductToCart;

use Ramsey\Uuid\UuidInterface;

final class AddProductToCartCommand
{
    public UuidInterface $cartId;

    public UuidInterface $productId;

    public function __construct(UuidInterface $cartId, UuidInterface $productId)
    {
        $this->cartId    = $cartId;
        $this->productId = $productId;
    }

    public function getCartId(): UuidInterface
    {
        return $this->cartId;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }
}
