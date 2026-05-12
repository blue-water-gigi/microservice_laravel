<?php

declare(strict_types=1);

namespace App\DTO\Google;

use App\DTO\Webhook;
use App\Models\SubscriptionEvent;
use Carbon\CarbonImmutable;

class SubscriptionBuilder
{
    /**
     * Create a Subscription DTO from a Google Webhook payload.
     */
    public function create(Webhook $webhook): Subscription
    {
        /**
         * @var array{
         *     data: array{
         *         subscription_notification: array{
         *             subscription_id: string,
         *             notification_type: int,
         *             in_trial: bool,
         *         },
         *         developer_notification: array{
         *             product_id: string,
         *             order_id: string,
         *             user_account_id: string,
         *             email: string,
         *             auto_renewing: bool,
         *             purchase_time_millis: int|string,
         *             expiry_time_millis: int|string,
         *             price_currency_code: string,
         *             region_code: string,
         *         },
         *         event_time_millis: int|string,
         *     },
         * } $data
         */
        $data = $webhook->getPayload();

        // extract necessary fields from webhook
        $subscriptionNotification = $data['data']['subscription_notification'];
        $developer_notification = $data['data']['developer_notification'];

        // Fetch some data from DB
        $subEvent = SubscriptionEvent::query()
            ->where('notification_type', '=', $subscriptionNotification['notification_type'])
            ->where('in_trial', '=', $subscriptionNotification['in_trial'])
            ->firstOrFail();

        // return a Subscription DTO
        return new Subscription(
            subscriptionId: $subscriptionNotification['subscription_id'],
            notificationType: $subscriptionNotification['notification_type'],
            inTrial: $subscriptionNotification['in_trial'],
            eventTime: CarbonImmutable::createFromTimestampMs($data['data']['event_time_millis']),
            event: $subEvent->name,
            category: $subEvent->category->value,
            productId: $developer_notification['product_id'],
            orderId: $developer_notification['order_id'],
            userId: $developer_notification['user_account_id'],
            email: $developer_notification['email'],
            autoRenewing: $developer_notification['auto_renewing'],
            purchaseDate: CarbonImmutable::createFromTimestampMs($developer_notification['purchase_time_millis']),
            expiryDate: CarbonImmutable::createFromTimestampMs($developer_notification['expiry_time_millis']),
            currency: $developer_notification['price_currency_code'],
            region: $developer_notification['region_code']
        );
    }
}
