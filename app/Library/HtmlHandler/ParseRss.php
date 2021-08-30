<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use SimpleXMLElement;
use Twig\TwigFunction;
use Twig\Loader\ArrayLoader;
use Twig\Environment;

class ParseRss implements StageInterface
{
    public function __invoke($html)
    {
        $rss = new TwigFunction('rss', function ($url, $count = 10) {
            $dom = simplexml_load_string(file_get_contents($url), 'SimpleXMLElement', LIBXML_NOCDATA);
            $x = $this->xmlToArray($dom);
            $x = ($x['rss']['channel']);
            $x['item'] = array_slice($x['item'], 0, $count);
            return $x;
        });

        $loader = new ArrayLoader([
            'content' => $html,
        ]);

        $twig = new Environment($loader);
        $twig->addFunction($rss);

        return $twig->render('content');
    }

    private function xmlToArray(SimpleXMLElement $xml): array
    {
        $parser = function (SimpleXMLElement $xml, array $collection = []) use (&$parser) {
            $nodes = $xml->children();
            $attributes = $xml->attributes();

            if (0 !== count($attributes)) {
                foreach ($attributes as $attrName => $attrValue) {
                    $collection['attributes'][$attrName] = html_entity_decode(strval($attrValue));
                }
            }

            if (0 === $nodes->count()) {
                // $collection['value'] = strval($xml);
                // return $collection;
                return html_entity_decode(strval($xml));
            }

            foreach ($nodes as $nodeName => $nodeValue) {
                if (count($nodeValue->xpath('../' . $nodeName)) < 2) {
                    $collection[$nodeName] = $parser($nodeValue);
                    continue;
                }

                $collection[$nodeName][] = $parser($nodeValue);
            }

            return $collection;
        };

        return [
            $xml->getName() => $parser($xml)
        ];
    }
}
