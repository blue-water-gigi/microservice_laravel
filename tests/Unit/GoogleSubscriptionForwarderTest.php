<?php

declare(strict_types=1);

use App\Contracts\GoogleSubscriptionForwarder;
use App\DTO\Google\SubscriptionBuilder;
use App\DTO\Webhook;
use App\Handlers\GoogleWebhookHandler;

it('gives true if it is a valid webhook', function () {
    $builder = Mockery::mock(SubscriptionBuilder::class);
    $forwarder = Mockery::mock(GoogleSubscriptionForwarder::class);
    $handler = new GoogleWebhookHandler($builder, [$forwarder]);
    $webhook = new Webhook('google', ['data' => []]);
    expect($handler->supports($webhook))->toBeTrue();
});

it('gives false if it is a invalid webhook', function () {
    $builder = Mockery::mock(SubscriptionBuilder::class);
    $forwarder = Mockery::mock(GoogleSubscriptionForwarder::class);
    $handler = new GoogleWebhookHandler($builder, [$forwarder]);
    $webhook = new Webhook('apple', ['data' => []]);
    expect($handler->supports($webhook))->toBeFalse();
});

it('processes webhook and forwards subscription to matching forwarders', function () {
    $builder = Mockery::mock(SubscriptionBuilder::class);
    $subscription = createSubscription(['category' => 'START']);

    $mockForwarder1 = Mockery::mock(GoogleSubscriptionForwarder::class);
    $mockForwarder2 = Mockery::mock(GoogleSubscriptionForwarder::class);

    $builder->shouldReceive('create')
        ->once()
        ->andReturn($subscription);

    $mockForwarder1->shouldReceive('supports')
        ->once()
        ->with($subscription)
        ->andReturn(true);

    $mockForwarder1->shouldReceive('forward')
        ->once()
        ->with($subscription);

    $mockForwarder2->shouldReceive('supports')
        ->once()
        ->with($subscription)
        ->andReturn(false); // This forwarder does not support the subscription

    $mockForwarder2->shouldNotReceive('forward');

    $handler = new GoogleWebhookHandler($builder, [$mockForwarder1, $mockForwarder2]);

    $webhook = new Webhook('google', ['data' => []]);

    $handler->handle($webhook);
    expect(true)->toBeTrue();
});
