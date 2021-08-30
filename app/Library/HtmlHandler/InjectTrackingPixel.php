<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class InjectTrackingPixel implements StageInterface
{
    public $msgId;

    public function __construct($msgId)
    {
        $this->msgId = trim($msgId);
    }

    public function __invoke($html)
    {
        if (empty($this->msgId)) {
            return $html;
        }

        $pixel = StringHelper::makeTrackingPixel($this->msgId);
        return StringHelper::appendHtml($html, $pixel);
    }
}
