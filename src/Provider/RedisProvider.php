<?php

declare(strict_types=1);

namespace Healios\Cache\Provider;

use Healios\Cache\Exception\CacheQueryKeyNotDeletedException;
use Healios\Cache\Exception\CacheQueryKeyNotSetException;
use Predis\Client;
use Predis\ClientInterface;

class RedisProvider implements RedisProviderInterface
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }


    public function getClient(): Client
    {
        return $this->client;
    }

    public function exists(string $key): bool
    {
        return (bool)$this->client->exists($key);
    }

    public function get(string $key): ?string
    {
        return $this->client->get($key);
    }

    /**
     * @throws CacheQueryKeyNotSetException
     */
    public function set(string $key, mixed $value, $expireResolution = null, $expireTTL = null, $flag = null): void
    {
        $result = $this->client->set($key, $value, $expireResolution, $expireTTL, $flag);

        if (!$result) {
            throw new CacheQueryKeyNotSetException();
        }
    }

    /**
     * @throws CacheQueryKeyNotDeletedException
     */
    public function delete(array|string $key): void
    {
        $result = $this->client->del($key);

        if ($result === 0) {
            throw new CacheQueryKeyNotDeletedException('the key(s) not deleted');
        }
    }
}
