<?php

/**
 * SendingServerSparkPost class.
 *
 * Abstract class for SparkPost sending server
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
 *
 * @description: This is the Skeleton for developing a sending server which is used to send email through a 3rd service
 * the expected usage is:
 *
 *     $server = new SendingServerSparkPostApi()
 *     $server->sendMessage( $message );
 */

namespace Acelle\Model;

use Acelle\Library\Log as MailLog;
use Postal\Client;
use Postal\SendRawMessage;
use Exception;

class SendingServerPostal extends SendingServer
{
    protected $table = 'sending_servers';
    public static $client = null;
 
    public static function handle($request)
    {
        MailLog::info('Notification received!');
        $json = $request->json()->all();

        MailLog::info(json_encode($json));

        if (array_key_exists('bounce', $json)) {
            self::handleBounce($json);
        } else {
            MailLog::warning('Not a bounce notification');            
        }
    }

    public static function handleBounce(Array $json)
    {
        $bounceLog = new BounceLog();

        // use Elastic Email transaction id as runtime-message-id
        try {
            $bounceLog->runtime_message_id = $json['bounce']['message_id'];
        } catch (\Exception $e) {
            MailLog::warning('Cannot retrive runtime_message_id');
            throw new Exception('Cannot retrive message_id in bounce notification');
        }

        $trackingLog = TrackingLog::where('runtime_message_id', $bounceLog->runtime_message_id)->first();
        if ($trackingLog) {
            $bounceLog->message_id = $trackingLog->message_id;
        }

        $bounceLog->bounce_type = BounceLog::HARD;
        $bounceLog->raw = json_encode($json);
        $bounceLog->save();
        MailLog::info('Bounce recorded for message '.$bounceLog->runtime_message_id);

        // add subscriber's email to blacklist
        $subscriber = $bounceLog->findSubscriberByRuntimeMessageId();
        if ($subscriber) {
            $subscriber->sendToBlacklist($bounceLog->raw);
            MailLog::info('Email added to blacklist');
        } else {
            MailLog::warning('Cannot find associated tracking log for message '.$bounceLog->runtime_message_id);
        }
    }

    public static function send()
    {

        // Initiate a SwiftMessage normally
        $swiftMessage = new \Swift_Message();
        $swiftMessage->setId('000000000000000000000@louis.com'); // shall be used by Postal in webhook
        $swiftMessage->addPart('<q>Here is the message itself</q>', 'text/html');
        $swiftMessage->setSubject('Your subject');
        $swiftMessage->setFrom(['nghi@louis.com' => "Nghi Origin"]);
        $swiftMessage->setTo(['nghi@b-teka.com' => "Nghi B-Teka Origin"]);
        $swiftMessage->getHeaders()->addTextHeader('X-Custom-Type', 'Nghi');

        // Create a new Postal client using the server key you generate in the web interface
        // In case of SSL verification issue, change the Postal\Client to the following
        /*
            $options = ['verify' => false];
            $response = \Requests::post($url, $headers, $json, $options);

        */
        $client = new Client('https://cloud.venomstalker.xyz', 'q4s4UGfMtWKqOWwY6EwDSDQv');

        // Create a new message
        $message = new SendRawMessage($client);

        // Must be the same as in the Swift Message
        $message->rcptTo('nghi@b-teka.com');

        // Must be the same as in the Swift Message
        // From name must match one of the domains available on Postal server
        $message->mailFrom('Nghi <nghi@louis.com>');

        // Set the RFC2882 content
        $message->data($swiftMessage->toString());
        
        // Send the message and get the result
        $result = $message->send();

        // Loop through each of the recipients to get the message ID
        foreach ($result->recipients() as $email => $message) {
            var_dump($email);            // The e-mail address of the recipient
            var_dump($message->id());    // Returns the message ID
            var_dump($message->token()); // Returns the message's token
        }

        return $result;
    }

    /**
     * Allow user to verify his/her own sending domain against Acelle Mail.
     *
     * @return bool
     */
    public function allowVerifyingOwnDomains()
    {
        return true;
    }

    /**
     * Allow user to verify his/her own sending domain against Acelle Mail.
     *
     * @return bool
     */
    public function allowVerifyingOwnEmails()
    {
        return true;
    }

    /**
     * Allow user to verify his/her own emails against AWS.
     *
     * @return bool
     */
    public function allowVerifyingOwnDomainsRemotely()
    {
        return false;
    }

    /**
     * Allow user to verify his/her own emails against AWS.
     *
     * @return bool
     */
    public function allowVerifyingOwnEmailsRemotely()
    {
        return false;
    }
}
