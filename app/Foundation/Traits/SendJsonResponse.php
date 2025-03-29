<?php


namespace App\Foundation\Traits;


use App\Foundation\Enums\MessagesEnum;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;

trait SendJsonResponse
{
    /**
     * success response method.
     *
     * @param $result
     * @param string $message
     * @param string $ability
     * @return JsonResponse|Response|int
     */
    public function sendResponse($result,string $message,string $ability = 'public',int $status=200): Response|JsonResponse|int
    {
        if (!is_array($result)) {
            $result = ['result' => $result];
        }

        $response = [
                'ability' => $ability,
                'success' => true,
                'message' => $message,

            ] + $result;


        return response()->json($response, $status);
    }

    /**
     * return error response.
     *
     * @param array|bool $array
     * @param int $code
     * @return JsonResponse|int
     */
    public function sendErrorFromArray(array|bool $array,int $code = 404): JsonResponse|int
    {
        return $this->sendError(
            $array['error'] ?? 'error',
            [
                'error' => $array['msg'] ??  __('app.error.unknown')
            ],
            $code
        );
    }


    /**
     * return error response.
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse|int
     */
    public function sendError(string $error,array $errorMessages = [],int $code = 404):JsonResponse|int
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response = $response + $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function responser($class,$line): \Closure
    {
        return function ($code) use ($class,$line) {
            return $this->sendError(__("app.error.{$code}"), [
                'error' => 'Server error',
                'class' => $class,
                'line' => $line,
            ], $code);
        };
    }

    public function send500Error($class,$line)
    {
        return $this->sendError(__(MessagesEnum::ERROR_UNKNOWN->value), [
            'error' => 'Server Error',
            'class' => $class,
            'line' => $line,
        ], 500);
    }
}
