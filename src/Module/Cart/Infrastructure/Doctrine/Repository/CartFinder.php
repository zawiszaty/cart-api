<?php

declare(strict_types=1);

namespace App\Module\Cart\Infrastructure\Doctrine\Repository;

use App\Module\Cart\Domain\CartFinder as CartFinderInterface;
use App\Module\Cart\Infrastructure\Doctrine\Entity\CartReadModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class CartFinder extends ServiceEntityRepository implements CartFinderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartReadModel::class);
    }

    public function getCart(UuidInterface $cartId): ?CartReadModel
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $cartId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
