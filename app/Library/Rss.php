<?php

namespace Acelle\Library;

/**
 * Tool class.
 */
class Rss
{
    
    protected $raw = null;
    protected $blocks = [];

    public function __construct($raw)
    {
        $this->raw = $raw;
    }

    public function render($retry = false)
    {
        preg_match('/{rss:start.*}.*{rss:end}/sim', $this->raw, $matched);
        $allrss = $r[0];

        // blocks like (without end) ['{RSS:START}.....', '{RSS:START}.....' ]
        $blocks = preg_split('/{RSS:END}/i', $allrss);
        foreach($blocks as $block) {
            $this->process($block);
        }

    }

    public function process($block)
    {

    }

    private function getMeta($block)
    {
        preg_match('/{RSS:START[^\}]*}/i', $start, $matched);
        $header = $matched[0];

        $header = preg_replace('/{RSS:START\s*|}/i', '', $header);
        $header = trim($header);
        $metastr = preg_split('/\s+/', $header);
    }
}

$raw = '
{RSS:START URL=http://rss.cnn.com/rss/edition.rss}
<h1>{rss:title}</h1>
{rss:end}
'