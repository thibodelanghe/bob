<?php

namespace App\Toggl\Clients;

use App\Interfaces\Client;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Toggl implements Client
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('toggl.base_url'))
            ->withBasicAuth(
                config('toggl.token'),
                'api_token'
            )
            ->acceptJson();
    }

    public function get(string $url, array $query = []): array
    {
        return $this->client->get($url, $query)
            ->throw()
            ->json();
    }

    public function post()
    {
        // TODO: Implement post() method.
    }
}
