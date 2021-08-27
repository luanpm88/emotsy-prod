<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use File;
use Validator;
use ZipArchive;
use KubAT\PhpSimple\HtmlDomParser;
use Acelle\Library\ExtendedSwiftMessage;
use Acelle\Library\Tool;
use Acelle\Library\Rss;
use Acelle\Library\StringHelper;
use Acelle\Library\Log as MailLog;
use Acelle\Model\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Acelle\Jobs\DeliverEmail;
use Acelle\Library\Traits\HasTemplate;
use Acelle\Library\Traits\HasUid;
use Exception;
use League\Pipeline\PipelineBuilder;
use Acelle\Library\HtmlHandler\ParseRss;
use Acelle\Library\HtmlHandler\ReplaceBareLineFeed;
use Acelle\Library\HtmlHandler\AppendHtml;
use Acelle\Library\HtmlHandler\TransformTag;
use Acelle\Library\HtmlHandler\InjectTrackingPixel;
use Acelle\Library\HtmlHandler\MakeInlineCss;
use Acelle\Library\HtmlHandler\TransformUrl;
use Acelle\Library\HtmlHandler\AddDoctype;
use Acelle\Library\HtmlHandler\RemoveTitleTag;

class Email extends Model
{
    use HasTemplate, HasUid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'from', 'from_name', 'reply_to', 'sign_dkim', 'track_open', 'track_click', 'action_id',
    ];

    // Cached HTML content
    protected $parsedContent = null;

    /**
     * Association with mailList through mail_list_id column.
     */
    public function automation()
    {
        return $this->belongsTo('Acelle\Model\Automation2', 'automation2_id');
    }

    /**
     * Get the customer.
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * Association with attachments.
     */
    public function attachments()
    {
        return $this->hasMany('Acelle\Model\Attachment');
    }

    /**
     * Association with email links.
     */
    public function emailLinks()
    {
        return $this->hasMany('Acelle\Model\EmailLink');
    }

    /**
     * Association with open logs.
     */
    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog');
    }

    public function deliveryAttempts()
    {
        return $this->hasMany('Acelle\Model\DeliveryAttempt');
    }

    /**
     * Get email's associated tracking domain.
     */
    public function trackingDomain()
    {
        return $this->belongsTo('Acelle\Model\TrackingDomain', 'tracking_domain_id');
    }

    /**
     * Get campaign's default mail list.
     */
    public function defaultMailList()
    {
        return $this->automation->mailList();
    }

    /**
     * Create automation rules.
     *
     * @return array
     */
    public function rules($request=null)
    {
        $rules = [
            'subject' => 'required',
            'from' => 'required|email',
            'from_name' => 'required',
        ];

        // tracking domain
        if (isset($request) && $request->custom_tracking_domain) {
            $rules['tracking_domain_uid'] = 'required';
        }

        return $rules;
    }

    /**
     * Upload attachment.
     */
    public function uploadAttachment($file)
    {
        $file_name = $file->getClientOriginalName();
        $att = $this->attachments()->make();
        $att->size = $file->getSize();
        $att->name = $file->getClientOriginalName();

        $path = $file->move(
            $this->getAttachmentPath(),
            $att->name
        );
        
        $att->file = $this->getAttachmentPath($att->name);
        $att->save();

        return $att;
    }

    /**
     * Get attachment path.
     */
    public function getAttachmentPath($path = null)
    {
        return $this->customer->getAttachmentsPath($path);
    }

    /**
     * Find and update email links.
     */
    public function updateLinks()
    {
        if (!$this->getTemplateContent()) {
            return false;
        }

        $links = [];

        // find all links from contents
        // Fix: str_get_html returning false
        defined('MAX_FILE_SIZE') || define('MAX_FILE_SIZE', 10000000);
        $document = HtmlDomParser::str_get_html($this->getTemplateContent());
        foreach ($document->find('a') as $element) {
            if (preg_match('/^http/', $element->href) != 0) {
                $links[] = trim($element->href);
            }
        }

        // delete al bold links
        $this->emailLinks()->whereNotIn('link', $links)->delete();

        foreach ($links as $link) {
            $exist = $this->emailLinks()->where('link', '=', $link)->count();

            if (!$exist) {
                $this->emailLinks()->create([
                    'link' => $link,
                ]);
            }
        }
    }

    public function queueDeliverTo($subscriber, $triggerId = null)
    {
        dispatch(new DeliverEmail(
            $this,
            $subscriber,
            $triggerId,
        ));
    }

    public function sendTestEmail($emailAddress)
    {
        $validator = Validator::make([ 'email' => $emailAddress ], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $firstErrorMsg = $validator->errors()->first();
            throw new Exception($firstErrorMsg);
        }

        $server = $this->automation->mailList->pickSendingServer();

        // Build a valid message with a fake contact
        // build a temporary subscriber oject used to pass through the sending methods
        $subscriber = $this->createStdClassSubscriber(['email' => $emailAddress]);
        list($message, $msgId) = $this->prepare($subscriber);
        $server->send($message);
    }

    // @note: complicated dependencies
    // It is just fine, we can think Email as an object depends on User/Customer
    public function deliverTo($subscriber, $triggerId = null)
    {
        $server = $subscriber->mailList->pickSendingServer();
        MailLog::info("Sending to subscriber `{$subscriber->email}`");
        list($message, $msgId) = $this->prepare($subscriber);

        if (config('app.demo') == true) {
            $sent = array(
                'runtime_message_id' => $msgId,
                'status' => SendingServer::DELIVERY_STATUS_SENT
            );
            // additional log
            MailLog::info("[DEMO] Sent to subscriber `{$subscriber->email}`");
        } else {
            $sent = $server->send($message);
            // additional log
            MailLog::info("Sent to subscriber `{$subscriber->email}`");
        }

        $this->trackMessage($sent, $subscriber, $server, $msgId, $triggerId);
    }

    /**
     * Create a stdClass subscriber (for sending a campaign test email)
     * The campaign sending functions take a subscriber object as input
     * However, a test email address is not yet a subscriber object, so we have to build a fake stdClass object
     * which can be used as a real subscriber.
     *
     * @param array $subscriber
     */
    public function createStdClassSubscriber($subscriber)
    {
        // default attributes that are required
        $jsonObj = [
            'uid' => uniqid(),
        ];

        // append the customer specified attributes and build a stdClass object
        $stdObj = json_decode(json_encode(array_merge($jsonObj, $subscriber)));

        return $stdObj;
    }

    /**
     * Prepare the email content using Swift Mailer.
     *
     * @input object subscriber
     * @input object sending server
     *
     * @return MIME text message
     */
    public function prepare($subscriber)
    {
        $this->updateLinks();

        // build the message
        $customHeaders = $this->getCustomHeaders($subscriber, $this);
        $msgId = $customHeaders['X-Acelle-Message-Id'];

        $message = new ExtendedSwiftMessage();
        $message->setId($msgId);

        // fixed: HTML type only
        $message->setContentType('text/html; charset=utf-8');

        foreach ($customHeaders as $key => $value) {
            $message->getHeaders()->addTextHeader($key, $value);
        }

        // @TODO for AWS, setting returnPath requires verified domain or email address
        $server = $this->automation->mailList->pickSendingServer();
        if ($server->allowCustomReturnPath()) {
            $returnPath = $server->getVerp($subscriber->email);
            if ($returnPath) {
                $message->setReturnPath($returnPath);
            }
        }
        $message->setSubject($this->getSubject($subscriber, $msgId));
        $message->setFrom(array($this->from => $this->from_name));
        $message->setTo($subscriber->email);
        $message->setReplyTo($this->reply_to);
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->addPart($this->getHtmlContent($subscriber, $msgId, $server), 'text/html');

        if ($this->sign_dkim) {
            $message = $this->sign($message);
        }

        foreach ($this->attachments as $file) {
            $attachment = \Swift_Attachment::fromPath($file->file);
            $message->attach($attachment);
            // This is used by certain delivery services like ElasticEmail
            $message->extAttachments[] = [ 'path' => $file->file, 'type' => $attachment->getContentType()];
        }

        return array($message, $msgId);
    }

    /**
     * Build Email Custom Headers.
     *
     * @return Hash list of custom headers
     */
    public function getCustomHeaders($subscriber, $server)
    {
        $msgId = StringHelper::generateMessageId(StringHelper::getDomainFromEmail($this->from));
        if ($this->isStdClassSubscriber($subscriber)) {
            $unsubscribeUrl = null;
        } else {
            $unsubscribeUrl = $subscriber->generateUnsubscribeUrl($msgId);
            if ($this->trackingDomain) {
                $unsubscribeUrl = $this->trackingDomain->buildTrackingUrl($unsubscribeUrl);
            }
        }

        $headers = array(
            'X-Acelle-Campaign-Id' => $this->uid,
            'X-Acelle-Subscriber-Id' => $subscriber->uid,
            'X-Acelle-Customer-Id' => $this->customer->uid,
            'X-Acelle-Message-Id' => $msgId,
            'X-Acelle-Sending-Server-Id' => $server->uid,
            'Precedence' => 'bulk',
        );

        if ($unsubscribeUrl) {
            $headers['List-Unsubscribe'] = "<{$unsubscribeUrl}>";
        }

        return $headers;
    }

    /**
     * Log delivery message, used for later tracking.
     */
    public function trackMessage($response, $subscriber, $server, $msgId, $triggerId = null)
    {

        // @todo: customerneedcheck
        $params = array_merge(array(
                // 'email_id' => $this->id,
                'message_id' => $msgId,
                'subscriber_id' => $subscriber->id,
                'sending_server_id' => $server->id,
                'customer_id' => $this->automation->customer->id,
                'auto_trigger_id' => $triggerId,
            ), $response);

        if (!isset($params['runtime_message_id'])) {
            $params['runtime_message_id'] = $msgId;
        }

        // create tracking log for message
        $this->trackingLogs()->create($params);
    }

    /**
     * Get tagged Subject.
     *
     * @return string
     */
    public function getSubject($subscriber, $msgId)
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new ReplaceBareLineFeed);
        $pipeline->add(new TransformTag($this, $subscriber, $msgId));
        return $pipeline->build()->process($this->subject);
    }

    /**
     * Check if the given variable is a subscriber object (for actually sending a email)
     * Or a stdClass subscriber (for sending test email).
     *
     * @param object $object
     */
    public function isStdClassSubscriber($object)
    {
        return get_class($object) == 'stdClass';
    }

    /**
     * Build Email HTML content.
     *
     * @return string
     */
    public function getHtmlContent($subscriber = null, $msgId = null, $server = null)
    {
        if (!$this->template) {
            throw new Exception('No template available');
        }

        $pipeline = new PipelineBuilder();
        $pipeline->add(new AddDoctype);
        $pipeline->add(new RemoveTitleTag);
        $pipeline->add(new ReplaceBareLineFeed);
        $pipeline->add(new AppendHtml($this->getHtmlFooter()));
        $pipeline->add(new ParseRss);
        $pipeline->add(new TransformTag($this, $subscriber, $msgId, $server));
        $pipeline->add(new InjectTrackingPixel($msgId));
        $pipeline->add(new TransformUrl($this->template, $msgId, $this->trackingDomain));
        $pipeline->add(new MakeInlineCss($this->template->findCssFiles()));

        $html = $pipeline->build()->process($this->getTemplateContent());

        // $html = $this->wooTransform($html);

        return $html;
    }

    public function wooTransform($body)
    {
        if ($this->automation->getTrigger()->getOption('source_uid')) {
            $client = new \GuzzleHttp\Client();

            // find all links from contents
            $document = HtmlDomParser::str_get_html($body);

            // Woo Items List
            foreach ($document->find('[builder-element=ProductListElement]') as $element) {
                $max = $element->getAttribute('data-max-items');
                $display = $element->getAttribute('data-display');
                $sort = $element->getAttribute('data-sort-by');

                $request = request();
                $request->merge(['per_page' => $max]);
                $request->merge(['sort_by' => $sort]);

                $items = Product::search($request)->paginate($request->per_page)
                    ->map(function ($product, $key) {
                        return [
                            'id' => $product->uid,
                            'name' => $product->title,
                            'price' => $product->price,
                            'image' => $product->getImageUrl(),
                            'description' => substr(strip_tags($product->description), 0, 100),
                            'link' => action('ProductController@index'),
                        ];
                    })->toArray();
                $itemsHtml = [];
                foreach ($items as $item) {
                    // $element->find('.woo-items')[0]->innertext = 'dddddd';
                    $itemsHtml[] = '
                        <div class="woo-col-item mb-4 mt-4 col-md-' . (12/$display) . '">
                            <div class="">
                                <div class="img-col mb-3">
                                    <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <a style="width:100%" href="'.$item["link"].'" class="mr-4"><img width="100%" src="'.($item["image"] ? $item["image"] : url('images/cart_item.svg')).'" style="max-height:200px;max-width:100%;" /></a>
                                    </div>
                                </div>
                                <div class="">
                                    <p class="font-weight-normal product-name mb-1">
                                        <a style="color: #333;" href="'.$item["link"].'" class="mr-4">'.$item["name"].'</a>
                                    </p>
                                    <p class=" product-description">'.$item["description"].'</p>
                                    <p><strong>'.$item["price"].'</strong></p>
                                    <a href="'.$item["link"].'" style="background-color: #9b5c8f;
        border-color: #9b5c8f;" class="btn btn-primary text-white">
                                        ' . trans('messages.automation.view_more') . '
                                    </a>
                                </div>
                            </div>
                        </div>
                    ';
                }

                $element->find('.products')[0]->innertext = implode('', $itemsHtml);
            }

            // Woo Single Item
            foreach ($document->find('[builder-element=ProductElement]') as $element) {
                $productId = $element->getAttribute('product-id');

                if ($productId) {
                    $product = Product::findByUid($productId);

                    $item = [
                        'id' => $product->uid,
                        'name' => $product->title,
                        'price' => $product->price,
                        'image' => $product->getImageUrl(),
                        'description' => substr(strip_tags($product->description), 0, 100),
                        'link' => action('ProductController@index'),
                    ];
                    // $element->find('.product-name', 0)->innertext = $item["name"];
                    // $element->find('.product-description', 0)->innertext = $item["description"];
                    // $element->find('.product-link', 0)->href = $item["link"];
                    // $element->find('.product-price', 0)->innertext = $item["price"];
                    $element->find('.product-link img', 0)->src = $item["image"];
                    $html = $element->innertext;
                    $html = str_replace('*|PRODUCT_NAME|*', $item["name"], $html);
                    $html = str_replace('*|PRODUCT_DESCRIPTION|*', $item["description"], $html);
                    $html = str_replace('*|PRODUCT_URL|*', $item["link"], $html);
                    $html = str_replace('*|PRODUCT_PRICE|*', $item["price"], $html);
                    // $html = str_replace('*|PRODUCT_QUANTITY|*', $item["quantity"], $html);
                    $element->innertext = $html;
                }
            }

            $body = $document;
        }

        return $body;
    }

    /**
     * Check if email footer enabled.
     *
     * @return string
     */
    public function footerEnabled()
    {
        return ($this->automation->customer->getCurrentSubscription()->plan->getOption('email_footer_enabled') == 'yes') ? true : false;
    }

    /**
     * Get HTML footer.
     *
     * @return string
     */
    public function getHtmlFooter()
    {
        return $this->automation->customer->getCurrentSubscription()->plan->getOption('html_footer');
    }

    /**
     * Find sending domain from email.
     *
     * @return mixed
     */
    public function findSendingDomain($email)
    {
        $domainName = substr(strrchr($email, '@'), 1);

        if ($domainName == false) {
            return;
        }

        $domain = $this->customer->sendingDomains()->where('name', $domainName)->first();

        return $domain;
    }

    /**
     * Sign the message with DKIM.
     *
     * @return mixed
     */
    public function sign($message)
    {
        $sendingDomain = $this->findSendingDomain($this->from);

        if (empty($sendingDomain)) {
            return $message;
        }

        $privateKey = $sendingDomain->dkim_private;
        $domainName = $sendingDomain->name;
        $selector = $sendingDomain->getDkimSelectorParts()[0];
        $signer = new \Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
        $signer->ignoreHeader('Return-Path');
        $message->attachSigner($signer);

        return $message;
    }

    public function isOpened($subscriber)
    {
        return $this->trackingLogs()->where('subscriber_id', $subscriber->id)
                            ->join('open_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')->exists();
    }

    public function isClicked($subscriber)
    {
        return $this->trackingLogs()->where('subscriber_id', $subscriber->id)
                            ->join('click_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id')->exists();
    }

    /**
     * Fill email's fields from request.
     */
    public function fillAttributes($params)
    {
        $this->fill($params);

        // Tacking domain
        if (isset($params['custom_tracking_domain']) && $params['custom_tracking_domain'] && isset($params['tracking_domain_uid'])) {
            $tracking_domain = \Acelle\Model\TrackingDomain::findByUid($params['tracking_domain_uid']);
            if (is_object($tracking_domain)) {
                $this->tracking_domain_id = $tracking_domain->id;
            } else {
                $this->tracking_domain_id = null;
            }
        } else {
            $this->tracking_domain_id = null;
        }
    }

    public function isSetup()
    {
        return $this->subject && $this->reply_to && $this->from && $this->template;
    }

    public function deleteAndCleanup()
    {
        if ($this->template) {
            $this->template->deleteAndCleanup();
        }

        $this->delete();
    }
}
