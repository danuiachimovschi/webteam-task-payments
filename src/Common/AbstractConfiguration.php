<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Common;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;


abstract class AbstractConfiguration
{
    public const INQUIRY = 'inquiry';
    public const CHARGE = 'charge';

    private ClientInterface $client;

    abstract public function commands(): array;

    protected function clientOptions(): array
    {
        return ['http_errors' => 0];
    }

    public function client(): ClientInterface
    {
        if (isset($this->client)) {
            return $this->client;
        }

        return $this->client = new Client($this->clientOptions());
    }
}