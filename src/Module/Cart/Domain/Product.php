<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

final class Product
{
    private ProductId $productId;

    private ProductName $productName;

    private ProductPrice $price;

    public function __construct(ProductId $productId, ProductName $productName, ProductPrice $price)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->price = $price;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getProductName(): ProductName
    {
        return $this->productName;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    public function equals(Product $product): bool
    {
        return $this->productName->getName() === $product->getProductName()->getName()
            && $this->productId->equals($product->getProductId())
            && $this->price->getPrice()->equals($product->getPrice()->getPrice());
    }
}
