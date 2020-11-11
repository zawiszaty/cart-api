<?php

declare(strict_types=1);

namespace App\Module\Catalog\Tests\Unit;

use App\Module\Catalog\Application\ChangeProductPrice\ChangeProductPriceCommand;
use App\Module\Catalog\Application\ChangeProductPrice\ChangeProductPriceHandler;
use App\Module\Catalog\Domain\Event\ProductPriceChangedEvent;
use App\Module\Catalog\Infrastructure\Repository\InmemoryProductRepository;
use App\Module\Catalog\Tests\TestDoubles\ProductMother;
use PHPUnit\Framework\TestCase;

final class ChangeProductPriceHandlerTest extends TestCase
{
    private InmemoryProductRepository $repo;

    private ChangeProductPriceHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new InmemoryProductRepository();
        $this->handler = new ChangeProductPriceHandler($this->repo);
    }

    public function testWhenCreateProduct(): void
    {
        $product = ProductMother::create();
        $this->repo->save($product);

        ($this->handler)(new ChangeProductPriceCommand(
            $product->getProductId()->getId(),
            '300',
            'PLN'
        ));

        $events = $this->repo->getEvents()[(string) $product->getProductId()];
        self::assertCount(2, $events);
        self::assertInstanceOf(ProductPriceChangedEvent::class, $events[1]);
    }
}
