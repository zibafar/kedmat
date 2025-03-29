<?php

namespace App\Foundation\Handlers\Responses;

use App\Foundation\Contracts\Responses\ResponseHandlerInterface;
use App\Foundation\Enums\MessagesEnum as Messages;
use App\Foundation\ValueObjects\Responses\Formatters\ApiResponseFormatterValues;
use App\Foundation\ValueObjects\Responses\ResponseValues;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * This Response Handler to Response Jobs is what
 * repository is to a Job that talks to Data layer.
 * It simply does the work. and Http jobs just call
 * its methods. It's behaviour object while concrete
 * formatter(ApiResponseFormatterValues) is the data
 * holder object (like DTO for repositories)
 */
class ApiResponseHandler implements ResponseHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function send(
        mixed $data,
        string $message,
        mixed $extra = null,
        ?int $statusCode = SymfonyResponse::HTTP_OK
    ): Response
    {
        $statusCode ??= SymfonyResponse::HTTP_OK;

        return response(
            content: $this->assemble(
                data: $data,
                message: $message,
                extra: $extra,
                statusCode: $statusCode
            )->getBody(),
            status: $statusCode
        );
    }

    /**
     * @inheritDoc
     */
    public function sendCreated(
        mixed $data = null,
        string $message = null,
        mixed $extra = null
    ): Response
    {
        $message = $message ??
            __(Messages::SUCCESS_RESOURCE_CREATED->value,
            ['resource' => 'resource']);

        return $this->send(
            data: $data,
            message: $message,
            extra: $extra,
            statusCode: SymfonyResponse::HTTP_CREATED
        );
    }

    /**
     * @inheritDoc
     */
    public function sendMessage(
        ?string $message = null,
        mixed $extra = null,
        int $statusCode = SymfonyResponse::HTTP_OK
    ): Response
    {
        return  response(
            content: $this->assemble(
                message: $message,
                extra: $extra,
                statusCode: $statusCode
            )->getBody(),
            status: $statusCode
        );
    }

    /**
     * @inheritDoc
     */
    public function sendData(
        mixed $data,
        ?string $message =null,
        mixed $extra = null
    ): Response
    {
        return response(
            $this->assemble(
                data: $data,
                message: $message,
                extra: $extra
            )->getBody()
        );
    }

    /**
     * ---------------------------------------------------
     * ----------------- Private Methods -----------------
     * ---------------------------------------------------
     */

    private function assemble(
        mixed $data = null,
        ?string $message = null,
        mixed $extra = null,
        int $statusCode = SymfonyResponse::HTTP_OK
    ): ResponseValues
    {
        return (new ApiResponseFormatterValues())
            ->succeed()
            ->setStatusCode($statusCode)
            ->setMessage($message)
            ->setData($data)
            ->setExtra($extra)
            ;
    }
}
