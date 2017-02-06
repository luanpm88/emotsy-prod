<?php

/**
 * Globally available helper methods
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
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


/**
 * Get full table name by adding the DB prefix
 *
 * @param string table name
 * @return string fulle table name with prefix
 */
function table($name)
{
    return \DB::getTablePrefix() . $name;
}

/**
 * Quote a value with astrophe to inject to an SQL statement
 *
 * @param string original value
 * @return string quoted value
 * @todo: use MySQL escape function to correctly escape string with astrophe
 */
function quote($value)
{
    return "'$value'";
}

/**
 * Quote a value with astrophe to inject to an SQL statement
 *
 * @param string original value
 * @return string quoted value
 * @todo: use MySQL escape function to correctly escape string with astrophe
 */
function db_quote($value)
{
    return "'$value'";
}

/**
 * Break an array into smaller batches (arrays)
 *
 * @param array original array
 * @param int batch size
 * @param bool whether or not to skip the first header line
 * @param callback function
 */
function each_batch($array, $batchSize, $skipHeader, $callback)
{
    $batch = [];
    foreach($array as $i => $value) {
        // skip the header
        if ($i == 0 && $skipHeader) {
            continue;
        }

        if ($i % $batchSize == 0) {
            $callback($batch);
            $batch = [];
        }
        $batch[] = $value;
    }
    
    // the last callback
    if (sizeof($batch) > 0) {
        $callback($batch);
    }
}

/**
 * Count the total number of line of a given file without loading the entire file.
 * This is effective for large file
 *
 * @param string file path
 * @return int line count
 */
function line_count($path)
{
    $file = new \SplFileObject($path, 'r');
    $file->seek(PHP_INT_MAX);
    return $file->key() + 1;
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
 * Get unique array based on user defined condition
 *
 * @param array original array
 * @return array unique array
 */
function array_unique_by($array, $callback)
{
    $result = [];
    foreach($array as $value) {
        $key = $callback($value);
        $result[$key] = $value;
    }
    return array_values($result);
}

/**
 * Get UTC offset of a particular time zone
 *
 * @param string timezone
 * @return string UTC offset (+02:00 for example)
 */
function utc_offset($timezone)
{
    $offset = \Carbon\Carbon::now($timezone)->offsetHours - \Carbon\Carbon::now('UTC')->offsetHours;
    return sprintf("%+'03d:00", $offset);
}

/**
 * Get Database UTC offset
 *
 * @return string UTC offset (+02:00 for example)
 */
function db_utc_offset()
{
    $zone = DB::select("SELECT TIME_FORMAT(TIMEDIFF(NOW(), UTC_TIMESTAMP), '%H:%i') AS zone")[0]->zone;

    if (!preg_match('/^-/', $zone)) {
        $zone = "+$zone";
    }

    return $zone;
}

/**
 * Check if exec() function is available
 *
 * @return boolean
 */
function exec_enabled() {
   return function_exists('exec') && !in_array('exec', array_map('trim', explode(', ', ini_get('disable_functions'))));
}

/**
 * Run artisan config cache
 *
 * @return boolean
 */
function artisan_config_cache() {
    $file = base_path('bootstrap/cache/config.php');
    $start_time = filemtime($file);
    
    $time_out = 5;
    $init_time = time();
    
    \Artisan::call('config:cache');
    while($start_time == filemtime($file) && time() - $init_time <= $time_out) {}
    
    sleep(5);
    
    return $start_time - filemtime($file);
}

/**
 * Run artisan migrate
 *
 * @return boolean
 */
function artisan_migrate() {
    \Artisan::call('migrate', ["--force"=> true]);
}