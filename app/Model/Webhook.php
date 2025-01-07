<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class Webhook extends Model
{
    use HasFactory;
    use HasUid;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const REQUEST_METHOD_GET = 'get';
    public const REQUEST_METHOD_POST = 'post';
    public const REQUEST_METHOD_PUT = 'put';
    public const REQUEST_METHOD_DELETE = 'delete';

    public const REQUEST_AUTH_TYPE_BASIC_AUTH = 'basic_auth';
    public const REQUEST_AUTH_TYPE_BEARER_TOKEN = 'bearer_token';
    public const REQUEST_AUTH_TYPE_CUSTOM = 'custom';
    public const REQUEST_AUTH_TYPE_NO_AUTH = 'no_auth';

    public const REQUEST_BODY_TYPE_KEY_VALUE = 'key_value';
    public const REQUEST_BODY_TYPE_PLAIN = 'plain';

    public function fillAttributes($attributes)
    {
        $this->name = $attributes['name'];
        $this->setting_retry_times = $attributes['setting_retry_times'] ?? 0;
        $this->setting_retry_after_seconds = $attributes['setting_retry_after_seconds'] ?? 900;
        $this->request_method = $attributes['request_method'];
        $this->request_url = $attributes['request_url'];
        $this->request_auth_type = $attributes['request_auth_type'];
        $this->request_auth_bearer_token = $attributes['request_auth_bearer_token'];
        $this->request_auth_basic_username = $attributes['request_auth_basic_username'];
        $this->request_auth_basic_password = $attributes['request_auth_basic_password'];
        $this->request_auth_custom_key = $attributes['request_auth_custom_key'];
        $this->request_auth_custom_value = $attributes['request_auth_custom_value'];
        $this->request_headers = json_encode($attributes['request_headers']);
        $this->request_body_type = $attributes['request_body_type'];
        $this->request_body_params = json_encode($attributes['request_body_params']);
        $this->request_body_plain = $attributes['request_body_plain'];
    }


    public static function newDefault()
    {
        $webhook = new static();
        $webhook->status = static::STATUS_ACTIVE;

        $webhook->setting_retry_times = 2;
        $webhook->setting_retry_after_seconds = 900;
        $webhook->request_method = static::REQUEST_METHOD_GET;
        $webhook->request_auth_type = static::REQUEST_AUTH_TYPE_BASIC_AUTH;
        $webhook->request_body_type = static::REQUEST_BODY_TYPE_KEY_VALUE;

        return $webhook;
    }

    public static function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('webhooks.name', 'like', '%'.$keyword.'%')
                        ->orWhere('webhooks.content', 'like', '%'.$keyword.'%');
                });
            }
        }
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isInactive()
    {
        return $this->status == self::STATUS_INACTIVE;
    }

    public function disable()
    {
        $this->status = self::STATUS_INACTIVE;

        return $this->save();
    }

    public function enable()
    {
        $this->status = self::STATUS_ACTIVE;

        return $this->save();
    }

    public static function scopeActive($query)
    {
        $query->where('status', static::STATUS_ACTIVE);
    }

    public function saveWebhook($name, $event)
    {
        // fill
        $this->name = $name;
        $this->event = $event;

        $validator = \Validator::make($this->getAttributes(), [
            'name' => 'required',
            'event' => 'required',
        ]);

        if ($validator->fails()) {
            return [false, $validator->errors()];
        }

        $this->save();

        return [true, $validator->errors()];
    }

    public function getTags()
    {
        return [];
    }

    public function getRequestHeaders()
    {
        if (!$this->request_headers) {
            return [];
        }

        return json_decode($this->request_headers, true);
    }

    public function getRequestBodyParams()
    {
        if (!$this->request_body_params) {
            return [];
        }

        return json_decode($this->request_body_params, true);
    }

    public function test()
    {
        return $this->execute();
    }

    public function execute()
    {
        // Prepare cURL initialization
        $ch = curl_init();

        // Set the URL from the options
        curl_setopt($ch, CURLOPT_URL, $this->request_url);

        // Set the HTTP method (POST)
        $method = strtoupper($this->request_method);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Set the Authorization (Basic Auth)
        if ($this->request_auth_type === static::REQUEST_AUTH_TYPE_BASIC_AUTH) {
            $username = $this->request_auth_basic_username;
            $password = $this->request_auth_basic_password;
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        }

        // Set Headers
        $headers = [];
        if (!empty($this->getRequestHeaders())) {
            foreach ($this->getRequestHeaders() as $header) {
                $headers[] = $header['key'] . ': ' . $header['value'];
            }
        }

        // Add Content-Type based on body type
        if ($this->request_body_type === static::REQUEST_BODY_TYPE_KEY_VALUE) {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        } elseif ($this->request_body_type === static::REQUEST_BODY_TYPE_PLAIN) {
            // No special Content-Type needed for plain text
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Set the body type and parameters
        $body = '';
        if ($this->request_body_type === static::REQUEST_BODY_TYPE_PLAIN) {
            $body = $this->request_body_plain;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        } elseif ($this->request_body_type === static::REQUEST_BODY_TYPE_KEY_VALUE) {
            $bodyParams = [];
            foreach ($this->getRequestBodyParams() as $param) {
                $bodyParams[] = urlencode($param['key']) . '=' . urlencode($param['value']);
            }
            $body = implode('&', $bodyParams);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        // Set additional cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Execute the cURL request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // Get HTTP response code

        // Capture cURL errors
        $curlError = curl_errno($ch) ? curl_error($ch) : null;

        // Close the cURL session
        curl_close($ch);

        // Display all response aspects
        return [
            'method' => $method,
            'body' => $body,
            'error' => $curlError,
            'http_code' => $httpCode,
            'response' => $response,
        ];
    }

    public function getRequestArray()
    {
        return [
            'name' => $this->name,
            'setting_retry_times' => $this->setting_retry_times,
            'setting_retry_after_seconds' => $this->setting_retry_after_seconds,
            'request_method' => $this->request_method,
            'request_url' => $this->request_url,
            'request_auth_type' => $this->request_auth_type,
            'request_auth_bearer_token' => $this->request_auth_bearer_token,
            'request_auth_basic_username' => $this->request_auth_basic_username,
            'request_auth_basic_password' => $this->request_auth_basic_password,
            'request_auth_custom_key' => $this->request_auth_custom_key,
            'request_auth_custom_value' => $this->request_auth_custom_value,
            'request_headers' => $this->getRequestHeaders(),
            'request_body_type' => $this->request_body_type,
            'request_body_params' => $this->getRequestBodyParams(),
            'request_body_plain' => $this->request_body_plain,
        ];
    }
}
