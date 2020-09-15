<?php

declare(strict_types=1);

namespace App\Module\Catalog\Infrastructure\Doctrine\Finder;

use App\Module\Catalog\Domain\ProductFinder as ProductFinderInterface;
use App\Module\Catalog\Infrastructure\Doctrine\Entity\ProductReadModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductFinder extends ServiceEntityRepository implements ProductFinderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductReadModel::class);
    }

    public function listAll(int $page, int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }
}
