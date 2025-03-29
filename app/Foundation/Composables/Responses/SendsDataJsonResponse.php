<?php

namespace App\Foundation\Composables\Responses;

use Illuminate\Http\Response;

/**
 * This $responseFormatter class should be
 * provided for this trait to work correctly
 */
trait SendsDataJsonResponse
{
    private function sendData(mixed $data, mixed $extra = null): Response
    {
        return response(
            $this->assembleDataResponse(
                data: $data,
                extra: $extra
            )->getBody()
        );
    }

    private function assembleDataResponse(mixed $data, mixed $extra = null)
    {
        return (new $this->responseFormatter())
            ->setOk()
            ->setData($data)
            ->setExtra($extra);
    }
}
