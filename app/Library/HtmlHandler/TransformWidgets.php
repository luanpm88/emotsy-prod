<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;
use Acelle\Model\Template;
use Acelle\Model\Product;
use Exception;

class TransformWidgets implements StageInterface
{
    public $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    public function __invoke($html)
    {
        return StringHelper::updateHtml($html, function($dom) {
            $finder = new \DOMXPath($dom);
            $classname="product-list-widget";
            $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            foreach ($nodes as $node)
            {
                $pList = $node->getElementsByTagName('products')[0];
                $pHtml =  Product::generateWidgetProductListHtmlContent([
                    'count' => $node->getAttribute('data-count'),
                    'cols' => $node->getAttribute('data-cols'),
                    'sort' => $node->getAttribute('data-sort'),
                    'preview' => $node->getAttribute('data-preview'),
                ]);

                // remove all child
                while ($pList->hasChildNodes()){
                    $pList->removeChild($pList->childNodes->item(0));
                }

                // try to append html
                $tmpDoc = new \DOMDocument();
                $tmpDoc->loadHTML(mb_convert_encoding($pHtml, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NODEFDTD);
                foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $cNode) {
                    $n = $dom->importNode($cNode, true);
                    $pList->appendChild($n);
                }
            }
        });
    }
}
