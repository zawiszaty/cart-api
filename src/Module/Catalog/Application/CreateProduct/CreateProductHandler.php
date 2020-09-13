<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\CreateProduct;

use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductPrice;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class CreateProductHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(CreateProductCommand $command): void
    {
        $product = Product::create(
            ProductName::fromString($command->getName()),
            ProductPrice::fromString((string) $command->getPrice(), $command->getCurrency())
        );
        $this->productRepository->save($product);
    }
}
