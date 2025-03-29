<?php

namespace App\Services\Book\Features\V1;

use App\Foundation\Composables\Features\_Features;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ListBooksFeature extends _Features
{
    public function __construct(
     private readonly FindAuthenticatableJob $findAuthJob
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function serve(Request $request): JsonResponse
    {
        // TODO: implement
        try {
            $auth = $this->findAuthJob->findAuthenticated();
            return $this->sendResponse(
                result: 'ListBooksFeature - WIP.',
                message: "success"
            );

        } catch (\Throwable $ex) {
            $this->logError($ex, $m, ['c' => __CLASS__, 'l' => __LINE__]);
            return $this->send500Error(__CLASS__,__LINE__);

        }

    }
}
