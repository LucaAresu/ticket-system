<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Application;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\User\Domain\UserHelper;
use TicketSystem\Shared\Domain\DomainException;
use TicketSystem\User\Application\RetrieveAccessToken\RetrieveAccessTokenCommand;
use TicketSystem\User\Application\RetrieveAccessToken\RetrieveAccessTokenCommandRequest;
use TicketSystem\User\Domain\AccessToken\GenerateAccessToken;
use TicketSystem\User\Domain\AccessToken\GenerateAccessTokenResponse;
use TicketSystem\User\Domain\GetUserInfo;
use TicketSystem\User\Domain\UserDto;
use TicketSystem\User\Domain\UserPasswordVerifier;

class RetrieveAccessTokenTest extends TestCase
{
    private GenerateAccessToken $generateAccessToken;
    private UserPasswordVerifier $userPasswordVerifier;
    private RetrieveAccessTokenCommand $retrieveAccessToken;

    private GetUserInfo $getUserInfo;

    public function setUp(): void
    {
        $this->getUserInfo = \Mockery::mock(GetUserInfo::class);
        $this->getUserInfo->shouldReceive('execute')->once()->andReturn(UserDto::createFrom(UserHelper::user()));

        $this->generateAccessToken = \Mockery::mock(GenerateAccessToken::class);
        $this->generateAccessToken->shouldReceive('execute')->andReturn(GenerateAccessTokenResponse::create('123'));

        $this->userPasswordVerifier = \Mockery::mock(UserPasswordVerifier::class);
        $this->userPasswordVerifier->shouldReceive('execute')->andReturn(true);

        $this->retrieveAccessToken = new RetrieveAccessTokenCommand(
            $this->getUserInfo,
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
    public function it_should_fail_when_wrong_password(): void
    {
        $this->userPasswordVerifier->byDefault()->shouldReceive('execute')->once()->andReturn(false);

        $this->expectException(DomainException::class);

        $this->retrieveAccessToken->execute($this->getCreateAccessTokenRequest('wrong'));
    }

    private function getCreateAccessTokenRequest($password = null): RetrieveAccessTokenCommandRequest
    {
        return RetrieveAccessTokenCommandRequest::create(
            UserHelper::email(),
            $password ?? UserHelper::password()
        );
    }
}
