<?php

namespace TerraReach;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class Client
{
    protected GuzzleClient $http;
    protected string $apiKey;
    protected string $mask;
    protected string $baseUrl = 'https://api.terrareach.com/api/v1/';

    /**
     * @param string $apiKey Your TerraReach API Key
     * @param string $mask Your approved Sender ID / Mask
     */
    public function __construct(string $apiKey, string $mask)
    {
        if (empty($mask)) {
            throw new InvalidArgumentException("TerraReach Error: A valid 'mask' (Sender ID) is required.");
        }

        $this->apiKey = $apiKey;
        $this->mask = $mask;
        
        $this->http = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ]
        ]);
    }

    /**
     * Send a single SMS
     */
    public function sendSms(string $phoneNumber, string $message, ?string $overrideMask = null)
    {
        return $this->request('POST', 'sms', [
            'apiKey'      => $this->apiKey,
            'mask'        => $overrideMask ?? $this->mask,
            'phoneNumber' => $phoneNumber,
            'message'     => $message,
        ]);
    }

    /**
     * Send Bulk SMS
     */
    public function sendBulkSms(array|string $phoneNumbers, string $message, ?string $overrideMask = null)
    {
        $numbersArray = is_array($phoneNumbers) ? $phoneNumbers : [$phoneNumbers];

        return $this->request('POST', 'sms/bulk', [
            'apiKey'       => $this->apiKey,
            'mask'         => $overrideMask ?? $this->mask,
            'phoneNumbers' => $numbersArray,
            'message'      => $message,
        ]);
    }

    /**
     * Get account statistics and balance
     */
    public function getStats()
    {
        return $this->request('GET', 'sms', [
            'apiKey' => $this->apiKey
        ]);
    }

    /**
     * Internal request handler
     */
    protected function request(string $method, string $endpoint, array $data = [])
    {
        try {
            $options = ($method === 'POST') ? ['json' => $data] : ['query' => $data];
            $response = $this->http->request($method, $endpoint, $options);
            
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return [
                'status'  => 'error',
                'message' => $e->getMessage(),
                'code'    => $e->getCode()
            ];
        }
    }
}