<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Infrastructure\Symfony\Security\AccessToken;

use Monolog\Test\TestCase;
use TicketSystem\Shared\Infrastructure\Redis\Predis\Client;
use TicketSystem\User\Infrastructure\Symfony\Security\AccessToken\AccessTokenRepository;

class AccessTokenRepositoryTest extends TestCase
{
    private const ACCESS_TOKEN_EXPIRATION = 10;
    private Client $client;
    private AccessTokenRepository $accessTokenRepository;

    public function setUp(): void
    {
        $this->client = \Mockery::mock(Client::class);

        $this->accessTokenRepository = new AccessTokenRepository($this->client, self::ACCESS_TOKEN_EXPIRATION);
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function it_should_get_something(): void
    {
        $expected = '1';
        $this->client->shouldReceive('get')->once()->andReturn($expected);

        $result = $this->accessTokenRepository->getUserIdByToken('asd123');

        self::assertEquals($expected, $result);
    }

    /** @test */
    public function it_should_save_something(): void
    {
        $userId = '10';
        $accessToken = 'asd123';

        $this->client->shouldReceive('set')->once()->with($accessToken, $userId);
        $this->client->shouldReceive('expire')->once()->with($accessToken, self::ACCESS_TOKEN_EXPIRATION);

        $this->accessTokenRepository->save($accessToken, $userId);

        // nothing to assert
        self::assertTrue(true);
    }

    /** @test */
    public function it_should_refresh(): void
    {
        $accessToken = 'asd123';

        $this->client->shouldReceive('expire')->once()->with($accessToken, self::ACCESS_TOKEN_EXPIRATION);

        $this->accessTokenRepository->refreshAccessToken($accessToken);

        // nothing to assert
        self::assertTrue(true);
    }
}
