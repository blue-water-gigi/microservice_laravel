<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Contracts\WebhookHandler;
use App\DTO\Google\SubscriptionBuilder;
use App\DTO\Webhook;

class GoogleWebhookHandler implements WebhookHandler
{
    private const string SUPPORTED_PLATFORM = 'google';

    public function __construct(private readonly SubscriptionBuilder $subscriptionBuilder) {}

    public function supports(Webhook $webhook): bool
    {
        return strtolower($webhook->getPlatform()) === self::SUPPORTED_PLATFORM;
    }

    public function handle(Webhook $webhook): void
    {
        // use a factory class to extract relevant data into GoogleSubscription
        $sub = $this->subscriptionBuilder->create($webhook);

        dd($sub);
    }
}
