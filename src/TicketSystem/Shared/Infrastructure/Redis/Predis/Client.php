<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Infrastructure\Redis\Predis;

use Predis\Client as PredisClient;

class Client
{
    private PredisClient $client;

    public function __construct(
        string $host,
        int $port,
        int $database,
    ) {
        $this->client = new PredisClient([
            'host' => $host,
            'port' => $port,
            'database' => $database,
        ]);
    }

    public function set(string $key, string $value): void
    {
        $this->client->set($key, $value);
    }

    public function get(string $key): null|string
    {
        /** @var string|null $value */
        $value =  $this->client->get($key);

        return $value ?: null;
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
