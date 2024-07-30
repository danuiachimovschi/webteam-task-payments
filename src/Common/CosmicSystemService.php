<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Common;

use Google\Protobuf\Internal\Message;
use Spiral\RoadRunner\GRPC\ContextInterface;
use WebTeam\Demo\CosmicSystems\Common\Command\CommandInterface;
use WebTeam\Demo\CosmicSystems\Providers\ProviderFactory;
use WebTeam\Demo\Cosmic\Proto\CosmicSystemsInterface;
use WebTeam\Demo\Cosmic\Proto\StatusRequest;
use WebTeam\Demo\Cosmic\Proto\StatusResponse;

class CosmicSystemService implements CosmicSystemsInterface
{
    public function __construct(
        private readonly ProviderFactory $factory
    ) {
    }

    public function statusCommand(ContextInterface $ctx, StatusRequest $in): StatusResponse
    {
        return $this->createCommand(AbstractConfiguration::INQUIRY, $in, $ctx)->execute();
    }

    private function createCommand(string $name, Message $request, ContextInterface $context): CommandInterface
    {
        $config = $this->factory->getConfiguration($request->getProvider());
        $className = $config->commands()[$name];

        return new $className($config, $request, $context);
    }
}