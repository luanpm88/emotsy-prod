<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class AddUtmParameters implements StageInterface
{
    public function __invoke($html)
    {
        $content = StringHelper::transformUrls($html, function ($url, $element) {
        	return "XXXXXXXXXXXXX";

            // If the given URL is a non-web link, like tel:, mailto:, ftp:...
            // then just skip
            if (is_non_web_link($url)) {
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

            if (!is_null(parse_url($url, PHP_URL_HOST))) {
                // url is with a host like "http://" or "//"
                if (!is_null($urlTransform)) {
                    // Make trackable links like. For example:
                    // StringHelper::makeTrackableLink('http://google.com', 'MSGID')
                    // ==> https://localhost/p/aHR0cDovL2dvb2dsZS5jb20/click/TVNHSUQ
                    $url = $urlTransform($url, $element);
                }

                // The next step is to transform it again to a tracking URL like
                // If tracking domain is of type HOST
                // ==> https://tracking.com/aHR0cHM6Ly9sb2NhbGhvc3QvcC9hSFIwY0RvdkwyZHZiMmRzWlM1amIyMC9jbGljay9UVk5IU1VR
                // Or, if tracking domain is of type DNS
                // ==> https://tracking.com/p/aHR0cDovL2dvb2dsZS5jb20/click/TVNHSUQ
                if ($domain) {
                    $url = $domain->buildTrackingUrl($url);
                }

                return $url;
            }

            if (strpos($url, '/') === 0) {
                // absolute url with leading slash (/) like "/hello/world"

                $urlWithHost = join_url(getAppHost(), $url);
                if (!is_null($urlTransform)) {
                    $urlWithHost = $urlTransform($urlWithHost, $element);
                }

                if ($domain) {
                    return $domain->buildTrackingUrl($urlWithHost);
                } elseif ($withHost) {
                    return $urlWithHost;
                } else {
                    return $url;
                }
            } elseif (strpos($url, 'data:') === 0) {
                // base64 image. Like: "data:image/png;base64,iVBOR"
                return $url;
            } else {
                // URL is a relative path like "images/banner.jpg"
                // Transform relative URLs to PUBLIC ABSOLUTE URLs with leading slash /
                $url = \Acelle\Helpers\generatePublicPath(
                    $this->getStoragePath($url),
                    $absolute = ($withHost) ? true : false
                );

                if (!is_null($urlTransform)) {
                    $url = $urlTransform($url, $element);
                }

                if ($domain) {
                    return $domain->buildTrackingUrl($url);
                } else {
                    return $url;
                }
            }
        });

        return $content;
    }
}
