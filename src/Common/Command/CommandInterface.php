<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Common\Command;

use WebTeam\Demo\Cosmic\Proto\StatusResponse;

interface CommandInterface
{
    public function execute(): StatusResponse;
}