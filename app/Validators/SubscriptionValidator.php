<?php

declare(strict_types=1);

namespace App\Validators;

use App\Exceptions\InvalidWebhookException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\Factory;

readonly class SubscriptionValidator
{
    /**
     * Create a new class instance.
     */
    public function __construct(private Factory $validator) {}

    /**
     * @param  array<string, array<string>>  $rules
     * @param  Arrayable<string, mixed>  $subscription
     *
     * @throws InvalidWebhookException
     */
    public function validate(Arrayable $subscription, array $rules): void
    {
        $validator = $this->validator->make($subscription->toArray(), $rules);

        if ($validator->fails()) {
            throw new InvalidWebhookException('Validation failed: '.$validator->errors());
        }
    }
}
