<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\Exception;

use App\Infrastructure\Domain\DomainException;

final class CartException extends DomainException
{
    public static function fromToManyProducts(): self
    {
        return new static('Product can have only 3 products');
    }

    public static function fromEmptyCart(): self
    {
        return new static('Cart is empty');
    }
}
