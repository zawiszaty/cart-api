<?php

declare(strict_types=1);

namespace App\Module\Catalog\Infrastructure\Repository;

use App\Infrastructure\EventStore\EventStore;
use App\Module\Catalog\Domain\Product;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductRepositoryInterface;

final class ProductRepository implements ProductRepositoryInterface
{
    private EventStore $eventStore;
    private string $aggregateClass;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
        $this->aggregateClass = Product::class;
    }

    public function save(Product $product): void
    {
        foreach ($product->getEventCollection() as $event) {
            $this->eventStore->addEvent($event, $this->aggregateClass);
        }

        $product->publishEvents();
    }

    public function get(ProductId $productId): ?Product
    {
        return $this->eventStore->getAggregate($productId, $this->aggregateClass);
    }
}
