<?php

use App\Exceptions\InvalidWebhookException;
use App\Validators\SubscriptionValidator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

beforeEach(function () {
    $translator = new Translator(new ArrayLoader, 'en');
    $this->factory = new Factory($translator);
});

it('validates subscription successfully with dynamic rules', function () {
    $subscription = new class implements Arrayable
    {
        public function toArray(): array
        {
            return [
                'event' => 'subscription_started',
                'properties' => [
                    'subscription_id' => 'premium_monthly',
                    'platform' => 'Google Android',
                    'auto_renew_status' => true,
                    'currency' => 'USD',
                    'in_trial' => false,
                    'product_name' => 'premium_monthly',
                    'renewal_date' => '2024-02-10T12:24:50+00:00',
                    'start_date' => '2024-01-06T19:04:50+00:00',
                ],
                'user' => [
                    'id' => 'USER-001',
                    'email' => 'joe@example.com',
                    'region' => 'US',
                ],
            ];
        }

        public static function rules(): array
        {
            return [
                'event' => ['required', 'string'],
                'properties.subscription_id' => ['required', 'string'],
                'properties.platform' => ['required', 'string'],
                'properties.auto_renew_status' => ['required', 'boolean'],
                'properties.currency' => ['required', 'string'],
                'properties.in_trial' => ['required', 'boolean'],
                'properties.product_name' => ['required', 'string'],
                'properties.renewal_date' => ['required', 'date', 'after_or_equal:properties.start_date'],
                'properties.start_date' => ['required', 'date'],
                'user.id' => ['required', 'string'],
                'user.email' => ['nullable', 'email'],
                'user.region' => ['nullable', 'string', 'size:2'],
            ];
        }
    };

    $validator = new SubscriptionValidator($this->factory);

    /**
     * @throws InvalidWebhookException
     */
    $validator->validate($subscription, $subscription::rules());
})->throwsNoExceptions();

it('throws an exception on validation failure with dynamic rules', function () {
    $subscription = new class implements Arrayable
    {
        public function toArray(): array
        {
            return [
                'event' => 'subscription_started',
                'properties' => [
                    'subscription_id' => 2,
                    'platform' => 'Google Android',
                    'auto_renew_status' => true,
                    'currency' => 'USD',
                    'in_trial' => 0,
                    'product_name' => 'premium_monthly',
                    'renewal_date' => '2024-02-10T12:24:50+00:00',
                    'start_date' => '2024-01-06T19:04:50+00:00',
                ],
                'user' => [
                    'id' => 'USER-001',
                    'email' => 'joe@example.com',
                    'region' => 'US',
                ],
            ];
        }

        public static function rules(): array
        {
            return [
                'event' => ['required', 'string'],
                'properties.subscription_id' => ['required', 'string'],
                'properties.platform' => ['required', 'string'],
                'properties.auto_renew_status' => ['required', 'boolean'],
                'properties.currency' => ['required', 'string'],
                'properties.in_trial' => ['required', 'boolean'],
                'properties.product_name' => ['required', 'string'],
                'properties.renewal_date' => ['required', 'date', 'after_or_equal:properties.start_date'],
                'properties.start_date' => ['required', 'date'],
                'user.id' => ['required', 'string'],
                'user.email' => ['nullable', 'email'],
                'user.region' => ['nullable', 'string', 'size:2'],
            ];
        }
    };

    $validator = new SubscriptionValidator($this->factory);

    expect(/**
     * @throws InvalidWebhookException
     */ fn () => $validator
        ->validate(
            $subscription,
            $subscription::rules()))
        ->toThrow(
            InvalidWebhookException::class,
            'Validation failed: '
        );
});
