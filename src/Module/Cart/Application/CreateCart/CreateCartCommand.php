<?php

declare(strict_types=1);

namespace App\Module\Cart\Application\CreateCart;

use Ramsey\Uuid\UuidInterface;

final class CreateCartCommand
{
    private UuidInterface $userId;

    public function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
