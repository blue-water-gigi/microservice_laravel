<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Webhook;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // determine the platform from header
        $platform = strtolower($request->header('X-Webhook-Source', 'unknown'));

        // get the payload off the request
        $payload = $request->all();

        // instantiate the webhook DTO

        /**
         * @phpstan-ignore new.resultUnused
         */
        $webhook = new Webhook($platform, $payload);

        // do something with webhook

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
