<?php

declare(strict_types=1);

namespace App\Error;

use App\Contracts\ErrorHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppErrorHandler implements ErrorHandler
{
    public function handle(Throwable $th): void
    {
        // log error --dev
        Log::error('An error occurred while processing your request', [
            'message' => $th->getMessage(),
            'code' => $th->getCode(),
            'trace' => $th->getTraceAsString(),
            'file' => $th->getFile(),
            'line' => $th->getLine(),
        ]);

        // Send alert to external systems (Sentry, Datadog, etc) --prod
    }
}
