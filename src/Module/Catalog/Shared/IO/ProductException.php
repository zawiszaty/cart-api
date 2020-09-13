<?php

declare(strict_types=1);

namespace App\Module\Catalog\Shared\IO;

final class ProductException extends \RuntimeException
{
    public static function fromMissingProduct(): self
    {
        return new self('Product is missing');
    }
}
