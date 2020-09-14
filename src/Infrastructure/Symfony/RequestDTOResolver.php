<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RequestDTOResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        try {
            $reflection = new \ReflectionClass($argument->getType());
        } catch (\ReflectionException $e) {
            return false;
        }

        if ($reflection->implementsInterface(RequestDTOInterface::class)) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $class = $argument->getType();
        yield new $class($request);
    }
}
