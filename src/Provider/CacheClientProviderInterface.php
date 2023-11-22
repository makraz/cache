<?php

declare(strict_types=1);

namespace Healios\Cache\Provider;

interface CacheClientProviderInterface
{
    public function exists(string $key): bool;

    public function get(string $key): ?string;

    public function set(string $key, $value, $expireResolution = null, $expireTTL = null, $flag = null): void;

    public function delete(string|array $key): void;
}
