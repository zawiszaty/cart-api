<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\TestDoubles;

use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductPrice;

final class ProductMother
{
    public static function create(string $name = 'test', string $price = '2', string $currency = 'PLN'): Product
    {
        return Product::create(ProductName::fromString($name), ProductPrice::fromString($price, $currency));
    }
}
