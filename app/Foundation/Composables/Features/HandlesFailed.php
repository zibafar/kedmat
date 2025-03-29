<?php

namespace App\Foundation\Composables\Features;

use App\Domains\HttpErrorResponse\Jobs\ThrowBadRequestResponseJob;
use App\Foundation\Enums\MessagesEnum;
use Illuminate\Http\Exceptions\HttpResponseException;

trait HandlesFailed
{
    /**
     * Sometimes a single step among many steps of
     * a feature fails. the 'result' of such a step
     * is checked here and being handled.
     *
     * @param bool $result
     * @param mixed $extra
     * @return void
     * @throws HttpResponseException
     */
    private function handleIfFailed(
        bool $result,
        mixed $extra = null
    ): void
    {
        /**
         * @var ThrowBadRequestResponseJob $errorResponseJob
         */
        $errorResponseJob = resolve(ThrowBadRequestResponseJob::class);

        if (!$result) {
            throw $errorResponseJob->handle(
                message: __(MessagesEnum::ERROR_GENERIC->value),
                extra: $extra
            );
        }
    }
}
