<?php

declare(strict_types=1);

namespace App\DTO;

readonly class Webhook
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(public string $platform, public array $payload) {}

    public function getPlatform(): string
    {
        return strtolower($this->platform);
    }

    /**
     * @return array<string,mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
