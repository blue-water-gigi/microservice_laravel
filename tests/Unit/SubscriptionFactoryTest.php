<?php

declare(strict_types=1);

use App\DTO\Google\Subscription;
use App\DTO\Google\SubscriptionBuilder;
use App\DTO\Webhook;
use App\Exceptions\InvalidWebhookException;
use App\Models\SubscriptionEvent;
use App\Repositories\SubscriptionEventRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it(/**
 * @throws InvalidWebhookException
 */ 'creates a subscription DTO successfully', closure: function () {
    // create a subscriptionEvent
    $subEvent = new SubscriptionEvent([
        'name' => 'subscription_started',
        'category' => 'START',
        'notification_type' => 4,
        'in_trial' => false,
    ]);

    // mock the SubscriptionEventRepository to return event
    $mockRepo = Mockery::mock(SubscriptionEventRepository::class);

    // instantiate SubscriptionBuilder and inject dependency
    $subBuilder = new SubscriptionBuilder($mockRepo);

    // create a Webhook
    $webhook = new Webhook('google', [
        'data' => [
            'version' => '1.0',
            'package_name' => 'com.example.premium',
            'event_time_millis' => '1704567890123',
            'subscription_notification' => [
                'notification_type' => 4,
                'purchase_token' => 'abcd1234-5678-efgh-9101-ijklmnopqrst',
                'subscription_id' => 'premium_monthly',
                'in_trial' => false,
            ],
            'developer_notification' => [
                'order_id' => 'GPA.1234-5678-9012-34567',
                'product_id' => 'premium_monthly',
                'user_account_id' => 'USER-001',
                'email' => 'joe@example.com',
                'acknowledgement_state' => 1,
                'auto_renewing' => true,
                'purchase_state' => 0,
                'purchase_time_millis' => '1704567890123',
                'expiry_time_millis' => '1707567890123',
                'price_amount_micros' => '4990000',
                'price_currency_code' => 'USD',
                'region_code' => 'US',
            ],
        ],
        'message_id' => '1234567890123456',
        'publish_time' => '2024-01-05T12:00:00.000Z',
    ]);

    $mockRepo->shouldReceive('findByNotificationType')
        ->with(4, false)
        ->once()
        ->andReturn($subEvent);

    // create a subscription
    $subscription = $subBuilder->create($webhook);

    // expect
    expect($subscription)->toBeInstanceOf(Subscription::class)
        ->and($subscription->event)->toBe('subscription_started')
        ->and($subscription->category)->toBe('START');
});

it(/**
 * @throws InvalidWebhookException
 */  'throws InvalidWebhookException when event is not found', function () {
    // mock the SubscriptionEventRepository to return event
    $mockRepo = Mockery::mock(SubscriptionEventRepository::class);

    // instantiate SubscriptionBuilder and inject dependency
    $subBuilder = new SubscriptionBuilder($mockRepo);

    // create a Webhook
    $webhook = new Webhook('google', [
        'data' => [
            'version' => '1.0',
            'package_name' => 'com.example.premium',
            'event_time_millis' => '1704567890123',
            'subscription_notification' => [
                'notification_type' => 999,
                'purchase_token' => 'abcd1234-5678-efgh-9101-ijklmnopqrst',
                'subscription_id' => 'premium_monthly',
                'in_trial' => false,
            ],
            'developer_notification' => [
                'order_id' => 'GPA.1234-5678-9012-34567',
                'product_id' => 'premium_monthly',
                'user_account_id' => 'USER-001',
                'email' => 'joe@example.com',
                'acknowledgement_state' => 1,
                'auto_renewing' => true,
                'purchase_state' => 0,
                'purchase_time_millis' => '1704567890123',
                'expiry_time_millis' => '1707567890123',
                'price_amount_micros' => '4990000',
                'price_currency_code' => 'USD',
                'region_code' => 'US',
            ],
        ],
        'message_id' => '1234567890123456',
        'publish_time' => '2024-01-05T12:00:00.000Z',
    ]);

    $mockRepo->shouldReceive('findByNotificationType')
        ->with(999, false)
        ->once()
        ->andThrow(ModelNotFoundException::class);

    // expect
    expect(/**
     * @throws InvalidWebhookException
     */ fn () => $subBuilder->create($webhook))
        ->toThrow(
            InvalidWebhookException::class,
            'Unable to create Google subscription'
        );
});
