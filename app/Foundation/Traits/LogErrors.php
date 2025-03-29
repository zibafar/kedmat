<?php


namespace App\Foundation\Traits;


use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Throwable;

trait LogErrors
{

    public function logError(Throwable $ex, &$msgReturn = '', $logData = [])
    {
        self::doLogError($ex, $msgReturn, $logData);
    }


    public static function doLogError(Throwable $ex, &$msgReturn = '', $logData = [])
    {

        $msg = $ex->getMessage();
        $msgReturn = null;

        if (strpos($msg, 'Duplicate')) {
            preg_match("/for key '(?<KEY>[^']+)'/", $msg, $output_array);
            $msgReturn = __('db-duplicate.' . $output_array['KEY']);
        }

        Log::channel('easyc')->error('logError Throwable ex', $logData + ['msg' => $msg,]);
        if (!App::isLocal()) {
            Log::channel('telegram')->error('logError Throwable ex', $logData + ['msg' => $msg,]);
        }
        if (config('app.debug')) {
            Container::getInstance()->make(
                ExceptionHandler::class
            )->report($ex);
        }
    }


    public static function doLog($msg, &$msgReturn = '')
    {
        $msgReturn = null;

        if (strpos($msg, 'Duplicate')) {
            preg_match("/for key '(?<KEY>[^']+)'/", $msg, $output_array);
            $msgReturn = __('db-duplicate.' . $output_array['KEY']);
        }

        Log::channel('easyc')->error('log', ['msg' => $msg,]);
        if (!App::isLocal()) {
            Log::channel('telegram')->error('log', ['msg' => $msg,]);
        }
    }
}
