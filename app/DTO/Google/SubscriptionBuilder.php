<?php

declare(strict_types=1);

namespace App\DTO\Google;

use App\DTO\Webhook;
use App\Exceptions\InvalidWebhookException;
use App\Repositories\SubscriptionEventRepository;
use Carbon\CarbonImmutable;
use Throwable;

readonly class SubscriptionBuilder
{
    public function __construct(private SubscriptionEventRepository $subscriptionEventRepository) {}

    /**
     * Create a Subscription DTO from a Google Webhook payload.
     *
     * @throws InvalidWebhookException
     */
    public function create(Webhook $webhook): Subscription
    {
        try {
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
            $subEvent = $this->subscriptionEventRepository
                ->findByNotificationType(
                    $subscriptionNotification['notification_type'],
                    $subscriptionNotification['in_trial']
                );

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
        } catch (Throwable $e) {
            throw new InvalidWebhookException(
                'Unable to create Google subscription: '.$e->getMessage(),
                $e->getCode(),
                $e);
        }
    }
}
