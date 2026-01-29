<?php

namespace Plexisms\Resources;

use GuzzleHttp\Client;
use Plexisms\Exceptions\PlexismsException;

class Account
{
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get the current account balance.
     *
     * @return array
     * @throws PlexismsException
     */
    public function balance()
    {
        try {
            $response = $this->httpClient->get('api/sms/balance/');
            return json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            $body = $response ? $response->getBody()->getContents() : $e->getMessage();
            throw new PlexismsException("Account Error: " . $body, $e->getCode(), $e);
        }
    }
}
