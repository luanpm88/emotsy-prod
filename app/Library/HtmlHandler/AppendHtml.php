<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;
use DOMDocument;

class AppendHtml implements StageInterface
{
    public $newHtml;

    public function __construct($newHtml)
    {
        $this->newHtml = $newHtml;
    }

    public function __invoke($html)
    {
        return StringHelper::updateHtml($html, function($document) {
            $tmpDoc = new DOMDocument();
            $tmpDoc->loadHTML($this->newHtml);
            foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node) {
                $node = $document->importNode($node, true);
                $document->getElementsByTagName('body')->item(0)->appendChild($node);
            }
        });
    }
}