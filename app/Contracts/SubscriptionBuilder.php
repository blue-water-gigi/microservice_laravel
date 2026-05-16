<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\Google\Subscription;
use App\DTO\Webhook;
use App\Exceptions\InvalidWebhookException;

interface SubscriptionBuilder
{
    /**
     * Create a Subscription DTO from a Webhook payload.
     *
     * @throws InvalidWebhookException
     */
    public function create(Webhook $webhook): Subscription;
}
