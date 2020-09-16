<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\ChangeProductPrice;

use App\Infrastructure\CommandBus\CommandHandler;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductPrice;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class ChangeProductPriceHandler extends CommandHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(ChangeProductPriceCommand $command): void
    {
        $product = $this->productRepository->get(ProductId::fromUuid($command->getProductId()));
        $product->changePrice(ProductPrice::fromString($command->getPrice(), $command->getCurrency()));
        $this->productRepository->save($product);
    }
}
