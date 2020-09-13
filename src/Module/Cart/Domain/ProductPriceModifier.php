<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

interface ProductPriceModifier
{
    public function modify(array $products): array;

    public function supports(array $products): bool;
}
