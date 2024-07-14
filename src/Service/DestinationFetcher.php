<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DestinationFetcher
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchAll(string $path): array
    {
        $response = $this->httpClient->request('GET', $path);
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new \Exception('Failed to fetch data from the API');
        }

        return $response->toArray();
    }
}
