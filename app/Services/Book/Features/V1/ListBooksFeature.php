<?php

namespace App\Services\Book\Features\V1;

use App\Data\Resources\Book\BookPaginateResource;
use App\Domains\Auth\Jobs\FindAuthenticatableJob;
use App\Domains\Book\Jobs\GetBooksJob;
use App\Foundation\Composables\Features\_Features;
use Illuminate\Http\JsonResponse;

class ListBooksFeature extends _Features
{
    public function __construct(
    // private readonly FindAuthenticatableJob $findAuthJob,
        private readonly GetBooksJob $getBooksJob
    )
    {
    }

    /**
     * @param array $filters
     * @param int $limit
     * @return JsonResponse
     */
    public function serve(array $filters, int $limit=12): JsonResponse
    {
        try {
            // $auth = $this->findAuthJob->findAuthenticated();
            $result= $this->getBooksJob->handle($filters,$limit);
            $success = [
                'result' => BookPaginateResource::make($result),
            ];

            return $this->sendResponse(
                result: $success,
                message: "success",
            );

        } catch (\Throwable $ex) {
            $this->logError($ex, $m, ['c' => __CLASS__, 'l' => __LINE__]);
            return $this->send500Error(__CLASS__,__LINE__);

        }

    }
}
