<?php

declare(strict_types=1);


namespace App\Module\Cart\Domain;


interface CartRepositoryInterface
{
    public function save(Cart $cart): void;

    public function get(CartId $cartId): Cart;
}
