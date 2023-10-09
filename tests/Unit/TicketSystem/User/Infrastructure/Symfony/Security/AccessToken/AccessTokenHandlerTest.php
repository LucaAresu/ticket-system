<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Infrastructure\Symfony\Security\AccessToken;

use Monolog\Test\TestCase;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Tests\Helpers\User\UserHelper;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Infrastructure\Symfony\Security\AccessToken\AccessTokenHandler;
use TicketSystem\User\Infrastructure\Symfony\Security\AccessToken\AccessTokenRepository;

class AccessTokenHandlerTest extends TestCase
{
    private AccessTokenHandler $accessTokenHandler;
    private UserRepository $userRepository;
    private AccessTokenRepository $accessTokenRepository;

    protected function setUp(): void
    {
        $this->accessTokenRepository = \Mockery::mock(AccessTokenRepository::class);
        $this->accessTokenRepository->shouldReceive('refreshAccessToken')->zeroOrMoreTimes();

        $this->userRepository = \Mockery::mock(UserRepository::class);
        $this->userRepository->shouldReceive('ofId')->zeroOrMoreTimes()->andReturn(\Mockery::mock(User::class));

        $this->accessTokenHandler = new AccessTokenHandler(
            $this->accessTokenRepository,
            $this->userRepository
        );
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function it_should_return_an_user_badge(): void
    {
        $userId = UserHelper::userId();
        $this->accessTokenRepository->shouldReceive('getUserIdByToken')->once()->andReturn($userId);
        $this->accessTokenRepository->byDefault()->shouldReceive('refreshAccessToken')->once();

        $badge = $this->accessTokenHandler->getUserBadgeFrom('access-token');

        self::assertSame($badge->getUserIdentifier(), $userId);
    }

    /** @test */
    public function it_should_throw_exception_if_no_badge_found(): void
    {
        $this->accessTokenRepository->shouldReceive('getUserIdByToken')->once()->andReturn(null);

        $this->expectException(BadCredentialsException::class);

        $this->accessTokenHandler->getUserBadgeFrom('access-token');
    }

    /** @test */
    public function it_should_throw_exception_if_user_does_not_exist(): void
    {
        $userId = UserHelper::userId();
        $this->accessTokenRepository->shouldReceive('getUserIdByToken')->once()->andReturn($userId);
        $this->userRepository->byDefault()->shouldReceive('ofId')->once()->andReturn(null);

        $this->expectException(BadCredentialsException::class);

        $this->accessTokenHandler->getUserBadgeFrom('access-token');
    }
}
