<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\RemoveProduct;

use App\Infrastructure\CommandBus\CommandHandler;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class RemoveProductHandler extends CommandHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(RemoveProductCommand $command): void
    {
        $product = $this->productRepository->get(ProductId::fromString($command->getProductId()->toString()));
        $product->remove();
        $this->productRepository->save($product);
    }
}
