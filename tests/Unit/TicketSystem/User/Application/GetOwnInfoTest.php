<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Application;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\User\Domain\UserHelper;
use TicketSystem\Shared\Domain\DomainException;
use TicketSystem\User\Application\GetOwnInfo\GetOwnInfo;
use TicketSystem\User\Domain\UserRepository;

class GetOwnInfoTest extends TestCase
{
    private GetOwnInfo $getOwnInfo;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = \Mockery::mock(UserRepository::class);

        $this->getOwnInfo = new GetOwnInfo($this->userRepository);
    }

    /** @test */
    public function it_should_return_the_user_data(): void
    {
        $user = UserHelper::user();

        $this->userRepository->shouldReceive('ofId')->once()->andReturn($user);

        $response = $this->getOwnInfo->execute($user->id->id);

        self::assertEquals($user->email, $response->email);
    }

    /** @test */
    public function it_should_throw_exception_when_user_not_found(): void
    {
        $this->userRepository->shouldReceive('ofId')->once()->andReturn(null);

        self::expectException(DomainException::class);

        $this->getOwnInfo->execute(UserHelper::userId());
    }
}
