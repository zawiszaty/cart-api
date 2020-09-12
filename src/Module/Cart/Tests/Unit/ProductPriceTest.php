<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Domain\Exception\ProductException;
use App\Module\Cart\Domain\ProductPrice;
use PHPUnit\Framework\TestCase;

final class ProductPriceTest extends TestCase
{
    public function testWhenCreateInvalidPrice(): void
    {
        $this->expectException(ProductException::class);

        ProductPrice::fromString('-123', 'PLN');
    }
}
