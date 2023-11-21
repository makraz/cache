<?php

namespace Healios\Cache;

use Predis\Client;

class CacheSetService
{
    private Client $client;

    public function __construct(
        ClientProvider $clientProvider
    ) {
        $this->client = $clientProvider->getClient();
    }

    public function set(string $key, mixed $value): void
    {
        $this->client->set($key, $value);
    }

    public function createRedisSetKey(string $pattern, array $params): string
    {
        foreach ($params as $key => $value) {
            $pattern = str_replace("{{$key}}", $value, $pattern);
        }
        if (preg_match('#\\{(.*?)\\}#', $pattern)) {
            throw new Exception('Not all keys have been passed');
        }
        return $pattern;
    }
}
