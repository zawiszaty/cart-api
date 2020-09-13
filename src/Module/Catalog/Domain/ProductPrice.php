<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain;

use App\Module\Catalog\Domain\Exception\ProductException;
use Money\Currency;
use Money\Money;

final class ProductPrice
{
    private Money $price;

    private function __construct(Money $price)
    {
        $this->price = $price;
    }

    public static function fromString(string $price, string $currency): ProductPrice
    {
        if ((float) $price <= 0) {
            throw ProductException::fromWrongPrice();
        }

        return new self(new Money($price, new Currency($currency)));
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function equals(ProductPrice $price): bool
    {
        return $this->price->equals($price->price);
    }
}
