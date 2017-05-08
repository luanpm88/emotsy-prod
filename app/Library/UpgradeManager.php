<?php

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

    private function getMetaInfo()
    {
        $path = join_paths($this->source, "meta.json");
        if (!file_exists($path)) {
            throw new \Exception("Cannot find meta file: {$path}");
        }

        return json_decode(file_get_contents($path), true);
    }

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

        foreach ($updates as $file) {
            $path = join_paths($this->target, $file);
            if (!$this->isWritable($path)) {
                $errors[] = $path;
            }
        }

        foreach ($packages as $dir) {
            $path = join_paths($this->target, 'vendor', $dir);
            echo "$path\n";
            if (!$this->isWritable($path)) {
                $errors[] = $path;
            }
        }

        return $errors;
    }

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
}

$manager = new UpgradeManager('/tmp/acellemail-3.0.0/upgrade/patch-v3.0.0-p3', '/tmp/oldme/');
$result = $manager->test();
var_dump($result);
