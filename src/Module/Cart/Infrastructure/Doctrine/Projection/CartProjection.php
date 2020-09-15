<?php

declare(strict_types=1);

namespace App\Module\Cart\Infrastructure\Doctrine\Projection;

use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Event\ProductRemoveFromCartEvent;
use App\Module\Cart\Infrastructure\Doctrine\Entity\CartReadModel;
use Doctrine\ORM\EntityManagerInterface;

final class CartProjection
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function whenCartCreated(CartCreatedEvent $event): void
    {
        $cart = (new CartReadModel())
            ->setId($event->getAggregateRootId()->getId())
            ->setUserId($event->getUserId())
            ->setProducts('[]');

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function whenProductAddedToCart(ProductAddedToCartEvent $event): void
    {
        $cart = $this->entityManager->getRepository(CartReadModel::class)->findOneBy([
            'id' => $event->getAggregateRootId()->getId(),
        ]);

        if (false === $cart instanceof CartReadModel) {
            return;
        }
        $product = $event->getProduct();
        $products = json_decode($cart->getProducts(), true, 512, JSON_THROW_ON_ERROR);
        $products[] = [
            'id' => $product->getProductId()->getId()->toString(),
            'price' => $product->getPrice()->getPrice()->getAmount(),
            'price_snapshot' => $product->getProductPriceSnapshot()->getPrice()->getAmount(),
            'currency' => $product->getPrice()->getPrice()->getCurrency()->getCode(),
            'currency_snapshot' => $product->getProductPriceSnapshot()->getPrice()->getCurrency()->getCode(),
        ];
        $cart->setProducts(json_encode($products, JSON_THROW_ON_ERROR));
        $this->entityManager->flush();
    }

    public function whenProductRemovedFromCart(ProductRemoveFromCartEvent $event): void
    {
        $cart = $this->entityManager->getRepository(CartReadModel::class)->findOneBy([
            'id' => $event->getAggregateRootId()->getId(),
        ]);

        if (false === $cart instanceof CartReadModel) {
            return;
        }

        $products = json_decode($cart->getProducts(), true, 512, JSON_THROW_ON_ERROR);
        foreach ($products as $index => $product) {
            if ($product['id'] === $event->getProduct()->getProductId()->getId()->toString()) {
                unset($products[$index]);
            }
        }

        $cart->setProducts(json_encode($products, JSON_THROW_ON_ERROR));
        $this->entityManager->flush();
    }
}
