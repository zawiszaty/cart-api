<?php

declare(strict_types=1);

namespace App\Module\Catalog\Shared\IO;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

final class Product
{
    private UuidInterface $productId;

    private Money $price;

    public function __construct(UuidInterface $productId, Money $price)
    {
        $this->productId = $productId;
        $this->price = $price;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getPriceAmount(): string
    {
        return $this->price->getAmount();
    }

    public function getCurrencyCode(): string
    {
        return $this->price->getCurrency()->getCode();
    }

}
