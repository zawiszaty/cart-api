<?php

declare(strict_types=1);

namespace App\Module\Cart\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="cart_read_model")
 */
final class CartReadModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $userId;

    /**
     * @ORM\Column(type="json")
     */
    private string $products;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): CartReadModel
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function setUserId(UuidInterface $userId): CartReadModel
    {
        $this->userId = $userId;

        return $this;
    }

    public function getProducts(): string
    {
        return $this->products;
    }

    public function setProducts(string $products): CartReadModel
    {
        $this->products = $products;

        return $this;
    }
}
