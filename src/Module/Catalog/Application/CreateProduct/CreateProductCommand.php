<?php

declare(strict_types=1);


namespace App\Module\Catalog\Application\CreateProduct;


final class CreateProductCommand
{
    private string $name;

    private float $price;

    private string $currency;

    public function __construct(string $name, float $price, string $currency)
    {
        $this->name     = $name;
        $this->price    = $price;
        $this->currency = $currency;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
