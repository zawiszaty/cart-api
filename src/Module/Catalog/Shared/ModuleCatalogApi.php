<?php

declare(strict_types=1);

namespace App\Module\Catalog\Shared;

use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductRepositoryInterface;
use App\Module\Catalog\Shared\IO\ProductException;
use Ramsey\Uuid\UuidInterface;

final class ModuleCatalogApi implements CatalogApi
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function isAvailable(UuidInterface $productId): bool
    {
        $product = $this->productRepository->get(ProductId::fromString($productId->toString()));

        return $product instanceof Product && false === $product->isDeleted();
    }

    public function getProduct(UuidInterface $productId): \App\Module\Catalog\Shared\IO\Product
    {
        $product = $this->productRepository->get(ProductId::fromString($productId->toString()));

        if (false === $product instanceof Product) {
            throw ProductException::fromMissingProduct();
        }

        return new \App\Module\Catalog\Shared\IO\Product($product->getProductId()->getId(), $product->getPrice()
            ->getPrice());
    }
}
