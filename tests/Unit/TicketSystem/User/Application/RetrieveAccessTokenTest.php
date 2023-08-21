<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Application;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\User\Domain\UserHelper;
use TicketSystem\Shared\Domain\DomainException;
use TicketSystem\User\Application\RetrieveAccessToken\RetrieveAccessToken;
use TicketSystem\User\Application\RetrieveAccessToken\RetrieveAccessTokenRequest;
use TicketSystem\User\Domain\Login\GenerateAccessToken;
use TicketSystem\User\Domain\Login\GenerateAccessTokenResponse;
use TicketSystem\User\Domain\UserPasswordVerifier;
use TicketSystem\User\Domain\UserRepository;

class RetrieveAccessTokenTest extends TestCase
{
    private GenerateAccessToken $generateAccessToken;
    private UserPasswordVerifier $userPasswordVerifier;
    private RetrieveAccessToken $retrieveAccessToken;

    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = \Mockery::mock(UserRepository::class);
        $this->userRepository->shouldReceive('ofEmail')->once()->andReturn(UserHelper::user());

        $this->generateAccessToken = \Mockery::mock(GenerateAccessToken::class);
        $this->generateAccessToken->shouldReceive('execute')->andReturn(GenerateAccessTokenResponse::create('123'));

        $this->userPasswordVerifier = \Mockery::mock(UserPasswordVerifier::class);
        $this->userPasswordVerifier->shouldReceive('execute')->andReturn(true);

        $this->retrieveAccessToken = new RetrieveAccessToken(
            $this->userRepository,
            $this->userPasswordVerifier,
            $this->generateAccessToken
        );
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function it_should_return_an_access_token(): void
    {
        $accessToken = '123';

        $response = $this->retrieveAccessToken->execute($this->getCreateAccessTokenRequest());

        self::assertEquals($accessToken, $response->accessToken);
    }

    /** @test */
    public function it_should_throw_when_user_not_found(): void
    {
        $this->userRepository->byDefault()->shouldReceive('ofEmail')->once()->andReturn(null);

        $this->expectException(DomainException::class);

        $this->retrieveAccessToken->execute($this->getCreateAccessTokenRequest());
    }

    /** @test */
    public function it_should_fail_when_wrong_password(): void
    {
        $this->userPasswordVerifier->byDefault()->shouldReceive('execute')->once()->andReturn(false);

        $this->expectException(DomainException::class);

        $this->retrieveAccessToken->execute($this->getCreateAccessTokenRequest('wrong'));
    }

    private function getCreateAccessTokenRequest($password = null): RetrieveAccessTokenRequest
    {
        return RetrieveAccessTokenRequest::create(
            UserHelper::email(),
            $password ?? UserHelper::password()
        );
    }
}
