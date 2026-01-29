<?php

namespace Plexisms\Resources;

use GuzzleHttp\Client;
use Plexisms\Exceptions\PlexismsException;

class OTP
{
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Send an OTP code to a phone number.
     *
     * @param string $to Recipient phone number
     * @param string $brand Brand name (optional, default: PlexiSMS)
     * @return array
     * @throws PlexismsException
     */
    public function send(string $to, string $brand = "PlexiSMS")
    {
        try {
            $response = $this->httpClient->post('api/sms/send-otp/', [
                'json' => [
                    'phone_number' => $to,
                    'brand' => $brand
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->handleError($e);
            return [];
        }
    }

    /**
     * Verify an OTP code.
     *
     * @param string $verificationId Verification ID received from send()
     * @param string $code The code the user entered
     * @return array
     * @throws PlexismsException
     */
    public function verify(string $verificationId, string $code)
    {
        try {
            $response = $this->httpClient->post('api/sms/verify-otp/', [
                'json' => [
                    'verification_id' => $verificationId,
                    'otp_code' => $code
                ]
            ]);

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
        throw new PlexismsException("OTP Error: " . $body, $e->getCode(), $e);
    }
}
