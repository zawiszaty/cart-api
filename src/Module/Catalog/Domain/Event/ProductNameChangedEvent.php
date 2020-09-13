<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain\Event;

use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductName;
use Ramsey\Uuid\Uuid;

final class ProductNameChangedEvent extends DomainEvent
{
    private ProductId $productId;

    private ProductName $name;

    public function __construct(ProductId $productId, ProductName $name)
    {
        parent::__construct(Uuid::uuid4());
        $this->productId = $productId;
        $this->name      = $name;
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->productId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getName(): ProductName
    {
        return $this->name;
    }
}
