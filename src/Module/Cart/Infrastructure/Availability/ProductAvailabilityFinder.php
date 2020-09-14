<?php

declare(strict_types=1);

namespace App\Module\Cart\Infrastructure\Availability;

use App\Module\Cart\Domain\ProductAvailabilityFinder as ProductAvailabilityFinderInterface;
use App\Module\Cart\Domain\ProductId;
use App\Module\Catalog\Shared\CatalogApi;

final class ProductAvailabilityFinder implements ProductAvailabilityFinderInterface
{
    private CatalogApi $catalogApi;

    public function __construct(CatalogApi $catalogApi)
    {
        $this->catalogApi = $catalogApi;
    }

    public function isAvailable(ProductId $productId): bool
    {
        return $this->catalogApi->isAvailable($productId->getId()); // this is dummy implementation of availability but in real project will be more case here
    }
}
