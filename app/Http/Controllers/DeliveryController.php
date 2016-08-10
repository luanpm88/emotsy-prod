<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;
use Mailixa\Library\Log;
use Mailixa\Http\Requests;
use Aws\Sns\MessageValidator\Message;
use Aws\Sns\MessageValidator\MessageValidator;
use Guzzle\Http\Client;
use Mailixa\Model\TrackingLog;
use Mailixa\Model\BounceLog;
use Mailixa\Model\FeedbackLog;

class DeliveryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('auth', [
            'except' => [
                'notify',
            ]
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        /**
     * Campaign notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notify(Request $request)
    {
        Log::configure(storage_path() . '/logs/delivery.log');

        // Make sure the request is POST
        if (!$request->isMethod('post')) {
            return;
        }
      
        try {
            Log::info('validating message...');
            // Create a message from the post data and validate its signature
            $message = Message::fromRawPostData();
            $validator = new MessageValidator();
            $validator->validate($message);
            Log::info('message validated!');
        } catch (\Exception $e) {
            // Pretend we're not here if the message is invalid
            Log::warning('not an Amazon push');
            return;
        }

        if ($message->get('Type') === 'SubscriptionConfirmation') {
            Log::info('subscription received');
            // Send a request to the SubscribeURL to complete subscription
            (new Client)->get($message->get('SubscribeURL'))->send();
            
            Log::info('sent back to AWS');
            return;
        }

        if ($message->get('Type') != 'Notification') {
            Log::info('not notification');
            return;
        }

        $responseMessage = json_decode($message->get('Message'), true);

        

        if ($responseMessage['notificationType'] == 'AmazonSnsSubscriptionSucceeded') {
            Log::info('subscription confirmed by AWS');
            return;
        }
        
        /*
        sleep(5);
        $trackingLog = TrackingLog::where("message_id", $responseMessage['mail']['messageId'])->first() ;
        if (empty($trackingLog)) {
            Log::error('message_id not found');
            return;
        }
        */

        if ($responseMessage['notificationType'] == 'Bounce') {
            $bounce = $responseMessage['bounce'];

            $bounceLog = new BounceLog();
            $bounceLog->runtime_message_id = $responseMessage['mail']['messageId'];
            $bounceLog->bounce_type = $bounce['bounceType'] !== 'Permanent' ? BounceLog::SOFT : BounceLog::HARD;
            $bounceLog->raw = $message->get('Message');
            $bounceLog->save();
            Log::info('Bounce recorded for message ' . $bounceLog->message_id);

            /*
            if ($bounce['bounceType'] === 'Permanent') {
                $deliveryLog->subscriber->addToBlacklist($bounceLog->message);
            }
            */
        }

        if ($responseMessage['notificationType'] == 'Complaint') {
            $feedback = $responseMessage['complaint'];

            $feedbackLog = new FeedbackLog();
            $feedbackLog->message_id = $responseMessage['mail']['messageId'];
            $feedbackLog->feedback_type = $feedback['complaintFeedbackType'];
            $feedbackLog->raw_feedback_content = $message->get('Message');
            $feedbackLog->save();
            Log::info('Feedback recorded for message ' . $feedbackLog->message_id);

            /*
            if ($bounce['bounceType'] === 'Permanent') {
                $deliveryLog->subscriber->addToBlacklist($bounceLog->message);
            }
            */
        }
    }
}
