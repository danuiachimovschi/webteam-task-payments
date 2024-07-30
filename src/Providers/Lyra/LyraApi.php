<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra;

use WebTeam\Demo\Cosmic\Proto\StatusRequest;

class LyraApi
{
    const API_URL = 'https://lyra.wtstage.lol/api/v1';

    public function __construct(
        readonly private LyraConfig $configuration
    ) {
    }

    public function status(StatusRequest $request): array
    {
        $method = 'Status';
        $data = [
            'Username' => 'demo',
            'OrderId' => $request->getRequestId(),
            'Client' => "desktop",
            'Attributes' => null,
        ];

        return $this->post($method, $data);
    }

    public function charge(StatusRequest $request): array
    {
        $method = 'Charge';
        $data = [
            'Username' => 'demo',
            'Client' => "desktop",
            'NotificationURL' => "https://gate.wtstage.lol/api/v1/notification",
            'MessageId' => rand(1, 999999),
            'OrderId' => $request->getRequestId(),
            'Attributes' => null,
        ];

        return $this->post($method, $data);
    }

    public function init(): void
    {
        $this->configuration->client()->request("GET", self::API_URL . "/init");
    }

    private function post(string $method, array $data): array
    {
        $uuid = $this->configuration->generateUuid();

        $payload = json_encode([
            'method' => $method,
            'params' => [
                'Signature' => $this->configuration->sign($method, $uuid, $data),
                'UUID' => $uuid,
                'Data' => $data,
            ],
        ]);

        $response = $this->configuration->client()->post(
            self::API_URL,
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $payload,
            ]
        );

        $body = $response->getBody();

        return json_decode((string) $body, true);
    }
}