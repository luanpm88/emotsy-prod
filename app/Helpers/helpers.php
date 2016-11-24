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
function each_batch($array, int $batchSize, bool $skipHeader, $callback)
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
 * Get message for status of importing job
 *
 * @param object data
 * @return string
 */
function importSubscriberMessage($data, $system_job)
{
    if($data->status == 'failed') {
        $message = '<span class="text-danger">' . trans('messages.Failed') . ': ' . $data->error_message . '</span>';
    } else if ($data->status == 'new' && $system_job->status != 'cancelled') {
        $message = trans('messages.starting');
    } else {
        $message = trans('messages.import_export_statistics_line', [
            'total' => $data->total,
            'processed' => $data->processed,
            'success' => $data->processed,
            'error' => 0,
        ]);;
    }
    
    return $message;
}

/**
 * Get message for status of export job
 *
 * @param object data
 * @return string
 */
function exportSubscriberMessage($data, $system_job)
{
    if($system_job->status == 'cancelled') {
        return trans('messages.cancelled');
    }
    return $data->message;
}