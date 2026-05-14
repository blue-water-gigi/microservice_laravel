<?php

declare(strict_types=1);

namespace App\Forwarders\Google;

use App\Contracts\GoogleSubscriptionForwarder;
use App\DTO\Google\Subscription as GoogleSubscription;
use App\DTO\SubscriptionEventCategory;
use App\Mappers\Google\SubscriptionMapper;

class SubscriptionStartForwarder implements GoogleSubscriptionForwarder
{
    public function supports(GoogleSubscription $googleSubscription): bool
    {
        return $googleSubscription->category === SubscriptionEventCategory::START->value;
    }

    public function forward(GoogleSubscription $googleSubscription): void
    {
        // map to audienceGridSubscription
        $audienceGridSubscription = (new SubscriptionMapper())->mapToAudienceGrid($googleSubscription);

        // validate the Subscription

        // forward the data
    }
}
