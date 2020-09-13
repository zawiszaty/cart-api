<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\ProductPriceModifier;

use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductPrice;
use App\Module\Cart\Domain\ProductPriceModifier;

final class ProductDiscountModifier implements ProductPriceModifier
{
    public function modify(array $products): array
    {
        $modifiedProducts = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $price = $product->getPrice();
            $newPrice = $price->getPrice()->divide(2);
            $product = $product->withPrice(ProductPrice::fromString($newPrice->getAmount(), $newPrice->getCurrency()
                ->getCode()));
            $modifiedProducts[$product->getProductId()->getId()->toString()] = $product;
        }

        return $modifiedProducts;
    }

    public function supports(array $products): bool
    {
        return 3 === count($products);
    }
}
