<?php

namespace Healios\Cache;

use Predis\Client;

readonly class CacheQueryService
{
    private Client $client;

    public function __construct(
        ClientProvider $clientProvider
    )
    {
        $this->client = $clientProvider->getClient();
    }

    public function search(string $searchKey): ?string
    {
        return $this->client->get($searchKey);
    }

    public function createRedisQueryKey(string $pattern, array $params): string
    {
        foreach ($params as $key => $value) {
            $pattern = str_replace("{{$key}}", $value, $pattern);
        }
        return preg_replace('#\\{(.*?)\\}#', '*', $pattern);
    }

    public function returnAsArray(?string $searchKeyResult): array
    {
        if (!$searchKeyResult) {
            return [];
        }

        try {
            return json_decode($searchKeyResult, true);
        } catch (Exception) {
            return [];
        }
    }
}
