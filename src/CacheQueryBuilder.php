<?php

namespace Healios\Cache;

class CacheQueryBuilder
{
    private CacheQueryService $cacheQueryService;
    private string $queryKey;
    private array $queryParams;
    private string $mode = 'string';

    public function __construct()
    {
        $this->cacheQueryService = new CacheQueryService(
            new ClientProvider()
        );
    }

    public static function start(): CacheQueryBuilder
    {
        return new self();
    }

    public function setQueryKey(string $queryKey): CacheQueryBuilder
    {
        $this->queryKey = $queryKey;
        return $this;
    }

    public function setQueryParams(array $queryParams): CacheQueryBuilder
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    public function setMode(string $mode): CacheQueryBuilder
    {
        $this->mode = $mode;
        return $this;
    }

    public function query(): null|string|array
    {
        if (!$this->queryKey) {
            throw new Exception('You must set a QueryKey');
        }

        $searchKey = $this->cacheQueryService->createRedisQueryKey(
            $this->queryKey,
            $this->queryParams
        );
        var_dump($searchKey);
        $result = $this->cacheQueryService->search($searchKey);
        var_dump($result);
        if ('string' === $this->mode) {
            return $result;
        }
        return $this->cacheQueryService->returnAsArray(
            $result
        );
    }
}
