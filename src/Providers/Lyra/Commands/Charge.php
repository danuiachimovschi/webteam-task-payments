<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Providers\Lyra\Commands;

use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\StatusCode;
use WebTeam\Demo\Cosmic\Proto\Status;
use WebTeam\Demo\Cosmic\Proto\StatusRequest;
use WebTeam\Demo\Cosmic\Proto\StatusResponse;
use WebTeam\Demo\CosmicSystems\Common\Command\CommandInterface;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraApi;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraConfig;

class Charge implements CommandInterface
{
    public function __construct(
        readonly private LyraConfig       $configuration,
        readonly private StatusRequest    $request,
        readonly private ContextInterface $context
    ) {
    }

    public function execute(): StatusResponse
    {
        $api = new LyraApi($this->configuration);
        $api->init();
        $result = $api->charge($this->request);

        $response = new StatusResponse();

        if (!isset($result['status'])) {
            throw new GRPCException(
                message: 'Invalid response from provider',
                code: StatusCode::NOT_FOUND
            );
        }

        return $response->setStatus(
            match ($result['status']) {
                'in_progress' => Status::BROADCASTING,
                'closed' => Status::SCHEDULING,
                default => Status::UNDEFINED,
            }
        );
    }
}