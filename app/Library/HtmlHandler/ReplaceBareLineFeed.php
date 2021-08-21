<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;

class ReplaceBareLineFeed implements StageInterface
{
    public function __invoke($html)
    {
        return trim(preg_replace("/(?<=[^\r])\n/", "\r\n", $html));
    }
}