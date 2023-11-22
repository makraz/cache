<?php

declare(strict_types=1);

namespace Healios\Cache\Tests;

use Healios\Cache\Provider\RedisProvider;
use Healios\Cache\Query\QueryBuilder;
use Healios\Cache\StudyCacheService;
use PHPUnit\Framework\TestCase;
use Predis\Client;

class StudyCacheServiceTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = $this->getMockBuilder(Client::class)
            ->addMethods(['get', 'set'])
            ->getMock();
    }

    public function testFindById(): void
    {
        $studyId = 'studyId';
        $study = 'study';
        $key = "studies:{clientId}:$studyId";

        $this->client
            ->expects($this->once())
            ->method('get')
            ->with($key)
            ->willReturn($study);

        $studyCacheService = $this->getStudyCacheService();

        $value = $studyCacheService->findById($studyId);

        $this->assertEquals($study, $value);
    }

    public function testGetByClientId(): void
    {
        $clientId = 'clientId';
        $study = 'study';
        $key = "studies:$clientId:{studyId}";

        $this->client
            ->expects($this->once())
            ->method('get')
            ->with($key)
            ->willReturn($study);

        $studyCacheService = $this->getStudyCacheService();

        $value = $studyCacheService->getByClientId($clientId);

        $this->assertEquals($study, $value);
    }

    public function testSetStudy(): void
    {
        $clientId = 'clientId';
        $studyId = 'studyId';
        $study = 'study';
        $key = "studies:$clientId:$studyId";

        $this->client
            ->expects($this->once())
            ->method('set')
            ->with($key, $study)
            ->willReturn(true);

        $studyCacheService = $this->getStudyCacheService();

        $studyCacheService->setStudy(
            clientId: $clientId,
            studyId: $studyId,
            study: $study
        );
    }

    private function getStudyCacheService(): StudyCacheService
    {
        return new StudyCacheService(
            new QueryBuilder(
                new RedisProvider($this->client)
            )
        );
    }
}