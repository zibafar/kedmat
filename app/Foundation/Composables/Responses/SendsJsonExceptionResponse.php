<?php

namespace App\Foundation\Composables\Responses;

use Illuminate\Http\Response;

/**
 * This $responseFormatter class should be
 * provided for this trait to work correctly
 */
trait SendsJsonExceptionResponse
{
    private function sendMessage(string $message, mixed $extra = null): Response
    {
        return response(
            $this->assembleMessageResponse(
                message: $message,
                extra: $extra
            )->getBody()
        );
    }

    private function assembleMessageResponse(string $message, mixed $extra = null)
    {
        return (new $this->responseFormatter())
            ->setStatusCodeAsServerError()
            ->setMessage($message)
            ->setExtra($extra);
    }
}