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
        $this->events = array_merge($this->events, $product->getEventCollection());
        $product->publishEvents();
    }

    public function get(ProductId $productId): Product
    {
        return Product::restore($this->events);
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
