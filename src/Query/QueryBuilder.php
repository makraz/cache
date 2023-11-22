<?php

declare(strict_types=1);

namespace Healios\Cache\Query;

use Healios\Cache\Exception\CacheQueryKeyRequiredException;
use Healios\Cache\Exception\CacheQueryParametersRequiredException;
use Healios\Cache\Exception\CacheQueryValueRequiredException;
use Healios\Cache\Exception\CacheValueNotExistException;
use Healios\Cache\Provider\CacheClientProviderInterface;
use Healios\Cache\Query\Enum\TypeEnum;
use Healios\Cache\Query\Enum\TypePatternEnum;

class QueryBuilder implements QueryBuilderInterface
{
    private CacheClientProviderInterface $cacheClientProvider;
    private Query $query;

    public function __construct(CacheClientProviderInterface $cacheClientProvider)
    {
        $this->cacheClientProvider = $cacheClientProvider;
    }

    public function resource(TypeEnum|string $type): QueryBuilder
    {
        $this->query = new Query();
        $this->query->type = $type instanceof TypeEnum ? $type->value : $type;
        $this->query->parameters = [];

        return $this;
    }

    public function with(string $field, string $value): QueryBuilder
    {
        $this->query->parameters[$field] = $value;

        return $this;
    }

    /**
     * @throws CacheQueryValueRequiredException
     * @throws CacheQueryKeyRequiredException
     * @throws CacheQueryParametersRequiredException
     */
    public function set(mixed $value): void
    {
        if (!$this->query->type) {
            throw new CacheQueryKeyRequiredException('You must set a type');
        }

        if (empty($this->query->parameters)) {
            throw new CacheQueryParametersRequiredException();
        }

        if (!$value) {
            throw new CacheQueryValueRequiredException();
        }

        $this->cacheClientProvider->set(
            $this->createRedisQueryKey(
                pattern: TypePatternEnum::fromName($this->query->type),
                parameters: $this->query->parameters
            ),
            $value
        );
    }

    /**
     * @throws CacheQueryKeyRequiredException
     * @throws CacheValueNotExistException
     * @throws CacheQueryParametersRequiredException
     */
    public function get(): string
    {
        if (!$this->query->type) {
            throw new CacheQueryKeyRequiredException('You must set a type');
        }

        if (empty($this->query->parameters)) {
            throw new CacheQueryParametersRequiredException();
        }

        $value = $this->cacheClientProvider->get(
            $this->createRedisQueryKey(
                pattern: TypePatternEnum::fromName($this->query->type),
                parameters: $this->query->parameters
            )
        );

        if (!$value) {
            throw new CacheValueNotExistException();
        }

        return $value;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }

    private function createRedisQueryKey(TypePatternEnum|string $pattern, array $parameters): string
    {
        $pattern = $pattern instanceof TypePatternEnum ? $pattern->value : $pattern;

        foreach ($parameters as $key => $value) {
            $pattern = \str_replace(
                "{{$key}}",
                $value,
                $pattern
            );
        }

        return \preg_replace('#\\{(.*?)\\}#', '*', $pattern);
    }
}