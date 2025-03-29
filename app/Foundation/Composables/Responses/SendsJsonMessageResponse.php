<?php

namespace App\Foundation\Composables\Responses;

use App\Foundation\ValueObjects\Responses\ResponseValues;
use Illuminate\Http\Response;

/**
* This $responseFormatter class should be
 * provided for this trait to work correctly
 */
trait SendsJsonMessageResponse
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

    private function assembleMessageResponse(string $message, mixed $extra = null): ResponseValues
    {
        return (new $this->responseFormatterClass())
            ->setOk()
            ->setMessage($message)
            ->setExtra($extra);
    }
}
