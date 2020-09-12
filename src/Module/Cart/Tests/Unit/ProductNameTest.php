<?php

declare(strict_types=1);


namespace App\Module\Cart\Tests\Unit;


use App\Module\Cart\Domain\Exception\ProductException;
use App\Module\Cart\Domain\ProductName;
use PHPUnit\Framework\TestCase;

final class ProductNameTest extends TestCase
{
    public function testWhenInvalidProductName(): void
    {
        $this->expectException(ProductException::class);

        ProductName::fromString('');
    }
}
