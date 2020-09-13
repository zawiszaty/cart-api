<?php

declare(strict_types=1);

namespace App\Module\Cart\Application\RemoveProductFromCart;

use Ramsey\Uuid\UuidInterface;

final class RemoveProductFromCart
{
    public UuidInterface $cartId;

    public UuidInterface $productId;

    public function __construct(UuidInterface $cartId, UuidInterface $productId)
    {
        $this->cartId = $cartId;
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
