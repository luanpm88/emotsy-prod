<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

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
        // Make sure the request is POST
        if (!$request->isMethod('post')) {
            return;
        }
      
        try {
            // Create a message from the post data and validate its signature
            $message = Message::fromRawPostData();
            $validator = new MessageValidator();
            $validator->validate($message);
        } catch (Exception $e) {
            // Pretend we're not here if the message is invalid
            return;
        }

        if ($message->get('Type') === 'SubscriptionConfirmation') {
            // Send a request to the SubscribeURL to complete subscription
            (new Client)->get($message->get('SubscribeURL'))->send();
            return;
        }

        if ($message->get('Type') != 'Notification') {
            return;
        }

        $responseMessage = json_decode($message->get('Message'), true);
        if ($responseMessage['notificationType'] == 'AmazonSnsSubscriptionSucceeded') {
            return;
        }

        $deliveryLog = \Mailixa\Web\CampaignDeliveryLog::where("email_message_id", $responseMessage['mail']['messageId'])->first() ;

        if (empty($deliveryLog)) {
            return;
        }

        if ($responseMessage['notificationType'] == 'Bounce') {
            $bounce = $responseMessage['bounce'];
            $bounceLog = new \Mailixa\Web\CampaignBounceLog;
            $bounceLog->campaign_id     = $deliveryLog->campaign->campaign_id;
            $bounceLog->subscriber_id   = $deliveryLog->subscriber->subscriber_id;
            $bounceLog->message         = !empty($bounce['bouncedRecipients'][0]['diagnosticCode']) ? $bounce['bouncedRecipients'][0]['diagnosticCode'] : 'BOUNCED BACK';
            $bounceLog->bounce_type     = $bounce['bounceType'] !== 'Permanent' ? \Mailixa\Web\CampaignBounceLog::BOUNCE_SOFT : \Mailixa\Web\CampaignBounceLog::BOUNCE_HARD;
            $bounceLog->save();

            if ($bounce['bounceType'] === 'Permanent') {
                $deliveryLog->subscriber->addToBlacklist($bounceLog->message);
            }
        }
    }
}
