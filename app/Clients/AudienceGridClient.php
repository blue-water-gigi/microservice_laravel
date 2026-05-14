<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class AudienceGridClient implements AudienceGridClientInterface
{
    private string $apiUrl;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiUrl = config()->string('services.audiencegrid.api_url');
    }

    /**
     * @throws ConnectionException
     */
    public function post(array $data): Response
    {
        return Http::post($this->apiUrl, $data);
    }
}
