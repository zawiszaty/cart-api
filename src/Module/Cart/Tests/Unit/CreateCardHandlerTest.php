<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Unit;

use App\Module\Cart\Application\CreateCart\CreateCardHandler;
use App\Module\Cart\Application\CreateCart\CreateCartCommand;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Inftastructure\Repository\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CreateCardHandlerTest extends TestCase
{
    private InMemoryCartRepository $cartRepository;

    private CreateCardHandler $handler;

    protected function setUp()
    {
        parent::setUp();
        $this->cartRepository = new InMemoryCartRepository();
        $this->handler        = new CreateCardHandler($this->cartRepository);
    }

    public function testWhenCartIsCreated(): void
    {
        $userid  = Uuid::uuid4();
        $command = new CreateCartCommand($userid);

        ($this->handler)($command);

        $events = $this->cartRepository->getEvents();
        self::assertSame(1, $events->count());
        self::assertInstanceOf(CartCreatedEvent::class, $events->current());
    }
}
