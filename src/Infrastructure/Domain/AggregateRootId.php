<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AggregateRootId
{
    protected UuidInterface $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): self
    {
        return new static(Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        if (Uuid::isValid($id)) {
            return new static(Uuid::fromString($id));
        }

        throw new DomainException('Id is not a valid uuid');
    }

    public function getId(): UuidInterface
    {
        return $this->uuid;
    }

    public function equals(AggregateRootId $id): bool
    {
        return $this->uuid->equals($id->getId());
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public static function fromUuid(UuidInterface $id): self
    {
        return new static($id);
    }

    public function __toString()
    {
        return (string) $this->getId();
    }
}
