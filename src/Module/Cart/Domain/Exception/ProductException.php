<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\Exception;

use App\Infrastructure\Domain\DomainException;

final class ProductException extends DomainException
{
    public static function fromEmptyName(): self
    {
        return new static('Name cannot be empty');
    }

    public static function fromWrongPrice(): self
    {
        return new static('Price must be grater than zero');
    }
}
