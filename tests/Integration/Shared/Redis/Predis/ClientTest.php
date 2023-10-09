<?php

declare(strict_types=1);

namespace Tests\Integration\Shared\Redis\Predis;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TicketSystem\Shared\Infrastructure\Redis\Predis\Client;

class ClientTest extends KernelTestCase
{
    /** @var Client */
    private Client $redisClient;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->redisClient = self::getContainer()->get('TestRedisClient');
    }

    /** @test */
    public function it_should_save_a_value(): void
    {
        $value = '123';
        $key = 'key';
        $this->redisClient->set($key, $value);

        self::assertEquals(
            $value,
            $this->redisClient->get($key)
        );

        $this->redisClient->delete($key);
    }

    /** @test */
    public function it_should_delete_a_value(): void
    {
        $value = '123';
        $key = 'key';
        $this->redisClient->set($key, $value);

        self::assertNotNull($this->redisClient->get($key));

        $this->redisClient->delete($key);

        self::assertNull($this->redisClient->get($key));
    }
}
