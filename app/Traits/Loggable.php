<?php

namespace App\Traits\Logging;

use Illuminate\Support\Facades\Log;

trait Loggable
{
    public function logError($message, $exception = null, $extraData = [])
    {
        $logData = array_merge([
            'error_message' => $exception ? $exception->getMessage() : $message,
            'file' => $exception ? $exception->getFile() : null,
            'line' => $exception ? $exception->getLine() : null,
            'class' => __CLASS__,
            'method' => debug_backtrace()[1]['function'] ?? null,
            'stack_trace' => $exception ? $exception->getTraceAsString() : null,
        ], $extraData);

        Log::error($message, $logData);
    }
}
