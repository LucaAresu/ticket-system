<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Shared\Domain;

use PHPUnit\Framework\TestCase;
use TicketSystem\Shared\Domain\EntityId;

class EntityIdTest extends TestCase
{
    /** @test */
    public function it_should_create(): void
    {
        $id = '83ea8380-9d6b-4b7e-a064-8c0c2155b9cd';

        $obj = EntityId::create($id);

        self::assertEquals($id, $obj->id);
    }

    /** @test */
    public function it_should_throw_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $id = 'not-valid-uuid';
        EntityId::create($id);
    }

    /** @test */
    public function it_should_throw_exception_when_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $id = '';
        EntityId::create($id);
    }

    /** @test */
    public function entity_is_different_from_null(): void
    {
        $id = '83ea8380-9d6b-4b7e-a064-8c0c2155b9cd';

        $obj = EntityId::create($id);

        self::assertFalse($obj->isEqual(null));
    }
}
