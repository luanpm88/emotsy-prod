<?php

namespace Acelle\Library\Traits;

use Acelle\Model\Template;
use Exception;
use Acelle\Library\StringHelper;

trait HasTemplate
{
    /**
     * Campaign has one template.
     */
    public function template()
    {
        return $this->belongsTo('Acelle\Model\Template');
    }

    /**
     * Get template.
     */
    public function setTemplate($template)
    {
        $campaignTemplate = $template->copy([
            'name' => trans('messages.campaign.template_name', ['name' => $this->name]),
            'customer_id' => $this->customer_id,
        ]);

        // remove exist template
        if ($this->template) {
            $this->template->deleteAndCleanup();
        }

        $this->template_id = $campaignTemplate->id;
        $this->save();
        $this->refresh();
        $this->updatePlainFromHtml();
        $this->updateLinks();
    }

    /**
     * Upload a template.
     */
    public function uploadTemplate($request)
    {
        $template = Template::uploadTemplate($request);
        $this->setTemplate($template);
    }

    /**
     * Check if email has template.
     */
    public function hasTemplate()
    {
        return $this->template()->exists();
    }

    /**
     * Get thumb.
     */
    public function getThumbUrl()
    {
        if ($this->template) {
            return $this->template->getThumbUrl();
        } else {
            return url('images/placeholder.jpg');
        }
    }

    /**
     * Remove email template.
     */
    public function removeTemplate()
    {
        $this->template->deleteAndCleanup();
    }

    /**
     * Update campaign plain text.
     */
    public function updatePlainFromHtml()
    {
        if (!$this->plain) {
            $this->plain = preg_replace('/\s+/', ' ', preg_replace('/\r\n/', ' ', strip_tags($this->getTemplateContent())));
            $this->save();
        }
    }

    /**
     * Set template content.
     */
    public function setTemplateContent($content, $callback = null)
    {
        if (!$this->template) {
            throw new Exception('Cannot set content: campaign/email does not have template!');
        }

        $template = $this->template;
        $template->content = $content;
        $template->save();
        if (!is_null($callback)) {
            $callback($this);
        }
    }

    /**
     * Get template content.
     */
    public function getTemplateContent()
    {
        if (!$this->template) {
            throw new Exception('Cannot get content: campaign/email does not have template!');
        }

        return $this->template->content;
    }

    /**
     * Build Email Custom Headers.
     *
     * @return Hash list of custom headers
     */
    public function getCustomHeaders($subscriber, $server)
    {
        $msgId = StringHelper::generateMessageId(StringHelper::getDomainFromEmail($this->from_email));

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
     * Prepare the email content using Swift Mailer.
     *
     * @input object subscriber
     * @input object sending server
     *
     * @return MIME text message
     */
    public function prepareEmail($subscriber, $server = null)
    {
        // build the message
        $customHeaders = $this->getCustomHeaders($subscriber, $this);
        $msgId = $customHeaders['X-Acelle-Message-Id'];

        $message = new ExtendedSwiftMessage();
        $message->setId($msgId);

        if (is_null($this->type) || $this->type == self::TYPE_REGULAR) {
            $message->setContentType('text/html; charset=utf-8');
        } else {
            $message->setContentType('text/plain; charset=utf-8');
        }

        foreach ($customHeaders as $key => $value) {
            $message->getHeaders()->addTextHeader($key, $value);
        }

        // @TODO for AWS, setting returnPath requires verified domain or email address
        if (!is_null($server) && $server->allowCustomReturnPath()) {
            $returnPath = $server->getVerp($subscriber->email);
            if ($returnPath) {
                $message->setReturnPath($returnPath);
            }
        }
        $message->setSubject($this->getSubject($subscriber, $msgId));
        $message->setFrom(array($this->from_email => $this->from_name));
        $message->setTo($subscriber->email);

        if (!empty(Setting::get('campaign.bcc'))) {
            $addresses = array_filter(preg_split('/\s*,\s*/', Setting::get('campaign.bcc')));
            $message->setBcc($addresses);
        }

        if (!empty(Setting::get('campaign.cc'))) {
            $addresses = array_filter(preg_split('/\s*,\s*/', Setting::get('campaign.cc')));
            $message->setCc($addresses);
        }

        $message->setReplyTo($this->reply_to);
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->addPart($this->getPlainContent($subscriber, $msgId, $server), 'text/plain');
        if (is_null($this->type) || $this->type == self::TYPE_REGULAR) {
            $message->addPart($this->getHtmlContent($subscriber, $msgId, $server), 'text/html');
        }

        if ($this->sign_dkim) {
            $message = $this->sign($message);
        }

        if ($this->attachments) {
            // Email model
            foreach ($this->attachments as $file) {
                $attachment = \Swift_Attachment::fromPath($file->file);
                $message->attach($attachment);
                // This is used by certain delivery services like ElasticEmail
                $message->extAttachments[] = [ 'path' => $file->file, 'type' => $attachment->getContentType()];
            }
        } else {
            // Campaign model
            //@todo attach function used for any attachment of Campaign
            $path_campaign = $this->getAttachmentPath();
            if (is_dir($path_campaign)) {
                $files = File::allFiles($path_campaign);
                foreach ($files as $file) {
                    $attachment = \Swift_Attachment::fromPath((string) $file);
                    $message->attach($attachment);
                    // This is used by certain delivery services like ElasticEmail
                    $message->extAttachments[] = [ 'path' => (string) $file, 'type' => $attachment->getContentType()];
                }
            }
        }

        return array($message, $msgId);
    }
}
