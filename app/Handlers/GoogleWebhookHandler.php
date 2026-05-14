<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Contracts\GoogleSubscriptionForwarder;
use App\Contracts\WebhookHandler;
use App\DTO\Google\SubscriptionBuilder;
use App\DTO\Webhook;
use App\Exceptions\InvalidWebhookException;

class GoogleWebhookHandler implements WebhookHandler
{
    private const string SUPPORTED_PLATFORM = 'google';

    /**
     * @param  iterable<GoogleSubscriptionForwarder>  $forwarders
     */
    public function __construct(
        private readonly SubscriptionBuilder $subscriptionBuilder,
        private readonly iterable $forwarders,
    ) {}

    /**
     * @throws InvalidWebhookException
     */
    public function handle(Webhook $webhook): void
    {
        // use a factory class to extract relevant data into GoogleSubscription
        $subscription = $this->subscriptionBuilder->create($webhook);

        foreach ($this->forwarders as $forwarder) {
            if ($forwarder->supports($subscription)) {
                $forwarder->forward($subscription);
            }
        }
    }

    public function supports(Webhook $webhook): bool
    {
        return strtolower($webhook->getPlatform()) === self::SUPPORTED_PLATFORM;
    }
}
