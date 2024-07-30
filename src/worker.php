<?php

use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\GRPC\Invoker;
use WebTeam\Demo\CosmicSystems\Common\CosmicSystemService;
use WebTeam\Demo\Cosmic\Proto\CosmicSystemsInterface;
use WebTeam\Demo\CosmicSystems\Providers\ProviderFactory;
use WebTeam\Demo\CosmicSystems\Common\CosmicInvoker;

require __DIR__ . '/vendor/autoload.php';

$server = new Server((new CosmicInvoker(new Invoker())));
$server->registerService(CosmicSystemsInterface::class, new CosmicSystemService(new ProviderFactory()));
$server->serve(Worker::create());