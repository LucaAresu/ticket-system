<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Infrastructure\Redis\Predis;

use Predis\Client as PredisClient;

class Client
{
    private PredisClient $client;

    public function __construct(
        string $host,
        int $port
    ) {
        $this->client = new PredisClient([
            'host' => $host,
            'port' => $port,
        ]);
    }

    public function set(string $key, string $value): void
    {
        $this->client->set($key, $value);
    }

    public function get(string $key): null|string
    {
        return $this->client->get($key);
    }

    public function delete(string $key): void
    {
        $this->client->del($key);
    }

    public function expire(string $key, int $seconds): void
    {
        $this->client->expire($key, $seconds);
    }
}
