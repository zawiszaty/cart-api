<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\Unit;

use App\Module\Catalog\Application\ChangeProductName\ChangeProductNameCommand;
use App\Module\Catalog\Application\ChangeProductName\ChangeProductNameHandler;
use App\Module\Catalog\Domain\Event\ProductNameChangedEvent;
use App\Module\Catalog\Infrastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Tests\TestDoubles\ProductMother;
use PHPUnit\Framework\TestCase;

final class ChangeProductNameHandlerTest extends TestCase
{
    private InmemoryProductRepository $repo;

    private ChangeProductNameHandler $handler;

   protected function setUp(): void
   {
       parent::setUp();
       $this->repo    = new InmemoryProductRepository();
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

        $events = $this->repo->getEvents()[(string) $product->getProductId()];
        self::assertCount(2, $events);
        self::assertInstanceOf(ProductNameChangedEvent::class, $events[1]);
    }
}
