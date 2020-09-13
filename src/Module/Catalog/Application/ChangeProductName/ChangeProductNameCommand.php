<?php

declare(strict_types=1);

namespace App\Module\Catalog\Application\ChangeProductName;

use Ramsey\Uuid\UuidInterface;

final class ChangeProductNameCommand
{
    private UuidInterface $productId;
    private string $name;

    public function __construct(UuidInterface $productId, string $name)
    {
        $this->productId = $productId;
        $this->name = $name;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
