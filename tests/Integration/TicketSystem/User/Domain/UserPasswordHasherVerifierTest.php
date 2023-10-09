<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\User\Domain;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TicketSystem\User\Domain\UserPasswordHasher;
use TicketSystem\User\Domain\UserPasswordVerifier;

class UserPasswordHasherVerifierTest extends KernelTestCase
{
    private UserPasswordHasher $hasher;
    private UserPasswordVerifier $verifier;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $this->hasher = $container->get('TestUserPasswordHasher');
        $this->verifier = $container->get('TestUserPasswordVerifier');
    }

    /** @test */
    public function it_should_verify_a_password(): void
    {
        $password = 'asd123';

        $hashedPassword = $this->hasher->execute($password);

        self::assertTrue($this->verifier->execute($password, $hashedPassword));
    }

    /** @test */
    public function it_should_hash_a_password(): void
    {
        $password = 'asd123';

        $hashedPassword = $this->hasher->execute($password);

        self::assertNotEquals($password, $hashedPassword);
    }
}
