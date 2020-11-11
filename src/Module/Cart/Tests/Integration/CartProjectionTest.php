<?php

declare(strict_types=1);

namespace App\Module\Cart\Tests\Integration;

use App\Infrastructure\Symfony\Kernel;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\Event\CartCreatedEvent;
use App\Module\Cart\Domain\Event\ProductAddedToCartEvent;
use App\Module\Cart\Domain\Event\ProductRemoveFromCartEvent;
use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Domain\ProductPrice;
use App\Module\Cart\Infrastructure\Doctrine\Entity\CartReadModel;
use App\Module\Cart\Infrastructure\Doctrine\Projection\CartProjection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CartProjectionTest extends KernelTestCase
{
    private CartProjection $projection;
    private EntityRepository $repo;
    private EntityManager $em;

    protected static $class = Kernel::class;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->projection = self::$container->get(CartProjection::class);
        $this->repo       = self::$container->get('doctrine.orm.entity_manager')->getRepository(CartReadModel::class);
        $this->em         = self::$container->get('doctrine.orm.entity_manager');
    }

    public function testWhenCartCreated(): void
    {
        $id = CartId::generate();
        $userId = Uuid::uuid4();
        $event = new CartCreatedEvent(
            $id,
            $userId,
        );

        $this->projection->whenCartCreated($event);

        /** @var CartReadModel $cart */
        $cart = $this->repo->find($id->getId());
        self::assertInstanceOf(CartReadModel::class, $cart);
        self::assertTrue($cart->getId()->equals($id->getId()));
        self::assertTrue($cart->getUserId()->equals($userId));
        self::assertSame('[]', $cart->getProducts());
    }

    public function testWhenProductAddedToCart(): void
    {
        $id = CartId::generate();
        $userId = Uuid::uuid4();
        $cart = (new CartReadModel())
            ->setId($id->getId())
            ->setUserId($userId)
            ->setProducts('[]');
        $this->em->persist($cart);
        $this->em->flush();
        $productId = ProductId::generate();
        $event = new ProductAddedToCartEvent(
            $id,
            new Product($productId, ProductPrice::fromString('20', 'PLN'))
        );

        $this->projection->whenProductAddedToCart($event);

        $cart = $this->repo->find($id->getId());

        self::assertInstanceOf(CartReadModel::class, $cart);
        self::assertSame(sprintf('[{"id":"%s","price":"20","price_snapshot":"20","currency":"PLN","currency_snapshot":"PLN"}]', $productId->getId()
            ->toString()), $cart->getProducts());
    }

    public function testWhenProductRemovedFromCart(): void
    {
        $id = CartId::generate();
        $userId = Uuid::uuid4();
        $productId = ProductId::generate();
        $cart = (new CartReadModel())
            ->setId($id->getId())
            ->setUserId($userId)
            ->setProducts(sprintf('[{"id": "%s"}]', $productId->getId()->toString()));
        $this->em->persist($cart);
        $this->em->flush();

        $event = new ProductRemoveFromCartEvent(
            $id,
            new Product($productId, ProductPrice::fromString('20', 'PLN'))
        );
        $this->projection->whenProductRemovedFromCart($event);

        $cart = $this->repo->find($id->getId());
        self::assertInstanceOf(CartReadModel::class, $cart);
        self::assertSame('[]', $cart->getProducts());
    }
}
