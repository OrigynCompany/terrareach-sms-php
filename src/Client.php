<?php

namespace TerraReach;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Official TerraReach PHP SDK
 * SMS for Developers and Marketers.
 */
class Client
{
    protected GuzzleClient $http;
    protected string $apiKey;
    protected ?string $defaultMask;
    protected string $baseUrl = 'https://api.terrareach.com/api/v1/';

    /**
     * @param string $apiKey Your TerraReach API Key
     * @param string|null $defaultMask Your approved Sender ID / Mask
     */
    public function __construct(string $apiKey, ?string $defaultMask = null)
    {
        $this->apiKey = $apiKey;
        $this->defaultMask = $defaultMask;
        
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
     * Endpoint: POST /sms
     */
    public function sendSms(string $phoneNumber, string $message, ?string $mask = null)
    {
        return $this->request('POST', 'sms', [
            'apiKey'      => $this->apiKey,
            'mask'        => $mask ?? $this->defaultMask,
            'phoneNumber' => $phoneNumber,
            'message'     => $message,
        ]);
    }

    /**
     * Send Bulk SMS
     * Endpoint: POST /sms/bulk
     */
    public function sendBulkSms(array|string $phoneNumbers, string $message, ?string $mask = null)
    {
        // Ensure phoneNumbers is always an array to match the API spec
        $numbersArray = is_array($phoneNumbers) ? $phoneNumbers : [$phoneNumbers];

        return $this->request('POST', 'sms/bulk', [
            'apiKey'       => $this->apiKey,
            'mask'         => $mask ?? $this->defaultMask,
            'phoneNumbers' => $numbersArray,
            'message'      => $message,
        ]);
    }

    /**
     * Get account statistics and balance
     * Endpoint: GET /sms
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
            // POST uses JSON body; GET uses query parameters (?apiKey=...)
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