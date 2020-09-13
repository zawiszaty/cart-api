<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\ProductPriceModifier;

use SplObjectStorage;

final class ProductPriceModifierFactory
{
    private array $modifiers;

    public function __construct()
    {
        $this->modifiers = [
            new ProductDiscountModifier(),
        ];
    }

    public function modify(SplObjectStorage $products): SplObjectStorage
    {
        foreach ($this->modifiers as $modifier) { // in real case I will use chain of responsibility pattern
            if ($modifier->supports($products)) {
                $products = $modifier->modify($products);
            }
        }

        return $products;
    }
}
