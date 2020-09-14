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

    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId()->toString(),
            'cart_id' => $this->getAggregateRootId()->getId()->toString(),
            'user_id' => $this->getUserId()->toString(),
        ];
    }

    public static function fromArray(array $payload): self
    {
        $event = new self(CartId::fromString($payload['cart_id']), Uuid::fromString($payload['user_id']));
        $event->eventId = Uuid::fromString($payload['event_id']);

        return $event;
    }
}
