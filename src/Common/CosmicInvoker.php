<?php

declare(strict_types=1);

namespace WebTeam\Demo\CosmicSystems\Common;

use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\InvokerInterface;
use Spiral\RoadRunner\GRPC\Method;
use Spiral\RoadRunner\GRPC\ServiceInterface;
use Spiral\RoadRunner\GRPC\StatusCode;
use WebTeam\Demo\Cosmic\Proto\ErrorResponse;

class CosmicInvoker implements InvokerInterface
{
    public function __construct(readonly private InvokerInterface $invoker)
    {
    }

    public function invoke(ServiceInterface $service, Method $method, ContextInterface $ctx, ?string $input): string
    {
        try {
            return $this->invoker->invoke($service, $method, $ctx, $input);
        } catch (GRPCException $e) {
            return (new ErrorResponse(['error' => $e->getCode()]))->serializeToString();
        } catch (\Throwable $e) {
            return (new ErrorResponse(['error' => StatusCode::UNKNOWN]))->serializeToString();
        }
    }
}