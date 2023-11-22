<?php

declare(strict_types=1);

namespace Healios\Cache;

use Healios\Cache\Exception\CacheQueryKeyRequiredException;
use Healios\Cache\Exception\CacheQueryParametersRequiredException;
use Healios\Cache\Exception\CacheQueryValueRequiredException;
use Healios\Cache\Exception\CacheValueNotExistException;
use Healios\Cache\Query\Enum\TypeEnum;
use Healios\Cache\Query\QueryBuilder;

class StudyCacheService
{
    public function __construct(
        private readonly QueryBuilder $queryBuilder
    )
    {
    }

    /**
     * @throws CacheValueNotExistException
     * @throws CacheQueryKeyRequiredException
     * @throws CacheQueryParametersRequiredException
     */
    public function findById(string $studyId): string
    {
        return $this->queryBuilder
            ->resource(TypeEnum::STUDY)
            ->with('studyId', $studyId)
            ->get();
    }

    /**
     * @throws CacheValueNotExistException
     * @throws CacheQueryKeyRequiredException
     * @throws CacheQueryParametersRequiredException
     */
    public function getByClientId(string $clientId): string
    {
        return $this->queryBuilder
            ->resource(TypeEnum::STUDY)
            ->with('clientId', $clientId)
            ->get();
    }

    /**
     * @throws CacheQueryValueRequiredException
     * @throws CacheQueryParametersRequiredException
     * @throws CacheQueryKeyRequiredException
     */
    public function setStudy(string $clientId, string $studyId, mixed $study): void
    {
        $this->queryBuilder
            ->resource(TypeEnum::STUDY)
            ->with('clientId', $clientId)
            ->with('studyId', $studyId)
            ->set($study);
    }
}
