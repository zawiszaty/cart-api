<?php

declare(strict_types=1);

namespace App\Module\Catalog\Infrastructure\Doctrine\Projection;

use App\Module\Catalog\Domain\Event\ProductCreatedEvent;
use App\Module\Catalog\Domain\Event\ProductNameChangedEvent;
use App\Module\Catalog\Domain\Event\ProductPriceChangedEvent;
use App\Module\Catalog\Domain\Event\ProductRemovedEvent;
use App\Module\Catalog\Infrastructure\Doctrine\Entity\ProductReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class ProductProjection
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function whenProductCreatedEvent(ProductCreatedEvent $event): void
    {
        $product = (new ProductReadModel())
            ->setId($event->getAggregateRootId()->getId())
            ->setName($event->getName()->getName())
            ->setPrice($event->getPrice()->getPrice()->getAmount())
            ->setCurrency($event->getPrice()->getPrice()->getCurrency()->getCode());
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function whenProductNameChangedEvent(ProductNameChangedEvent $event): void
    {
        $product = $this->entityManager->getRepository(ProductReadModel::class)->find($event->getAggregateRootId()
            ->getId());

        if (false === $product instanceof ProductReadModel) {
            return;
        }

        $product->setName($event->getName()->getName());
        $this->entityManager->flush();
    }

    public function whenProductPriceChangedEvent(ProductPriceChangedEvent $event): void
    {
        $product = $this->entityManager->getRepository(ProductReadModel::class)->find($event->getAggregateRootId()
            ->getId());

        if (false === $product instanceof ProductReadModel) {
            return;
        }

        $product->setPrice($event->getPrice()->getPrice()->getAmount());
        $product->setCurrency($event->getPrice()->getPrice()->getCurrency()->getCode());
        $this->entityManager->flush();
    }

    public function whenProductRemovedEvent(ProductRemovedEvent $event): void
    {
        $product = $this->entityManager->getRepository(ProductReadModel::class)->find($event->getAggregateRootId()
            ->getId());

        if (false === $product instanceof ProductReadModel) {
            return;
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
