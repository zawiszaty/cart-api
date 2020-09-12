<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

use App\Module\Cart\Domain\Exception\ProductException;
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
        if ((float) $price <= 0)
        {
            throw ProductException::fromWrongPrice();
        }

        return new self(new Money($price, new Currency($currency)));
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
}
