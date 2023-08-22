<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Domain;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\User\Domain\UserHelper;
use TicketSystem\Shared\Domain\DomainException;
use TicketSystem\User\Domain\GetUserInfo;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;

class GetUserInfoTest extends TestCase
{
    private GetUserInfo $getOwnInfo;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = \Mockery::mock(UserRepository::class);

        $this->getOwnInfo = new GetUserInfo($this->userRepository);
    }

    /** @test */
    public function it_should_return_the_user_data(): void
    {
        $user = UserHelper::user();

        $this->userRepository->shouldReceive('ofId')->once()->andReturn($user);

        $response = $this->getOwnInfo->execute($user->id);

        self::assertEquals($user->email, $response->email);
    }

    /** @test */
    public function it_should_return_the_user_data_with_email(): void
    {
        $user = UserHelper::user();

        $this->userRepository->shouldReceive('ofEmail')->once()->andReturn($user);

        $response = $this->getOwnInfo->execute($user->email);

        self::assertEquals($user->id, $response->id);
    }

    /** @test */
    public function it_should_throw_exception_when_user_not_found(): void
    {
        $this->userRepository->shouldReceive('ofId')->once()->andReturn(null);

        self::expectException(DomainException::class);

        $this->getOwnInfo->execute(UserId::create(UserHelper::userId()));
    }
}
