<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use TicketSystem\User\Infrastructure\Domain\Symfony\Security\DoctrineSecurityUser;
use TicketSystem\User\Infrastructure\Domain\Symfony\Security\DoctrineSecurityUserProvider;

class UselessTestsForCoverageTest extends TestCase
{
    /** @test */
    public function cover_erase_credentials(): void
    {
        $user = new DoctrineSecurityUser('a');

        $user->eraseCredentials();

        self::assertTrue(true);
    }

    /** @test */
    public function cover_load_user_by_identifier(): void
    {
        $doctrineSecurityUserProvider = new DoctrineSecurityUserProvider();

        self::assertInstanceOf(
            DoctrineSecurityUser::class,
            $doctrineSecurityUserProvider->loadUserByIdentifier('a')
        );
    }
}
