<?php

namespace Healios\Cache;

class StudyCacheService
{
    private const STUDY_KEY_PATTERN = 'studies:{clientId}:{studyId}';

    public function findById(string $studyId): ?array
    {
        $builder = CacheQueryBuilder::start();
        $builder->setMode('');
        return $builder->setQueryKey(self::STUDY_KEY_PATTERN)
            ->setQueryParams(['studyId' => $studyId])
            ->setMode('array')
            ->query();
    }

    public function getByClientId(string $clientId): ?array
    {
        $builder = CacheQueryBuilder::start();
        $builder->setMode('');
        return $builder->setQueryKey(self::STUDY_KEY_PATTERN)
            ->setQueryParams(['clientId' => $clientId])
            ->setMode('array')
            ->query();
    }

    public function setStudy(string $clientId, string $studyId, mixed $study): void
    {
        $provider = new ClientProvider();
        $service = new CacheSetService(
            $provider
        );
        $service->set(
            $service->createRedisSetKey(self::STUDY_KEY_PATTERN, [
                'clientId' => $clientId,
                'studyId' => $studyId
            ]),
            $study
        );
    }
}
