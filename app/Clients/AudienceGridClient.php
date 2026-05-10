<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AudienceGridClient implements AudienceGridClientInterface
{
    private readonly string $apiUrl;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiUrl = config()->string('services.audiencegrid.api_url');
    }

    public function post(array $data): Response
    {
        return Http::post($this->apiUrl, $data);
    }
}
