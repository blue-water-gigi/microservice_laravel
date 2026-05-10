<?php

declare(strict_types=1);

use function Pest\Laravel\getJson;

test('healthcheck endpoint works', function () {
    getJson('/api/healthcheck')
        ->assertStatus(200)
        ->assertJson(['status' => 'ok'], true);
});
