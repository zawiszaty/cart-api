<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\Unit;

use App\Module\Catalog\Application\ChangeProductName\ChangeProductNameCommand;
use App\Module\Catalog\Application\ChangeProductName\ChangeProductNameHandler;
use App\Module\Catalog\Domain\Event\ProductNameChangedEvent;
use App\Module\Catalog\Inftastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Tests\TestDoubles\ProductMother;
use PHPUnit\Framework\TestCase;

final class ChangeProductNameHandlerTest extends TestCase
{
    private InmemoryProductRepository $repo;

    private ChangeProductNameHandler $handler;

    protected function setUp()
    {
        parent::setUp();
        $this->repo = new InmemoryProductRepository();
        $this->handler = new ChangeProductNameHandler($this->repo);
    }

    public function testWhenChangeProductName(): void
    {
        $product = ProductMother::create();
        $this->repo->save($product);

        ($this->handler)(new ChangeProductNameCommand(
            $product->getProductId()->getId(),
            'test2'
        ));

        $events = $this->repo->getEvents()[$product->getProductId()->getId()->toString()];
        self::assertCount(2, $events);
        self::assertInstanceOf(ProductNameChangedEvent::class, $events[1]);
    }
}
