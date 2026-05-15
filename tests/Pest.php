<?php

declare(strict_types=1);

use App\DTO\Google\Subscription;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/
pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature')
    ->beforeEach(fn () => $this->seed());
pest()->in('Unit');
/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/
expect()->extend('toBeOne', fn () => $this->toBe(1));
/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/
function createSubscription(array $overrides = []): Subscription
{
    $defaults = [
        'subscription_id' => 'premium_monthly',
        'notification_type' => 4,
        'in_trial' => false,
        'event_time' => CarbonImmutable::now(),
        'event' => 'subscription_started',
        'category' => 'START',
        'product_id' => 'premium_monthly',
        'order_id' => 'GPA.1234-5678-9012-34567',
        'user_id' => 'USER-001',
        'email' => 'joe@example.com',
        'auto_renewing' => true,
        'purchase_date' => CarbonImmutable::now(),
        'expiry_date' => CarbonImmutable::now()->addMonth(),
        'currency' => 'USD',
        'region' => 'US',
    ];

    $data = array_merge($defaults, $overrides);

    return new Subscription(
        subscriptionId: $data['subscription_id'],
        notificationType: $data['notification_type'],
        inTrial: $data['in_trial'],
        eventTime: $data['event_time'],
        event: $data['event'],
        category: $data['category'],
        productId: $data['product_id'],
        orderId: $data['order_id'],
        userId: $data['user_id'],
        email: $data['email'],
        autoRenewing: $data['auto_renewing'],
        purchaseDate: $data['purchase_date'],
        expiryDate: $data['expiry_date'],
        currency: $data['currency'],
        region: $data['region']
    );
}

/**
 * Generate a Google Play subscription notification payload for testing.
 *
 * @param  int  $notificationType  The notification type (4 = subscription purchased)
 * @param  bool  $inTrial  Whether the subscription is in trial period
 * @param  bool  $autoRenewing  Whether auto-renewal is enabled
 * @return array<string, mixed> The webhook payload structure
 */
function getPayload(int $notificationType = 4, bool $inTrial = false, bool $autoRenewing = true): array
{
    return [
        'data' => [
            'version' => '1.0',
            'package_name' => 'com.example.premium',
            'event_time_millis' => '1704567890123',
            'subscription_notification' => [
                'notification_type' => $notificationType,
                'purchase_token' => 'abcd1234-5678-efgh-9101-ijklmnopqrst',
                'subscription_id' => 'premium_monthly',
                'in_trial' => $inTrial,
            ],
            'developer_notification' => [
                'order_id' => 'GPA.1234-5678-9012-34567',
                'product_id' => 'premium_monthly',
                'user_account_id' => 'USER-001',
                'email' => 'joe@example.com',
                'acknowledgement_state' => 1,
                'auto_renewing' => $autoRenewing,
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
    ];
}
