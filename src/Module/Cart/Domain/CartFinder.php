<?php

declare(strict_types=1);

namespace App\Module\Cart\Domain;

use App\Module\Cart\Infrastructure\Doctrine\Entity\CartReadModel;
use Ramsey\Uuid\UuidInterface;

interface CartFinder
{
    public function getCart(UuidInterface $cartId): ?CartReadModel;
}
