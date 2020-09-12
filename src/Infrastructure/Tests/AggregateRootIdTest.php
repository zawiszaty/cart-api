<?php

declare(strict_types=1);

namespace App\Infrastructure\Tests;

use App\Infrastructure\Domain\AggregateRootId;
use App\Infrastructure\Domain\DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class AggregateRootIdTest extends TestCase
{
    public function testWhenIdIsGenerate(): void
    {
        $id = AggregateRootId::generate();

        self::assertTrue(Uuid::isValid($id->getId()->toString()));
    }

    public function testWhenIdIsCreateFromString(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $id = AggregateRootId::fromString($uuid);

        self::assertTrue(Uuid::isValid($id->getId()->toString()));
    }

    public function testWhenIdIsNotValidUuid(): void
    {
        $uuid = '¯\_( ͡° ͜ʖ ͡°)_/¯';

        $this->expectException(DomainException::class);

        AggregateRootId::fromString($uuid);
    }

    /**
     * @dataProvider uuidDataProvider
     */
    public function testWhenEquals($id, $secondId, $result): void
    {
        $uuid       = AggregateRootId::fromString($id);
        $secondUuid = AggregateRootId::fromString($secondId);

        self::assertSame($result, $uuid->equals($secondUuid));
    }

    public function uuidDataProvider(): array
    {
        return [
            [Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), false],
            ['2d76ebe6-62f6-4cad-9bbe-0e7decdf22c6', '2d76ebe6-62f6-4cad-9bbe-0e7decdf22c6', true],
        ];
    }
}
