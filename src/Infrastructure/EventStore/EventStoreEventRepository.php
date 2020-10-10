<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class EventStoreEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventStoreEvent::class);
    }

    public function save(): void
    {
        $this->_em->flush();
    }
}
