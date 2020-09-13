<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\ChangeProductPrice;

use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductPrice;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class ChangeProductPriceHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(ChangeProductPriceCommand $command): void
    {
        $product = $this->productRepository->get(ProductId::fromString($command->getProductId()->toString()));
        $product->changePrice(ProductPrice::fromString((string) $command->getPrice(), $command->getCurrency()));
        $this->productRepository->save($product);
    }
}
