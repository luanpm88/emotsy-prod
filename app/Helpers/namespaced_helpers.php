<?php

namespace Acelle\Helpers;

use Acelle\Library\StringHelper;
use Exception;

function generatePublicPath($absPath)
{
    // Notice: $relativePath must be relative to storage/ folder
    // For example, with a real path of /home/deploy/acellemail/storage/app/sub/example.png
    // then $relativePath should be "app/sub/example.png"

    $excludeBase = storage_path();
    $pos = strpos($absPath, $excludeBase); // Expect pos to be exactly 0

    if ($pos === false) {
        throw new Exception(sprintf("File '%s' cannot be made public", $absPath));
    }

    if ($pos != 0) {
        throw new Exception(sprintf("Invalid path '%s', cannot make it public", $absPath));
    }

    $relativePath = substr($absPath, strlen($excludeBase));
    $encodedUrl = StringHelper::base64UrlEncode($relativePath);

    return route('public_assets', [ 'path' => $encodedUrl ]);
}
