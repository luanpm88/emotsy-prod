<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use bjoernffm\Spintax\Parser;

class GenerateSpintax implements StageInterface
{
    public function __invoke($html)
    {
        return $html;
        //return Parser::replicate($html, []);
    }
}
