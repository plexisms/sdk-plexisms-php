<?php

namespace Plexisms;

use GuzzleHttp\Client as GuzzleClient;
use Plexisms\Resources\Messages;

class Client
{
    private $apiKey;
    private $httpClient;
    public $messages;

    /**
     * Create a new PlexiSMS Client instance.
     *
     * @param string $apiKey Your API Key
     * @param array $options Guzzle options (optional)
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;

        $baseUri = 'https://server.plexisms.com';

        $config = array_merge([
            'base_uri' => $baseUri,
            'headers' => [
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'plexisms-php/1.0.0'
            ]
        ], $options);

        $this->httpClient = new GuzzleClient($config);

        // Initialize Resources
        $this->messages = new Messages($this->httpClient);
    }
}
