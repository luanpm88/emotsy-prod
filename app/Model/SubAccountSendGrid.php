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

    public function sendingServerSendGrid()
    {
        return $this->belongsTo('Acelle\Model\SendingServerSendGrid', 'sending_server_id');
    }

    public function updateApiKey() {
        try {
            // create an API key
            $client = new \Acelle\Extra\SendGrid(['username' => $this->getSubAccountUsername(), 'password' => decrypt($this->password)]);
            $response = $client->createApiKey(['name' => 'Acelle Mail']);
            $this->api_key_id = $response['api_key_id'];
            $this->api_key = $response['api_key'];
            $this->save();
            LaravelLog::info("API key updated for SendGrid subuser {$this->getSubAccountUsername()}");
        } catch (\Exception $ex) {
            throw new \Exception('Cannot update API key. Error message from SendGrid: ' . $ex->getMessage());
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
            $client = new \Acelle\Extra\SendGrid(['api' => 'XSG.Mt2aSakBQfCBtGGirscWPA.J4Zla0WC6uPnZ-Tx4sjDxtLf4Y9TD6P1fkNT4_6iKzk']);

            if ($client->subUserExists($this->getSubAccountUsername())) {
                LaravelLog::info("SendGrid subuser {$this->getSubAccountUsername()} already exists");
                return;
            }

            LaravelLog::info("Trying to create SendGrid subuser {$this->getSubAccountUsername()}");
            $response = $client->createSubUser([
                "username" => $this->getSubAccountUsername(),
                "email" => $this->email,
                "password" => decrypt($this->password),
            ]);
            LaravelLog::info("SendGrid subuser {$this->getSubAccountUsername()} created");
        } catch (\Exception $ex) {
            throw new \Exception('Cannot create SendGrid subuser. Error message from SendGrid: ' .  $ex->getMessage());
        }
    }

    public static function setup($params)
    {
        $instance = self::findByKeySet($params['customer_id'], $params['sending_server_id']);

        if (is_null($instance)) {
            $instance = new self();
            $instance->email = $params['email'];
            $instance->subscription_id = $params['subscription_id'];
            $instance->customer_id = $params['customer_id'];
            $instance->sending_server_id = $params['sending_server_id'];
            $instance->password = encrypt(uniqid());
            $instance->username = $instance->getSubAccountUsername();
            $instance->save();
        }

        $instance->createSubAccount(); // if one does not exist yet, otherwise, just return ok
        $instance->updateApiKey(); // just create a new API KEY for new subscription, @todo: is this a problem?

        return $instance;
    }

    public function getSubAccountUsername()
    {
        // return a unique username for subaccount
        return "acellemail_" . substr(md5("{$this->sending_server_id}{$this->customer_id}"), 0, 8);
    }

    public static function findByKeySet($customer_id, $sending_server_id)
    {
        return self::where('customer_id', $customer_id)->where('sending_server_id', $sending_server_id)->first();
    }
}
