<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\Unit;

use App\Module\Catalog\Application\RemoveProduct\RemoveProductCommand;
use App\Module\Catalog\Application\RemoveProduct\RemoveProductHandler;
use App\Module\Catalog\Domain\Event\ProductRemovedEvent;
use App\Module\Catalog\Inftastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Tests\TestDoubles\ProductMother;
use PHPUnit\Framework\TestCase;

final class RemoveProductHandlerTest extends TestCase
{
    private InmemoryProductRepository $repo;

    private RemoveProductHandler $handler;

    protected function setUp()
    {
        parent::setUp();
        $this->repo = new InmemoryProductRepository();
        $this->handler = new RemoveProductHandler($this->repo);
    }

    public function testWhenCreateProduct(): void
    {
        $product = ProductMother::create();
        $this->repo->save($product);

        ($this->handler)(new RemoveProductCommand($product->getProductId()->getId()));

        $events = $this->repo->getEvents()[$product->getProductId()->getId()->toString()];
        self::assertCount(2, $events);
        self::assertInstanceOf(ProductRemovedEvent::class, $events[1]);
    }
}
