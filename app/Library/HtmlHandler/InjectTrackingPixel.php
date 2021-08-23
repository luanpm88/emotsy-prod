<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class InjectTrackingPixel implements StageInterface
{
    public $msgId;

    public function __construct($msgId)
    {
        $this->msgId = $msgId;
    }

    public function __invoke($html)
    {
        $pixel = StringHelper::makeTrackingPixel($this->msgId);
        return StringHelper::appendHtml($html, $pixel);
    }
}