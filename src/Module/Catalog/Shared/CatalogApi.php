<?php

declare(strict_types=1);

namespace App\Module\Catalog\Shared;

use App\Module\Catalog\Shared\IO\Product;
use Ramsey\Uuid\UuidInterface;

interface CatalogApi
{
    public function isAvailable(UuidInterface $productId): bool;

    public function getProduct(UuidInterface $productId): Product;
}
