<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\Unit;

use App\Module\Catalog\Domain\Exception\ProductException;
use App\Module\Catalog\Domain\ProductName;
use PHPUnit\Framework\TestCase;

final class ProductNameTest extends TestCase
{
    public function testWhenInvalidProductName(): void
    {
        $this->expectException(ProductException::class);

        ProductName::fromString('');
    }
}


