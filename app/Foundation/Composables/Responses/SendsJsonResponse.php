<?php

namespace App\Foundation\Composables\Responses;

use Illuminate\Http\Response;

trait SendsJsonResponse
{
    public function sendData(mixed $data, mixed $extra = null): Response
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
        return (new $this->responseValuesClass())
            ->setOk()
            ->setData($data)
            ->setExtra($extra);
    }
}
