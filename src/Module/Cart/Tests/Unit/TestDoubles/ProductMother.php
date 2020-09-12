<?php

declare(strict_types=1);


namespace App\Module\Cart\Tests\Unit\TestDoubles;


use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Domain\ProductName;
use App\Module\Cart\Domain\ProductPrice;

final class ProductMother
{
    public static function create(string $name, string $price, string $currency): Product
    {
        return new Product(ProductId::generate(), ProductName::fromString($name), ProductPrice::fromString($price, $currency));
    }
}
