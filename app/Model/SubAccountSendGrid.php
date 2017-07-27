<?php

/**
 * SubAccountSendGrid class.
 *
 * Model class for SubUserSendGrid
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log as LaravelLog;
use DB;

class SubAccountSendGrid extends SubAccount
{
    protected $table = 'sub_accounts';

    const DEFAULT_API_NAME = 'Acelle Mail API Key';

    public function sendingServerSendGrid()
    {
        return $this->belongsTo('Acelle\Model\SendingServerSendGrid', 'sending_server_id');
    }

    public function updateApiKey() {
        try {
            // create an API key
            $client = new \Acelle\Extra\SendGrid($this->getSubAccountUsername(), decrypt($this->password));

            $response = $client->api_keys()->request('POST', [
                'name' => self::DEFAULT_API_NAME,
                'scopes' => [
                    'user.webhooks.event.settings.read',
                    'user.webhooks.event.settings.update',
                    'user.webhooks.parse.settings.create',
                    'user.webhooks.parse.settings.delete',
                    'user.webhooks.parse.settings.read',
                    'user.webhooks.parse.settings.update',
                    'user.webhooks.parse.stats.read',
                    'mail.batch.create',
                    'mail.batch.delete',
                    'mail.batch.read',
                    'mail.batch.update',
                    'mail.send',
                ]
            ]);

            $response_json = json_decode($response->getBody()->read(1024 * 1024), true);
            $this->api_key_id = $response_json['api_key_id'];
            $this->api_key = $response_json['api_key'];
            $this->save();

        } catch (\Exception $ex) {
            throw new \Exception('Cannot update API Key. Original message: ' . $ex->getMessage());
        }
    }

    /**
     * Overwrite the delete() method to also clear the pending jobs
     *
     */
    public function delete()
    {
        $this->deleteSubUser();
        parent::delete();
    }

    /**
     * Overwrite the delete() method to also clear the pending jobs
     *
     */
    public function deleteSubAccount() {
        try {
            $client = new \Acelle\Extra\SendGrid(\Config::get('sendgrid.username'), \Config::get('sendgrid.password'));
            $client->subusers()->delete($this->getSubAccountUsername());
        } catch (\Exception $ex) {
            // just suppress the error
        }
    }

    /**
     * Overwrite the delete() method to also clear the pending jobs
     *
     */
    public function createSubAccount() {
        try {
            LaravelLog::info("Trying to create SendGrid subuser {$this->getSubAccountUsername()}");
            $client = new \Acelle\Extra\SendGrid('lifehappens', 'life@1655fdn');

            $ips = json_decode($client->ips()->request('GET')->getBody()->read(1024 * 1024), true);
            $ips = array_map(function($ip) {
                return $ip['ip'];
            }, $ips);
            $response = $client->subusers()->request('POST', [
                "username" => $this->getSubAccountUsername(),
                "email" => $this->email,
                "password" => decrypt($this->password),
                "ips" => $ips
            ]);
            LaravelLog::info("SendGrid subuser {$this->getSubAccountUsername()} created");
        } catch (\Exception $ex) {
            throw new \Exception('Cannot create SendGrid subuser. Original message: ' .  $ex->getMessage());
        }
    }

    public static function setup($params)
    {
        $instance = new self();
        $instance->email = $params['email'];
        $instance->subscription_id = $params['subscription_id'];
        $instance->customer_id = $params['customer_id'];
        $instance->sending_server_id = $params['sending_server_id'];
        $instance->password = encrypt(uniqid());
        $instance->username = $instance->getSubAccountUsername();
        $instance->save();

        $instance->createSubAccount();
        $instance->updateApiKey();
    }

    public function getSubAccountUsername()
    {
        // return a unique username for subaccount
        return "acellemail_" . substr(md5("{$this->sending_server_id}{$this->customer_id}"), 0, 8);
    }
}
