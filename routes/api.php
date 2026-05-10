<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::get('/healthcheck', fn (): JsonResponse => response()->json([
    'status' => 'ok',
], 200));

Route::post('/webhook', WebhookController::class);
