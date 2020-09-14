<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Infrastructure\Domain\DomainEvent;

interface Projector
{
    public function apply(DomainEvent $event): void;
}
