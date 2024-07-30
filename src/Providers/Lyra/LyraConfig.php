<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra;

use Exception;
use WebTeam\Demo\CosmicSystems\Common\AbstractConfiguration;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands\Charge;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands\Inquiry;

class LyraConfig extends AbstractConfiguration
{
    private array $config;
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/secret.php';
    }

    public function commands(): array
    {
        return [
            AbstractConfiguration::INQUIRY => Inquiry::class,
            AbstractConfiguration::CHARGE => Charge::class,
        ];
    }

    public function generateUuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function sign(string $method, string $uuid, array $data, string $key = ''): string
    {
        $privateKey = openssl_pkey_get_private($this->config['private_key']);
        $plaintext = $method . $uuid . $this->serializeData($data);

        if (!openssl_sign($plaintext, $signature, $privateKey)) {
            throw new Exception('Failed to sign data');
        }

        return base64_encode($signature);
    }

    public function serializeData($object): string
    {
        $serialized = '';
        if (is_array($object)) {
            ksort($object);
            foreach ($object as $key => $value) {
                if (is_numeric($key)) {
                    $serialized .= self::serializeData($value);
                } else {
                    $serialized .= $key . self::serializeData($value);
                }
            }
        } else {
            return (string)$object;
        }

        return $serialized;
    }
}