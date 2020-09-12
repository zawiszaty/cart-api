<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain\Event;

use App\Infrastructure\Domain\DomainEvent;
use App\Module\Cart\Domain\CartId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CartCreatedEvent extends DomainEvent
{
    private CartId $cardId;

    private UuidInterface $userId;

    public function __construct(CartId $cardId, UuidInterface $userId)
    {
        parent::__construct(Uuid::uuid4());
        $this->cardId = $cardId;
        $this->userId = $userId;
    }

    public function getAggregateRootId(): CartId
    {
        return $this->cardId;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
