<?php

namespace Acelle\Library;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;
use Exception;

class VerifiedEmailsClient
{
    protected $apiToken;
    protected $client;
    protected $enpoint;

    const DEFAULT_ENPOINT = 'https://verifiedemails.io/api/v';

    // https://verifiedemails.io/api/v?user=iamanantgupta&api_token=6fb6f064a046350710e04617&verify=iamanantgupta@gmail.com,anant@growmeorganic.com

    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
        $this->enpoint = static::DEFAULT_ENPOINT;
    }

    private function getClient()
    {
        if (!$this->client) {
            $this->client = new GuzzleClient([
                'base_uri' => $this->enpoint,
                'verify' => false,
                'headers' => [],
            ]);
        }

        return $this->client;
    }

    private function makeRequest($method, $uri, $params = [])
    {
        $client = $this->getClient();

        // API key
        $params = array_merge($params, [
            'user' => 'iamanantgupta',
            // 'api_token' => '6fb6f064a046350710e04617', // $this->apiToken,
            'api_token' => $this->apiToken,
        ]);

        //
        try {
            $options = [
                // 'headers' => [
                //     'Authorization' => "Bearer {$this->apiKey}",
                //     'Accept' => 'application/json',
                // ],
            ];

            // If the method is POST, add form_params
            if (strtoupper($method) == 'POST') {
                $options['form_params'] = $params;
            } else if (strtoupper($method) == 'GET') {
                $options['query'] = $params;
            }

            $response = $client->request($method, $uri, $options);

            // Get the status code of the response
            $statusCode = $response->getStatusCode();

            // Get the response body
            $body = $response->getBody();

            // Decode the JSON response if necessary
            $data = json_decode($body, true);

            // Output the data
            return [$statusCode, $data];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // // Handle client exceptions
            // echo 'ClientException: ' . $e->getMessage() . "\n";
            // echo 'Response: ' . $e->getResponse()->getBody()->getContents() . "\n";
            // echo 'Stack Trace: ' . $e->getTraceAsString() . "\n";

            // return $e->getResponse();

            throw new \Exception($e->getResponse()->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // // Handle request exceptions
            // echo 'RequestException: ' . $e->getMessage() . "\n";
            // if ($e->hasResponse()) {
            //     echo 'Response: ' . $e->getResponse()->getBody()->getContents() . "\n";
            // }
            // echo 'Stack Trace: ' . $e->getTraceAsString() . "\n";

            // return $e->getResponse();
            throw new \Exception($e->getResponse()->getBody()->getContents());
            
        } catch (Exception $e) {
            // // Handle all other exceptions
            // echo 'Exception: ' . $e->getMessage() . "\n";
            // echo 'Stack Trace: ' . $e->getTraceAsString() . "\n";

            throw new \Exception($e->getMessage());
        }
    }

    public function verify($email)
    {
        list($statusCode, $data) = $this->makeRequest('GET', '', [
            'verify' => $email,
        ]);

        if (!isset($data) || !isset($data[0]) || !isset($data[0]['verify'])) {
            throw new \Exception("Something went wrong. Reponse: " . json_encode($data));
        }

        return $data[0]["response"];
    }

    public function batchVerify($emails)
    {
        list($statusCode, $data) = $this->makeRequest('GET', '', [
            'verify' => implode(',', $emails),
        ]);

        if (!isset($data) || !isset($data[0]) || !isset($data[0]['verify'])) {
            throw new \Exception("Something went wrong. Reponse: " . json_encode($data));
        }

        //
        $batchId = uniqid();

        //
        Cache::forever($batchId, $data);

        return [
            'batch_id' => $batchId,
        ];
    }

    public function getBatchStatus($batchId)
    {
        return [
            'status' => 'done',
        ];
    }

    public function getBatchResult($batchId)
    {
        $data = Cache::get($batchId);

        return array_map(function ($item) {
            return [
                'email' => $item['verify'],
                'status' => $item["response"]['safetosend'],
                'response' => $item,
            ];
        }, $data);
    }

    public function getCredits()
    {
        return 1000;
    }






    // public function testApi()
    // {
    //     list($statusCode, $data) = $this->makeRequest('GET', 'test', []);

    //     return $statusCode;
    // }

    // public function batchVerify(array $emails)
    // {
    //     list($statusCode, $data) = $this->makeRequest('POST', 'batch-verify', [
    //         'emails' => $emails,
    //     ]);

    //     return $data;
    // }
    
    // public function getBatchStatus($batchId)
    // {
    //     list($statusCode, $data) = $this->makeRequest('POST', 'batch-status', [
    //         'batch_id' => $batchId,
    //     ]);

    //     return $data;
    // }

    

    // public function getCredits()
    // {
    //     list($statusCode, $data) = $this->makeRequest('GET', 'get-credits');

    //     return $data;
    // }
}
