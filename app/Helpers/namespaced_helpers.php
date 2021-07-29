<?php

namespace Acelle\Helpers;

use Acelle\Library\StringHelper;
use Exception;
use File;

function generatePublicPath($absPath, $withHost = false)
{
    // Notice: $relativePath must be relative to storage/ folder
    // For example, with a real path of /home/deploy/acellemail/storage/app/sub/example.png
    // then $relativePath should be "app/sub/example.png"

    if (empty(trim($absPath))) {
        throw new Exception('Empty path');
    }

    $excludeBase = storage_path();
    $pos = strpos($absPath, $excludeBase); // Expect pos to be exactly 0

    if ($pos === false) {
        throw new Exception(sprintf("File '%s' cannot be made public, only files under storage/ folder can", $absPath));
    }

    if ($pos != 0) {
        throw new Exception(sprintf("Invalid path '%s', cannot make it public", $absPath));
    }

    // Do not use string replace, as path parts may occur more than once
    // For example: abc/xyz/abc/xyz...
    $relativePath = substr($absPath, strlen($excludeBase) + 1);

    if ($relativePath === false) {
        throw new Exception("Invalid path {$absPath}");
    }

    $dirname = dirname($relativePath);
    $basename = basename($relativePath);
    $encodedDirname = StringHelper::base64UrlEncode($dirname);

    // If Laravel is under a subdirectory
    $subdirectory = getAppSubdirectory();

    if (empty($subdirectory) || $absolute) {
        // Return something like
        //     "http://localhost/{subdirectory if any}/p/assets/ef99238abc92f43e038efb"   # absolute = true, OR
        //     "/p/assets/ef99238abc92f43e038efb"                   # absolute = false
        $url = route('public_assets', [ 'dirname' => $encodedDirname, 'basename' => $basename ], $withHost);
    } else {
        $url = join_paths($subdirectory, route('public_assets', [ 'dirname' => $encodedDirname, 'basename' => $basename ], $absolute));
    }

    return $url;
}

function getAppSubdirectory()
{
    // IMPORTANT: do not use url('/') as it will not work correctly
    // when calling from another file (like filemanager/config/config.php for example)
    // Otherwise, it will always return 'http://localhost' --> without subdirectory
    $appUrlInfo = parse_url(config('app.url'));
    $subdirectory = array_key_exists('path', $appUrlInfo) ? $appUrlInfo['path'] : null;
    return $subdirectory;
}

function updateTranslationFile($targetFile, $sourceFile, $overwriteTargetPhrases = false)
{
    $source = include $sourceFile;
    $target = include $targetFile;

    if ($overwriteTargetPhrases) {
        // Overwrite $target
        $merged = $source + $target;
    } else {
        // Respect $target
        $merged = $target + $source;
    }

    // Find keys in $target that are that not available in $source
    $diff = array_diff_key($target, $source);

    // Delete those keys in the final result
    $merged = array_diff_key($merged, $diff);

    $out = '<?php return '.var_export(\Yaml::parse(\Yaml::dump($merged)), true).' ?>';
    \File::put($targetFile, $out);
}

// Copy and:
// + Remove the destination first
// + Create parent folders if not exist
function pcopy($src, $dst)
{
    if (!File::exists($src)) {
        throw new Exception("File `{$src}` does not exist");
    }

    if (File::exists($dst)) {
        // Delete the file or link or directory
        if (is_link($dst) || is_file($dst)) {
            File::delete($dst);
        } else {
            File::deleteDirectory($dst);
        }
    } else {
        // Make sure the PARENT directory exists
        $dirname = pathinfo($dst)['dirname'];
        if (!File::exists($dirname)) {
            File::makeDirectory($dirname, 0777, true, true);
        }
    }

    // if source is a file, just copy it
    if (File::isFile($src)) {
        File::copy($src, $dst);
    } else {
        File::copyDirectory($src, $dst);
    }
}
