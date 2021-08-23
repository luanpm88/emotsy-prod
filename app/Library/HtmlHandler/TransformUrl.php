<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;
use Acelle\Model\TrackingDomain;
use Acelle\Model\Template;

class TransformUrl implements StageInterface
{
    public $template;
    public $msgId;
    public $domain;

    public function __construct(Template $template, $msgId, TrackingDomain $domain = null)
    {
        $this->template = $template;
        $this->msgId = $msgId;
        $this->domain = $domain;
    }

    public function __invoke($html)
    {
        $transformClosure = function($url, $element) {
            if ($element->nodeName == 'a') {
                return StringHelper::makeTrackableLink($url, $this->msgId);
            } else {
                return $url;
            }
        };

        $html = $this->template->getContentWithTransformedAssetsUrls($html, $withHost = true, $transformClosure, $this->domain);
        return $html;
    }
}