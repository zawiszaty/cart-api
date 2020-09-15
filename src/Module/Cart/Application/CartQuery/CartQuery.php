<?php

declare(strict_types=1);

namespace App\Module\Cart\Application\CartQuery;

use App\Module\Cart\Domain\CartFinder;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CartQuery
{
    private CartFinder $finder;

    public function __construct(CartFinder $finder)
    {
        $this->finder = $finder;
    }

    public function getCart(UuidInterface $uuid): array
    {
        $cart = $this->finder->getCart($uuid);

        if (null === $cart) {
            throw new NotFoundHttpException('Cart not found');
        }
        $products = json_decode($cart->getProducts(), true, 512, JSON_THROW_ON_ERROR);

        $priceSum = 0;

        foreach ($products as $product) {
            $priceSum += (float) $product['price_snapshot'];
        }

        return [
            'id' => $cart->getId()->toString(),
            'user_id' => $cart->getUserId()->toString(),
            'products' => $products,
            'price_sum' => $priceSum,
        ];
    }
}
