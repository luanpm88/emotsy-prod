<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Library\Log;
use Acelle\Library\StringHelper;
use Aws\Sns\MessageValidator\Message;
use Aws\Sns\MessageValidator\MessageValidator;
use Guzzle\Http\Client;
use Acelle\Model\TrackingLog;
use Acelle\Model\BounceLog;
use Acelle\Model\SendingServer;
use Acelle\Model\FeedbackLog;

class DeliveryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth', [
            'except' => [
                'notify',
            ],
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function notify(Request $request)
    {
        // Make sure the request is POST
        if (!$request->isMethod('post')) {
            return;
        }

        $type = $request->stype;

        echo $type;

        if ($type == 'amazon') { // @TODO hard-coded here, seeking for a solution
            $this->handleAws();
        } elseif ($type == 'mailgun-api') {
            $this->handleMailgun($sendingServer);
        } else {
            return;
        }
    }

    private function handleMailgun($sendingServer)
    {
        Log::configure(storage_path().'/logs/handler-mailgun.log');
        Log::info(print_r($_POST, true));

        if (isset($_POST['timestamp']) && isset($_POST['token']) && isset($_POST['signature']) && hash_hmac('sha256', $_POST['timestamp'].$_POST['token'], $sendingServer->api_key) === $_POST['signature']) {
            if ($_POST['event'] == 'complained') {
                $feedbackLog = new FeedbackLog();
                $feedbackLog->runtime_message_id = StringHelper::cleanupMessageId($_POST['Message-Id']);
                $feedbackLog->feedback_type = 'spam';
                $feedbackLog->raw_feedback_content = '';
                $feedbackLog->save();
                Log::info('Feedback recorded for message '.$feedbackLog->runtime_message_id);
            } elseif ($_POST['event'] == 'bounced') {
                $bounceLog = new BounceLog();
                $bounceLog->runtime_message_id = StringHelper::cleanupMessageId($_POST['Message-Id']);
                $bounceLog->bounce_type = BounceLog::HARD;
                $bounceLog->raw = $_POST['error'];
                $bounceLog->save();
                Log::info('Bounce recorded for message '.$bounceLog->runtime_message_id);
            }
        }
        header('X-PHP-Response-Code: 200', true, 200);
    }

    private function handleAws()
    {
        Log::configure(storage_path().'/logs/handler-aws.log');

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
            (new Client())->get($message->get('SubscribeURL'))->send();

            Log::info('subscription confirmed');

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
            $bounceLog->bounce_type = $bounce['bounceType']; // !== 'Permanent' ? BounceLog::SOFT : BounceLog::HARD;
            $bounceLog->raw = $message->get('Message');
            $bounceLog->save();
            Log::info('Bounce recorded for message '.$bounceLog->runtime_message_id);

            if ($bounce['bounceType'] === 'Permanent') {
                Log::info('Adding email to blacklist');
                $bounceLog->findSubscriberByRuntimeMessageId()->sendToBlacklist($bounceLog->raw);
            }
        }

        if ($responseMessage['notificationType'] == 'Complaint') {
            $feedback = $responseMessage['complaint'];

            $feedbackLog = new FeedbackLog();
            $feedbackLog->runtime_message_id = $responseMessage['mail']['messageId'];
            $feedbackLog->feedback_type = $feedback['complaintFeedbackType'];
            $feedbackLog->raw_feedback_content = $message->get('Message');
            $feedbackLog->save();
            Log::info('Feedback recorded for message '.$feedbackLog->runtime_message_id);
        }
    }
}
