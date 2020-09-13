<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

use SplObjectStorage;

interface ProductPriceModifier
{
    public function modify(SplObjectStorage $products): SplObjectStorage;

    public function supports(SplObjectStorage $products): bool;
}
