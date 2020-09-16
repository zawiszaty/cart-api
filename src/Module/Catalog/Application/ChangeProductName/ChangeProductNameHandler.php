<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\ChangeProductName;

use App\Infrastructure\CommandBus\CommandHandler;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class ChangeProductNameHandler extends CommandHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(ChangeProductNameCommand $command): void
    {
        $product = $this->productRepository->get(ProductId::fromUuid($command->getProductId()));
        $product->changeName(ProductName::fromString($command->getName()));
        $this->productRepository->save($product);
    }
}
