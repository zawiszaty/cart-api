<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\CreateProduct;

use App\Infrastructure\CommandBus\CommandHandler;
use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductPrice;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class CreateProductHandler extends CommandHandler
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
            ProductPrice::fromString($command->getPrice(), $command->getCurrency())
        );
        $this->productRepository->save($product);
    }
}
