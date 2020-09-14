<?php

declare(strict_types=1);

namespace App\Module\Catalog\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_read_model")
 */
final class ProductReadModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $name;
    /**
     * @ORM\Column(type="string")
     */
    private string $price;
    /**
     * @ORM\Column(type="string")
     */
    private string $currency;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): ProductReadModel
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProductReadModel
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): ProductReadModel
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): ProductReadModel
    {
        $this->currency = $currency;

        return $this;
    }
}
