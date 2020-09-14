<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

final class Product
{
    private ProductId $productId;

    private ProductPrice $price;

    private ProductPrice $productPriceSnapshot;

    public function __construct(ProductId $productId, ProductPrice $price)
    {
        $this->productId = $productId;
        $this->price = $price;
        $this->productPriceSnapshot = $price;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    public function equals(Product $product): bool
    {
        return $this->productId->equals($product->getProductId())
            && $this->price->getPrice()->equals($product->getPrice()->getPrice());
    }

    public function withPrice(ProductPrice $price): self
    {
        $product = clone $this;
        $product->price = $price;

        return $product;
    }

    public function getProductPriceSnapshot(): ProductPrice
    {
        return $this->productPriceSnapshot;
    }

    public function withPriceSnapshot(ProductPrice $price): self
    {
        $product = clone $this;
        $product->productPriceSnapshot = $price;

        return $product;
    }
}
