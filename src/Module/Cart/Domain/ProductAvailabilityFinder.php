<?php

declare(strict_types=1);


namespace App\Module\Cart\Domain;


interface ProductAvailabilityFinder
{
    public function isAvailable(ProductId $productId): bool;
}
