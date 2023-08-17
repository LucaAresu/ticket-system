<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Shared\Infrastructure\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Test\TestCase;
use TicketSystem\Shared\Infrastructure\Doctrine\DoctrineRepository;

class DoctrineRepositoryTest extends TestCase
{
    /** @test */
    public function it_should_error_when_no_repository_registered(): void
    {
        $mockRegistry = \Mockery::mock(Registry::class);
        $mockRegistry->shouldReceive('getManagerForClass')->andReturn(null);

        $this->expectException(\LogicException::class);

        $class = new class($mockRegistry) extends DoctrineRepository {
            protected function getEntityClassName(): string
            {
                return 'a';
            }

            protected function getEntityAliasName(): string
            {
                return 'a';
            }
        };
    }
}
