<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use JsonPath\JsonObject;

class EmailVerificationServer extends Model
{
    // set the table name
    protected $table = 'email_verification_servers';

    /**
     * Verify email address
     *
     * @return mixed
     */
    public function verify($email)
    {
        $config = $this->getConfig();
        $client = new Client();

        // build the uri
        $uri = $config['uri'];
        $uri = str_replace('{EMAIL}', $email, $uri);
        $uri = str_replace('{API_KEY}', $this->api_key, $uri);
        $uri = str_replace('{API_SECRET_KEY}', $this->api_secret_key, $uri);

        // actually request to the service
        $response = $client->request($config['request_type'], $uri);

        // obtain the result
        $raw = (string)$response->getBody();
        $jsonObject = new JsonObject($raw);

        // retrieve the result
        $result = $jsonObject->get($config['result_xpath'])[0];

        // map the result value to those of Acelle Mail
        $mapped = $config['result_map'][$result];

        return [$mapped, $raw];
    }

    /**
     * Find the configuration settings for a given verification service
     *
     * @return mixed
     */
    private function getConfig()
    {
        $configs = \Config::get('verification.services');
        foreach($configs as $config) {
            if ($config['id'] == $this->options) {
                return $config;
            }
        }

        throw new \Exception('Cannot find settings for verification service ' . $this->options);
    }
}
