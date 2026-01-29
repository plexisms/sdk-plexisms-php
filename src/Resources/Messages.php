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
            if (isset($params['to']))
                $payload['phone_number'] = $params['to'];
            if (isset($params['phone_number']))
                $payload['phone_number'] = $params['phone_number'];

            if (isset($params['body']))
                $payload['message'] = $params['body'];
            if (isset($params['message']))
                $payload['message'] = $params['message'];

            if (isset($params['senderId']))
                $payload['sender_id'] = $params['senderId'];
            if (isset($params['sender_id']))
                $payload['sender_id'] = $params['sender_id'];

            if (isset($params['smsType']))
                $payload['sms_type'] = $params['smsType'];
            if (isset($params['sms_type']))
                $payload['sms_type'] = $params['sms_type'];

            $response = $this->httpClient->post('api/sms/send/', [
                'json' => $payload
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->handleError($e);
            return [];
        }
    }

    /**
     * Send bulk SMS messages.
     *
     * @param array $phoneNumbers List of phone numbers
     * @param string $body Message content
     * @param string|null $senderId Sender ID (optional)
     * @param string $smsType 'transactional' or 'promotional'
     * @return array
     * @throws PlexismsException
     */
    public function createBulk(array $phoneNumbers, string $body, ?string $senderId = null, string $smsType = 'transactional')
    {
        try {
            $payload = [
                'phone_numbers' => $phoneNumbers,
                'message' => $body,
                'sms_type' => $smsType
            ];

            if ($senderId) {
                $payload['sender_id'] = $senderId;
            }

            $response = $this->httpClient->post('api/sms/send-bulk/', [
                'json' => $payload
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->handleError($e);
            return [];
        }
    }

    /**
     * Get status of a specific message.
     *
     * @param string|int $messageId
     * @return array
     * @throws PlexismsException
     */
    public function get($messageId)
    {
        try {
            $response = $this->httpClient->get("api/sms/{$messageId}/status/");
            return json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->handleError($e);
            return [];
        }
    }

    private function handleError($e)
    {
        $response = $e->getResponse();
        $body = $response ? $response->getBody()->getContents() : $e->getMessage();
        throw new PlexismsException("Message Error: " . $body, $e->getCode(), $e);
    }
}
