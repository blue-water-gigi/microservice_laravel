<?php

declare(strict_types=1);

namespace App\Forwarders\Google;

use App\Contracts\GoogleSubscriptionForwarder;
use App\DTO\Google\Subscription;
use App\DTO\SubscriptionEventCategory;

class SubscriptionRenewForwarder implements GoogleSubscriptionForwarder
{
    public function supports(Subscription $subscription): bool
    {
        return $subscription->category === SubscriptionEventCategory::RENEW->value;
    }

    public function forward(Subscription $subscription): void
    {
        // TODO: Implement forward() method.
    }
}
