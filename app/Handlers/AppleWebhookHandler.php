<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Contracts\WebhookHandler;
use App\DTO\Webhook;

class AppleWebhookHandler implements WebhookHandler
{
    private const string SUPPORTED_PLATFORM = 'apple';

    public function supports(Webhook $webhook): bool
    {
        return strtolower($webhook->getPlatform()) === self::SUPPORTED_PLATFORM;
    }

    public function handle(Webhook $webhook): void
    {
        // use a factory class to extract relevant data into GoogleSubscription
        dump(self::SUPPORTED_PLATFORM);
    }
}
