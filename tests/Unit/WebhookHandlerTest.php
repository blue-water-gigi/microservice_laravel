<?php

use App\DTO\Webhook;
use App\Handlers\AppleWebhookHandler;
use App\Handlers\GoogleWebhookHandler;

test('supports', function (string $platform, bool $googleShouldHandle, bool $appleShouldHandle) {
    $googleHandler = new GoogleWebhookHandler;
    $appleHandler = new AppleWebhookHandler;

    $webhook = new Webhook($platform, ['data' => 'data']);

    // expect
    expect($googleHandler->supports($webhook))->toBe($googleShouldHandle)
        ->and($appleHandler->supports($webhook))->toBe($appleShouldHandle);

})->with([
    ['google', true, false],
    ['apple', false, true],
    ['unknown', false, false],
]);
