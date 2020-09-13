<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function get(ProductId $productId): Product;
}
