<?php

namespace App\Foundation\Composables\Responses;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseFoundation;

/**
 * This $responseFormatter class should be
 * provided for this trait to work correctly
 */
trait SendsJsonNotFoundResponse
{
    private function sendData(string $message, mixed $extra = null): Response
    {
        return response(
            $this->assembleDataResponse(
                message: $message,
                extra: $extra
            )->getBody(), ResponseFoundation::HTTP_NOT_FOUND);
    }

    private function assembleDataResponse(string $message, mixed $extra = null)
    {
        return (new $this->responseFormatter())
            ->setStatusCreated()
            ->setMessage($message)
            ->setExtra($extra);
    }
}
