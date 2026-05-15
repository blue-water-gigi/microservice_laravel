<?php

declare(strict_types=1);

namespace App\Forwarders\Google;

use App\Clients\AudienceGridClient;
use App\Contracts\GoogleSubscriptionForwarder;
use App\DTO\Google\Subscription as GoogleSubscription;
use App\DTO\SubscriptionEventCategory;
use App\Exceptions\InvalidWebhookException;
use App\Mappers\Google\SubscriptionMapper;
use App\Validators\SubscriptionValidator;
use Illuminate\Http\Client\ConnectionException;

readonly class SubscriptionStartForwarder implements GoogleSubscriptionForwarder
{
    public function __construct(
        private SubscriptionValidator $subscriptionValidator,
        private AudienceGridClient $audienceGridClient
    ) {}

    public function supports(GoogleSubscription $googleSubscription): bool
    {
        return $googleSubscription->category === SubscriptionEventCategory::START->value;
    }

    /**
     * @throws InvalidWebhookException
     * @throws ConnectionException
     */
    public function forward(GoogleSubscription $googleSubscription): void
    {
        // map to audienceGridSubscription
        $audienceGridSubscription = new SubscriptionMapper()
            ->mapToAudienceGrid($googleSubscription);

        // validate the Subscription
        $this->subscriptionValidator->validate(
            $audienceGridSubscription,
            $audienceGridSubscription::rules()
        );

        // forward the data
        $this->audienceGridClient->post($audienceGridSubscription->toArray());
    }
}
