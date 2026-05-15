<?php

declare(strict_types=1);

namespace App\Mappers\Google;

use App\DTO\AudienceGrid\Subscription as AudienceGridSubscription;
use App\DTO\Google\Subscription as GoogleSubscription;
use App\Exceptions\InvalidWebhookException;
use Throwable;

class SubscriptionMapper
{
    /**
     * @throws InvalidWebhookException
     */
    public function mapToAudienceGrid(GoogleSubscription $googleSubscription): AudienceGridSubscription
    {
        $audienceGridSubscription = new AudienceGridSubscription;

        try {
            $audienceGridSubscription->setEvent($googleSubscription->event);
            $audienceGridSubscription->setSubscriptionId($googleSubscription->subscriptionId);
            $audienceGridSubscription->setPlatform('Google Android');
            $audienceGridSubscription->setAutoRenewStatus($googleSubscription->autoRenewing);
            $audienceGridSubscription->setCurrency($googleSubscription->currency);
            $audienceGridSubscription->setInTrial($googleSubscription->inTrial);
            $audienceGridSubscription->setProductName($googleSubscription->productId);
            $audienceGridSubscription->setRenewalDate($googleSubscription->expiryDate);
            $audienceGridSubscription->setStartDate($googleSubscription->purchaseDate);
            $audienceGridSubscription->setUserId($googleSubscription->userId);
            $audienceGridSubscription->setEmail($googleSubscription->email);
            $audienceGridSubscription->setRegion($googleSubscription->region);

            return $audienceGridSubscription;
        } catch (Throwable $th) {
            throw new InvalidWebhookException(
                'Mapping failed: '.$th->getMessage(), $th->getCode(), $th
            );
        }
    }
}
