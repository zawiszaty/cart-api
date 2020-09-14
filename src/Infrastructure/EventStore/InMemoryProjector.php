<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Infrastructure\Domain\DomainEvent;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Event\ProductRemoveFromCartEvent;
use App\Module\Catalog\Domain\Event\ProductCreatedEvent;
use App\Module\Catalog\Domain\Event\ProductNameChangedEvent;
use App\Module\Catalog\Domain\Event\ProductPriceChangedEvent;
use App\Module\Catalog\Domain\Event\ProductRemovedEvent;
use Psr\Container\ContainerInterface;

final class InMemoryProjector implements Projector
{
    private ContainerInterface $container;

    /**
     * @var string[]
     */
    private array $eventConfig;

    public function __construct(ContainerInterface $container)
    {
        $this->eventConfig = [
            CartCreatedEvent::class => 'App\Module\Cart\Infrastructure\Doctrine\Projection\CartProjection::whenCartCreated',
            ProductAddedToCartEvent::class => 'App\Module\Cart\Infrastructure\Doctrine\Projection\CartProjection::whenProductAddedToCart',
            ProductRemoveFromCartEvent::class => 'App\Module\Cart\Infrastructure\Doctrine\Projection\CartProjection::whenProductRemovedFromCart',

            ProductCreatedEvent::class => 'App\Module\Catalog\Infrastructure\Doctrine\Projection\ProductProjection::whenProductCreatedEvent',
            ProductNameChangedEvent::class => 'App\Module\Catalog\Infrastructure\Doctrine\Projection\ProductProjection::whenProductNameChangedEvent',
            ProductPriceChangedEvent::class => 'App\Module\Catalog\Infrastructure\Doctrine\Projection\ProductProjection::whenProductPriceChangedEvent',
            ProductRemovedEvent::class => 'App\Module\Catalog\Infrastructure\Doctrine\Projection\ProductProjection::whenProductRemovedEvent',
        ];
        $this->container = $container;
    }

    public function apply(DomainEvent $event): void
    {
        if (isset($this->eventConfig[get_class($event)])) {
            [$service, $method] = explode('::', $this->eventConfig[get_class($event)]);
            $this->container->get($service)->{$method}($event);
        }
    }
}
