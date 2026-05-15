<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\postJson;

it('processes subscription purchase notifications', function () {
    $payload = getPayload();

    Http::fake();

    $response = postJson('api/webhook', $payload, ['X-Webhook-Source' => 'Google']);

    Http::assertSentCount(1);

    Http::assertSent(function (Request $request): bool {
        $data = $request->data();

        return $data['event'] === 'subscription_started' &&
            $data['properties'] === [
                'subscription_id' => 'premium_monthly',
                'platform' => 'Google Android',
                'auto_renew_status' => true,
                'currency' => 'USD',
                'in_trial' => false,
                'product_name' => 'premium_monthly',
                'renewal_date' => '2024-02-10T12:24:50+00:00',
                'start_date' => '2024-01-06T19:04:50+00:00',
            ] &&
            $data['user'] === [
                'id' => 'USER-001',
                'email' => 'joe@example.com',
                'region' => 'US',
            ];
    });

    $response->assertStatus(204);
});
