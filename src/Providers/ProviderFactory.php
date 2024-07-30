<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Providers;

use WebTeam\Demo\Cosmic\Proto\Provider;
use WebTeam\Demo\CosmicSystems\Common\AbstractConfiguration;
use WebTeam\Demo\CosmicSystems\Providers\Lyra\LyraConfig;

class ProviderFactory
{
    public function getConfiguration($provider): AbstractConfiguration
    {
        return match ($provider) {
            Provider::Lyra => new LyraConfig(),
        };
    }
}