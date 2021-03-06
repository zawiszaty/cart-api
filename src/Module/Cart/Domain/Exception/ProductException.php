<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\Exception;

use App\Infrastructure\Domain\DomainException;

final class ProductException extends DomainException
{
    public static function fromWrongPrice(): self
    {
        return new static('Price must be grater than zero');
    }
}
