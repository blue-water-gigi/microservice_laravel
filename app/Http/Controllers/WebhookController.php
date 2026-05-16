<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\ErrorHandler;
use App\DTO\Webhook;
use App\Exceptions\InvalidWebhookException;
use App\Handlers\HandlerDelegator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class WebhookController extends Controller
{
    public function __construct(
        private readonly HandlerDelegator $handlerDelegator,
        private readonly ErrorHandler $errorHandler
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        // determine the platform from header
        $platform = strtolower($request->header('X-Webhook-Source', 'unknown'));

        // get the payload off the request
        $payload = $request->all();
        try {
            $webhook = new Webhook($platform, $payload);

            $this->handlerDelegator->delegate($webhook);

            return response()->json(status: Response::HTTP_NO_CONTENT);
        } catch (Throwable $th) {
            $this->errorHandler->handle($th);

            $status = $th instanceof InvalidWebhookException
                ? Response::HTTP_BAD_REQUEST
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json(status: $status);
        }
    }
}
