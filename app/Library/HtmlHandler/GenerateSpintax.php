<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use bjoernffm\Spintax\Parser;

class GenerateSpintax implements StageInterface
{
    public function __invoke($html)
    {
        // Introduction: Spintax parser may strip brackets ("{}") in HTML which contains CSS
        // As a result, we need to handle it by extracting text from the HTML

        // Get all text content of the HTML
        // Look after a ">" and before a "<", with any char that is not ">" and "<"
        $htmlTextRegexp = '/(?<=>)[^<>]+(?=<)/';

        // Extract text values from HTML
        preg_match_all($htmlTextRegexp, $html, $matches);

        // Check every value
        foreach($matches[0] as $text) {
            if ($this->containsSpintaxPattern($text)) {
                $transformed = Parser::replicate($text, []);

                // Actually replace in the HTML content
                $html = str_replace(">{$text}<", ">{$transformed}<", $html);
            }
        }

        return $html;
    }

    private function containsSpintaxPattern($text)
    {
        // REGEXP to check if a text contains Spintax {}
        $containsSpintaxRegexp = '/{.+|.+}/';
        return preg_match($containsSpintaxRegexp, $text) == true;
    }
}
