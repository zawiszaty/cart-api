<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\ChangeProductPrice;

use Ramsey\Uuid\UuidInterface;

final class ChangeProductPriceCommand
{
    private UuidInterface $productId;

    private string $price;

    private string $currency;

    public function __construct(UuidInterface $productId, string $price, string $currency)
    {
        $this->productId = $productId;
        $this->price = $price;
        $this->currency = $currency;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
