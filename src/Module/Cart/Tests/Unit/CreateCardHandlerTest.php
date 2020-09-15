<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Application\CreateCart\CreateCartCommand;
use App\Module\Cart\Application\CreateCart\CreateCartHandler;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Infrastructure\Repository\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CreateCardHandlerTest extends TestCase
{
    private InMemoryCartRepository $cartRepository;

    private CreateCartHandler $handler;

    protected function setUp()
    {
        parent::setUp();
        $this->cartRepository = new InMemoryCartRepository();
        $this->handler = new CreateCartHandler($this->cartRepository);
    }

    public function testWhenCartIsCreated(): void
    {
        $userid = Uuid::uuid4();
        $command = new CreateCartCommand($userid);

        ($this->handler)($command);

        $events = $this->cartRepository->getEvents()[array_keys($this->cartRepository->getEvents())[0]];

        self::assertCount(1, $events);
        self::assertInstanceOf(CartCreatedEvent::class, $events[0]);
    }
}
