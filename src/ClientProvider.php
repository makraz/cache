<?php

namespace Healios\Cache;

use Predis\Client;

class ClientProvider
{
    private Client $client;
    public function __construct()
    {
        $this->client = self::setClient();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    private static function setClient(): Client
    {
        return new Client([
            'scheme' => 'tcp',
            'host'   =>  '0.0.0.0',
            'port'   =>  6379,
        ]);
    }
}