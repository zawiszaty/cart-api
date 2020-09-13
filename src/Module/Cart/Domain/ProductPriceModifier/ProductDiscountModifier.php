<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\ProductPriceModifier;

use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductPrice;
use App\Module\Cart\Domain\ProductPriceModifier;
use SplObjectStorage;

final class ProductDiscountModifier implements ProductPriceModifier
{
    public function modify(SplObjectStorage $products): SplObjectStorage
    {
        $modifiedProducts = new SplObjectStorage();

        /** @var Product $product */
        foreach ($products as $product) {
            $price = $product->getPrice();
            $newPrice = $price->getPrice()->divide(2);
            $product = $product->withPrice(ProductPrice::fromString($newPrice->getAmount(), $newPrice->getCurrency()->getCode()));
            $modifiedProducts->attach($product);
        }

        return $modifiedProducts;
    }

    public function supports(SplObjectStorage $products): bool
    {
        return 3 === $products->count();
    }
}
