<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\RemoveProduct;

use Ramsey\Uuid\UuidInterface;

final class RemoveProductCommand
{
    private UuidInterface $productId;

    public function __construct(UuidInterface $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }
}
