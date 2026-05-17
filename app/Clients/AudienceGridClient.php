<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
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
     * @throws RequestException
     */
    public function post(array $data): Response
    {
        $request = Http::post($this->apiUrl, $data);

        $request->throw();

       logger()->info('Sending to AudienceGrid', [
           'url' => $this->apiUrl,
           'data' => $data,
       ]);

        return $request;
    }
}
