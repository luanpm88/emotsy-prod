<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class AddUtmParameters implements StageInterface
{
    public $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function __invoke($html)
    {
        $content = StringHelper::transformUrls($html, function ($url, $element) {
            // If the given URL is a non-web link, like tel:, mailto:, ftp:...
            // then just skip
            if (\Acelle\Helpers\is_non_web_link($url)) {
                return $url;
            }

            if (parse_url($url, PHP_URL_HOST) === false) {
                // false ==> if url is invalid
                // null ==> if url does not have host information
                return $url;
            }

            if (StringHelper::isTag($url)) {
                return $url;
            }

            if (strpos($url, 'data:') === 0) {
                // base64 image. Like: "data:image/png;base64,iVBOR"
                return $url;
            }

            if (!is_null(parse_url($url, PHP_URL_HOST))) {
                // url is with a host like "http://" or "//" thì:
                
                // translate url with utm
                // VD: http://acelle.com/p/aHR0cDovL2dvb2dsZS5jb20/click
                // ==> http://acelle.com/p/aHR0cDovL2dvb2dsZS5jb20/click?utm_campaign=6552d2892ab83&utm_medium=email&utm_source=acellemail&utm_term=asdasdasd&utm_content=link
                return \Acelle\Helpers\generateUtmLink($url, $this->parameters);
            }
        });

        return $content;
    }
}
