<?php

/**
 * SendingServerMailgun class.
 *
 * Abstract class for Mailgun sending servers
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

use Acelle\Library\Log;
use Acelle\Library\StringHelper;
use SendGrid\Mail;
use SendGrid\Email;
use SendGrid\Content;

class SendingServerSendGrid extends SendingServer
{
    const WEBHOOK = 'mailgun';

    protected $table = 'sending_servers';
    public static $client = null;
    public static $isWebhookSetup = false;
    
    /**
     * Get authenticated to Mailgun and return the session object.
     *
     * @return mixed
     */
    public function client()
    {
        if (!self::$client) {
            self::$client = new \SendGrid($this->api_key);;
        }
        
        return self::$client;
    }
    
    /**
     * Setup webhooks for processing bounce and feedback loop.
     *
     * @return mixed
     */
    public function setupWebhooks()
    {
        if (self::$isWebhookSetup) {
            return true;
        }
        try {
            // ....
            self::$isWebhookSetup = true;
        } catch (\Exception $e) {
            // just ignore
        }
    }
    
    /**
     * Get Message Id
     * Extract the message id from SendGrid response
     *
     * @return String
     */
    public function getMessageId($headers) {
        preg_match('/(?<=X-Message-Id: ).*/', $headers, $matches);
        if (isset($matches[0])) {
            return $matches[0];
        } else {
            return NULL;
        }
    }
}
