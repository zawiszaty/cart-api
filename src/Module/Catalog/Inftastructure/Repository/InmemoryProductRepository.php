<?php

declare(strict_types=1);

namespace App\Module\Catalog\Inftastructure\Repository;

use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class InmemoryProductRepository implements ProductRepositoryInterface
{
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function save(Product $product): void
    {
        foreach ($product->getEventCollection() as $event) {
            $this->events[$product->getProductId()->getId()
                ->toString()][] = $event;
        }
        $product->publishEvents();
    }

    public function get(ProductId $productId): ?Product
    {
        if (isset($this->events[$productId->getId()
                ->toString()])) {
            return Product::restore($this->events[$productId->getId()
                ->toString()]);
        }

        return null;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
