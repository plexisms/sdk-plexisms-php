<?php

namespace Plexisms\Resources;

use GuzzleHttp\Client;
use Plexisms\Exceptions\PlexismsException;

class Messages
{
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Send a new SMS message.
     *
     * @param array $params
     * @return array
     * @throws PlexismsException
     */
    public function create(array $params)
    {
        try {
            // Map convenient keys to API expected keys if necessary, 
            // but assuming API expects snake_case based on other SDKs:
            // phone_number, message, sender_id, sms_type
            
            // Allow camelCase input and convert to snake_case for consistency with JS/Dart SDKs if desired,
            // or just stick to array keys matching API. Let's support mapped keys for better DX:
            
            $payload = [];
            if (isset($params['to'])) $payload['phone_number'] = $params['to'];
            if (isset($params['phone_number'])) $payload['phone_number'] = $params['phone_number'];

            if (isset($params['body'])) $payload['message'] = $params['body'];
            if (isset($params['message'])) $payload['message'] = $params['message'];
            
            if (isset($params['senderId'])) $payload['sender_id'] = $params['senderId'];
            if (isset($params['sender_id'])) $payload['sender_id'] = $params['sender_id'];

            if (isset($params['smsType'])) $payload['sms_type'] = $params['smsType'];
            if (isset($params['sms_type'])) $payload['sms_type'] = $params['sms_type'];

            $response = $this->httpClient->post('sms/send/', [
                'json' => $payload
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            $body = $response ? $response->getBody()->getContents() : $e->getMessage();
            throw new PlexismsException("Failed to send message: " . $body, $e->getCode(), $e);
        }
    }
}
