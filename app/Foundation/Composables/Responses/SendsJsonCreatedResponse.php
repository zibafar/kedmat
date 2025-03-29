<?php

namespace App\Foundation\Composables\Responses;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseFoundation;

/**
 * This $responseFormatter class should be
 * provided for this trait to work correctly
 */
trait SendsJsonCreatedResponse
{
    private function sendData(mixed $data, mixed $extra = null): Response
    {
        return response(
            $this->assembleDataResponse(
                data: $data,
                extra: $extra
            )->getBody(), ResponseFoundation::HTTP_CREATED);
    }

    private function assembleDataResponse(mixed $data, mixed $extra = null)
    {
        return (new $this->responseFormatter())
            ->setStatusCreated()
            ->setData($data)
            ->setExtra($extra);
    }
}
