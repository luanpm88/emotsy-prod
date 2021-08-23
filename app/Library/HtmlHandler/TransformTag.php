<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class TransformTag implements StageInterface
{
    public $campaign;
    public $subscriber;
    public $msgId;
    public $server;

    // Campaign or email
    public function __construct($campaign, $subscriber, $msgId, $server = null)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
        $this->msgId = $msgId;
        $this->server = $server;
    }
    public function __invoke($html)
    {
        // DEPRECATED
        if (!is_null($this->server) && $this->server->isElasticEmailServer()) {
            $html = $this->server->addUnsubscribeUrl($html);
        }

        $tags = array(
            'CAMPAIGN_NAME' => $this->campaign->name,
            'CAMPAIGN_UID' => $this->campaign->uid,
            'CAMPAIGN_SUBJECT' => $this->campaign->subject,
            'CAMPAIGN_FROM_EMAIL' => $this->campaign->from_email,
            'CAMPAIGN_FROM_NAME' => $this->campaign->from_name,
            'CAMPAIGN_REPLY_TO' => $this->campaign->reply_to,
            'CURRENT_YEAR' => date('Y'),
            'CURRENT_MONTH' => date('m'),
            'CURRENT_DAY' => date('d'),
            'LIST_NAME' => $this->campaign->defaultMailList->name,
            'LIST_SUBJECT' => $this->campaign->defaultMailList->default_subject,
            'LIST_FROM_NAME' => $this->campaign->defaultMailList->from_name,
            'LIST_FROM_EMAIL' => $this->campaign->defaultMailList->from_email,
        );

        # Subscriber specific
        if (is_null($this->subscriber) || empty($this->msgId) || $this->campaign->isStdClassSubscriber($this->subscriber)) {
            $sampleLink = route('campaign_message', [ 'message' => StringHelper::base64UrlEncode(trans('messages.email.test_link_note')) ]);

            if ($this->campaign->trackingDomain) {
                $sampleLink = $this->campaign->trackingDomain->buildTrackingUrl($sampleLink);
            }

            $tags['UNSUBSCRIBE_URL'] = $sampleLink;
            $tags['UPDATE_PROFILE_URL'] = $sampleLink;
            $tags['WEB_VIEW_URL'] = $sampleLink;
            $tags['SUBSCRIBER_UID'] = '%UID%';

            # Subscriber custom fields, including email
            $sample = '%PERSONALIZED-DATA%';
            foreach ($this->campaign->defaultMailList->fields as $field) {
                $tags['SUBSCRIBER_'.$field->tag] = $sample;
                $tags[$field->tag] = $sample;
            }

            // Special / shortcut fields
            $tags['NAME'] = $sample;
            $tags['FULL_NAME'] = $sample;

            // Only email is "reserved", overwrite previous $sample
            $tags['SUBSCRIBER_EMAIL'] = is_null($this->subscriber) ? 'email@sample.com' : $this->subscriber->email;
        } else {
            $updateProfileUrl = $this->subscriber->generateUpdateProfileUrl();
            $unsubscribeUrl = $this->subscriber->generateUnsubscribeUrl($this->msgId);
            $webViewUrl = StringHelper::generateWebViewerUrl($this->msgId);

            if ($this->campaign->trackingDomain) {
                $updateProfileUrl = $this->campaign->trackingDomain->buildTrackingUrl($updateProfileUrl);
                $unsubscribeUrl = $this->campaign->trackingDomain->buildTrackingUrl($unsubscribeUrl);
                $webViewUrl = $this->campaign->trackingDomain->buildTrackingUrl($webViewUrl);
            }

            $tags['UPDATE_PROFILE_URL'] = $updateProfileUrl;
            $tags['UNSUBSCRIBE_URL'] = $unsubscribeUrl;
            $tags['WEB_VIEW_URL'] = $webViewUrl;

            $tags['SUBSCRIBER_UID'] = $this->subscriber->uid;

            # Subscriber custom fields
            foreach ($this->campaign->defaultMailList->fields as $field) {
                $tags['SUBSCRIBER_'.$field->tag] = $this->subscriber->getValueByField($field);
                $tags[$field->tag] = $this->subscriber->getValueByField($field);
            }

            // Special / shortcut fields
            $tags['NAME'] = $this->subscriber->getFullName();
            $tags['FULL_NAME'] = $this->subscriber->getFullName();
        }

        // Actually transform the message
        foreach ($tags as $tag => $value) {
            $html = str_replace('{'.$tag.'}', $value, $html);
        }

        return $html;
    }
}
