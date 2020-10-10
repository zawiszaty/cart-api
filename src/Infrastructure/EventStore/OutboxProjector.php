<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Infrastructure\Domain\DomainEvent;

final class OutboxProjector implements Projector
{
    public function apply(DomainEvent $event): void
    {
    }
}
