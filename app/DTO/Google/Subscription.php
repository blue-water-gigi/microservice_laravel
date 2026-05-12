<?php

declare(strict_types=1);

namespace App\DTO\Google;

use Carbon\CarbonImmutable;

readonly class Subscription
{
    /**
     * Builder creational OOP pattern
     */
    public function __construct(
        public ?string $subscriptionId = null,       // data.subscription_notification.subscription_id
        public ?int $notificationType = null,        // data.subscription_notification.notification_type
        public ?bool $inTrial = null,                // data.subscription_notification.in_trial
        public ?CarbonImmutable $eventTime = null,   // Converted: data.event_time_millis
        public ?string $event = null,                // From subscription_events DB lookup
        public ?string $category = null,             // From subscription_events DB lookup
        public ?string $productId = null,            // data.developer_notification.product_id
        public ?string $orderId = null,              // data.developer_notification.order_id
        public ?string $userId = null,               // data.developer_notification.user_account_id
        public ?string $email = null,                // data.developer_notification.email
        public ?bool $autoRenewing = null,           // data.developer_notification.auto_renewing
        public ?CarbonImmutable $purchaseDate = null, // Converted: data.developer_notification.purchase_time_millis
        public ?CarbonImmutable $expiryDate = null,  // Converted: data.developer_notification.expiry_time_millis
        public ?string $currency = null,             // data.developer_notification.price_currency_code
        public ?string $region = null,              // data.developer_notification.region_code
    ) {}
}
