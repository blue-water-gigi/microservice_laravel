<?php

declare(strict_types=1);

namespace App\Contracts;

use Throwable;

interface ErrorHandler
{
    /**
     * Handle the given throwable.
     */
    public function handle(Throwable $th): void;
}
