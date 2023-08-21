<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Infrastructure\Symfony\Communication\Http\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use TicketSystem\Shared\Application\Command\FailureResponse;

abstract class Controller
{
    public function jsonResponse(object $response): JsonResponse
    {
        return new JsonResponse(
            $response,
            $response instanceof FailureResponse ? $response->status : 200
        );
    }

    public function forbiddenResponse(string $message): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message
        ], 401);
    }
}
