<?php

/**
 * UpgradeManager class.
 *
 * Tool for upgrading the entire system source
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   Acelle Library
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Library\UpgradeManager;

function join_paths()
{
    $paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') { $paths[] = $arg; }
    }

    return preg_replace('#/+#','/',join('/', $paths));
}

class UpgradeManager
{
    protected $source;
    protected $target;
    protected $meta;

    /**
     * Constructor, specify the source, target and load the meta information
     *
     */
    function __construct($source, $target)
    {
        $this->source = $source;
        $this->target = $target;
        $this->meta = $this->getMetaInfo();
    }

    /**
     * Join filesystem path strings
     *
     * @param * parts of the path
     * @return string a full path
     */
    function join_paths()
    {
        $paths = array();
        foreach (func_get_args() as $arg) {
            if ($arg !== '') { $paths[] = $arg; }
        }

        return preg_replace('#/+#','/',join('/', $paths));
    }

    /**
     * Read the meta data from a patch package
     *
     */
    private function getMetaInfo()
    {
        $path = join_paths($this->source, "meta.json");
        if (!file_exists($path)) {
            throw new \Exception("Cannot find meta file: {$path}");
        }

        return json_decode(file_get_contents($path), true);
    }

    /**
     * Actually run the upgrade process
     *
     */
    public function run()
    {
        $errors = [];
        $updates = $this->meta['updated'];
        $deletes = $this->meta['deleted'];
        $packages = $this->meta['packages'];

        foreach($updates as $file) {
            $source = join_paths($this->source, $file);
            $target = join_paths($this->target, $file);
            if (!copy($source, $target)) {
                throw new \Exception("Cannot replace file {$target}");
            }
        }

        foreach($deletes as $file) {
            $target = join_paths($this->target, $file);
            @unlink($target);
        }

        foreach($packages as $dir) {
            $source = join_paths($this->source, 'vendor', $dir);
            $target = join_paths($this->target, 'vendor', $dir);
            $this->rm($target);

            $this->copy($source, $target);
        }
    }

    /**
     * Test the upgrade process (DRYRUN)
     *
     */
    public function test()
    {
        $errors = [];
        $updates = $this->meta['updated'];
        $deletes = $this->meta['deleted'];
        $packages = $this->meta['packages'];

        foreach ($updates as $file) {
            $path = join_paths($this->target, $file);
            if (!$this->isWritable($path)) {
                $errors[] = $path;
            }
        }

        foreach ($deletes as $file) {
            $path = join_paths($this->target, $file);
            if (!$this->isWritable($path)) {
                $errors[] = $path;
            }
        }

        foreach ($packages as $dir) {
            $path = join_paths($this->target, 'vendor', $dir);
            if (!$this->isWritable($path)) {
                $errors[] = $path;
            }
        }

        return $errors;
    }

    /**
     * Check if an existing file is writable or a new path can be created
     *
     * @input string file path
     * @output boolean
     */
    private function isWritable($path)
    {
        if (is_writable($path)) {
            return true;
        } elseif (!file_exists($path) && $this->canCreateFile($path)) {
            return true;
        } else {
            // file exists but not writable
            // file not exist nor creatable
            return false;
        }
    }

    private function canCreateFile($path)
    {
        $a = explode(DIRECTORY_SEPARATOR, $path);
        $parent = null;
        for ($i = 0; $i < sizeof($a); $i += 1) {
            $tmppath = implode(DIRECTORY_SEPARATOR, array_slice($a, 0, $i));

            if (strpos("/" . $tmppath . "/", $this->target) === false) {
                continue;
            }

            if (!file_exists($tmppath)) {
                break;
            } else {
                $parent = $tmppath;
            }
        }

        return is_writable($parent);
    }

    private function rm($src)
    {
        if (!file_exists($src)) {
            return;
        }

        if (!is_dir($src)) {
            unlink($src);
            return;
        }

        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rm($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    private function copy($src, $dst)
    {
        if (!is_dir($src)) {
            copy($src, $dst);
            return;
        }

        $dir = opendir($src);
        mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir(join_paths($src, $file)) ) {
                    $this->copy(join_paths($src,  $file), join_paths($dst, $file));
                } else {
                    copy(join_paths($src, $file), join_paths($dst,  $file));
                }
            }
        }
        closedir($dir);
    }
}

$manager = new UpgradeManager('/tmp/acellemail-3.0.0/upgrade/patch-v3.0.0-p3', '/tmp/oldme/');
$result = $manager->test();
var_dump($result);
