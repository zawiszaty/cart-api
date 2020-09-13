<?php

declare(strict_types=1);

namespace App\Module\Catalog\Domain\Event;

use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainEvent;
use App\Module\Catalog\Domain\ProductId;
use App\Module\Catalog\Domain\ProductName;
use App\Module\Catalog\Domain\ProductPrice;
use Ramsey\Uuid\Uuid;

final class ProductCreatedEvent extends DomainEvent
{
    private ProductId $productId;

    private ProductName $name;

    private ProductPrice $price;

    public function __construct(ProductId $productId, ProductName $name, ProductPrice $price)
    {
        parent::__construct(Uuid::uuid4());
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
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

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }
}
