<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait Loggable
{
    public function logError(Exception $exception)
    {
        Log::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'class' => __CLASS__,
            'method' => debug_backtrace()[1]['function'] ?? null,
            'stack_trace' => $exception->getTraceAsString(),
        ]);
    }
}
