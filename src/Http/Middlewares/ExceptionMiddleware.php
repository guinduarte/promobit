<?php

namespace App\Middlewares;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionMiddleware {
    public function onKernelException(ExceptionEvent $event) : void
    {
        $throwable = $event->getThrowable();

        $statusCode = method_exists($throwable, 'getStatusCode') ? $throwable->getStatusCode() : 400;

        if (!is_null(json_decode($throwable->getMessage()))) {
            $response = JsonResponse::fromJsonString($throwable->getMessage(), $statusCode);
            $event->setResponse($response);
        }
    }
}