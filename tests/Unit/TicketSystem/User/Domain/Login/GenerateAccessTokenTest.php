<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Domain\Login;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\User\Domain\UserHelper;
use TicketSystem\User\Domain\Login\GenerateAccessToken;
use TicketSystem\User\Domain\Login\StoreAccessToken;
use TicketSystem\User\Domain\UserId;

class GenerateAccessTokenTest extends TestCase
{
    /** @test */
    public function it_should_generate_a_token(): void
    {
        $storeAccessToken = \Mockery::mock(StoreAccessToken::class);
        $storeAccessToken->shouldReceive('execute')->once();

        $generateAccessToken = new GenerateAccessToken($storeAccessToken);

        $tokenResponse = $generateAccessToken->execute(UserId::create(UserHelper::userId()));

        self::assertNotEmpty($tokenResponse);

        \Mockery::close();
    }
}
