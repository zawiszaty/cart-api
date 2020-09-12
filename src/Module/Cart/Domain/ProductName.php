<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

use App\Module\Cart\Domain\Exception\ProductException;

final class ProductName
{
    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $name): ProductName
    {
        if (false === empty($name)) {
            return new self($name);
        }

        throw ProductException::fromEmptyName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
