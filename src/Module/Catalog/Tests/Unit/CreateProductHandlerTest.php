<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\Unit;

use App\Module\Catalog\Application\CreateProduct\CreateProductCommand;
use App\Module\Catalog\Application\CreateProduct\CreateProductHandler;
use App\Module\Catalog\Domain\Event\ProductCreatedEvent;
use App\Module\Catalog\Inftastructure\Repository\InmemoryProductRepository;
use PHPUnit\Framework\TestCase;

final class CreateProductHandlerTest extends TestCase
{
    private InmemoryProductRepository $repo;

    private CreateProductHandler $handler;

    protected function setUp()
    {
        parent::setUp();
        $this->repo = new InmemoryProductRepository();
        $this->handler = new CreateProductHandler($this->repo);
    }

    public function testWhenCreateProduct(): void
    {
        ($this->handler)(new CreateProductCommand('test', 20.0, 'PLN'));

        $events = $this->repo->getEvents()[array_keys($this->repo->getEvents())[0]];
        self::assertCount(1, $events);
        self::assertInstanceOf(ProductCreatedEvent::class, $events[0]);
    }
}
