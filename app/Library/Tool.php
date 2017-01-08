<?php

/**
 * Tool class.
 *
 * Misc helper tool
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

namespace Acelle\Library;

class Tool
{
    /**
     * Copy a file, or recursively copy a folder and its contents.
     *
     * @param string $source      Source path
     * @param string $dest        Destination path
     * @param int    $permissions New folder creation permissions
     *
     * @return bool Returns true on success, false on failure
     */
    public static function xcopy($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            self::xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();

        return true;
    }

    /**
     * Delete a file, or recursively delete a folder and its contents.
     *
     * @param string $source Source path
     *
     * @return bool Returns true on success, false on failure
     */
    public static function xdelete($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (is_dir($dir.'/'.$object)) {
                        self::xdelete($dir.'/'.$object);
                    } else {
                        unlink($dir.'/'.$object);
                    }
                }
            }
            rmdir($dir);
        }

        return true;
    }

    /**
     * Get all time zone.
     *
     * @var array
     */
    public static function allTimeZones()
    {
        // Get all time zones with offset
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['text'] = '(GMT'.date('P', $timestamp).') '.$zones_array[$key]['zone'];
            $zones_array[$key]['order'] = str_replace('-', '1', str_replace('+', '2', date('P', $timestamp))).$zone;
        }

        // sort by offset
        usort($zones_array, function ($a, $b) {
            return strcmp($a['order'], $b['order']);
        });

        return $zones_array;
    }

    /**
     * Get options array for select box.
     *
     * @var array
     */
    public static function getTimezoneSelectOptions()
    {
        $arr = [];
        foreach (self::allTimeZones() as $timezone) {
            $row = ['value' => $timezone['zone'], 'text' => $timezone['text']];
            $arr[] = $row;
        }

        return $arr;
    }

    /**
     * Format display datetime.
     *
     * @var string
     */
    public static function formatDateTime($datetime)
    {
        $result = self::dateTime($datetime)->format(trans('messages.datetime_format'));

        return $result;
    }

    /**
     * Format display datetime.
     *
     * @var string
     */
    public static function dateTime($datetime)
    {
        $timezone = \Auth::user()->timezone;
        $result = $datetime;
        if (!empty($timezone)) {
            $result = $result->timezone($timezone);
        }

        return $result;
    }
    
    /**
     * Format display datetime.
     *
     * @var string
     */
    public static function dateTimeFromString($time_string)
    {        
        return self::dateTime(\Carbon\Carbon::parse($time_string));
    }

    /**
     * For mat human time.
     *
     * @param       DateTime
     *
     * @return string
     */
    public static function formatHumanTime($time)
    {
        return $time->diffForHumans();
    }

    /**
     * Change singular to plural.
     *
     * @param       string
     *
     * @return string
     */
    public static function getPluralPrase($phrase, $value)
    {
        $plural = '';
        if ($value > 1) {
            for ($i = 0; $i < strlen($phrase); ++$i) {
                if ($i == strlen($phrase) - 1) {
                    $plural .= ($phrase[$i] == 'y' && $phrase != 'day') ? 'ies' : (($phrase[$i] == 's' || $phrase[$i] == 'x' || $phrase[$i] == 'z' || $phrase[$i] == 'ch' || $phrase[$i] == 'sh') ? $phrase[$i].'es' : $phrase[$i].'s');
                } else {
                    $plural .= $phrase[$i];
                }
            }

            return $plural;
        }

        return $phrase;
    }

    /**
     * Get file/folder permissions.
     *
     * @param       string
     *
     * @return string
     */
    public static function getPerms($path)
    {
        return substr(sprintf('%o', fileperms($path)), -4);
    }
    
    /**
     * Get system time conversion.
     *
     * @param       string
     *
     * @return string
     */
    public static function systemTime($time)
    {
        return $time->setTimezone(config('app.timezone'));
    }
    
    /**
     * Get system time conversion.
     *
     * @param       string
     *
     * @return string
     */
    public static function systemTimeFromString($string, $timezone)
    {
        $time = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $string, $timezone);            
        $time = self::systemTime($time);
        return $time;
    }
    
    /**
     * Get bytes from string.
     *
     * @param string
     *
     * @return string
     */
    public static function returnBytes($val) {
        //$val = trim($val);
        //$last = strtolower($val[strlen($val)-1]);
        //switch($last) 
        //{
        //    case 'g':
        //    $val *= 1024;
        //    case 'm':
        //    $val *= 1024;
        //    case 'k':
        //    $val *= 1024;
        //}
        return $val;
    }
    
    /**
     * Get max upload file.
     *
     * @param string
     *
     * @return string
     */
    public static function maxFileUploadInBytes() {
        //select maximum upload size
        $max_upload = self::returnBytes(ini_get('upload_max_filesize'));
        //select post limit
        $max_post = self::returnBytes(ini_get('post_max_size'));
        //select memory limit
        $memory_limit = self::returnBytes(ini_get('memory_limit'));
        // return the smallest of them, this defines the real limit
        return min($max_upload, $max_post);
    }
    
    /**
     * Day of week select options.
     *
     * @param string
     *
     * @return array
     */
    public static function dayOfWeekSelectOptions() {
        return [
            ['value' => '1', 'text' => trans('messages.Monday')],
            ['value' => '2', 'text' => trans('messages.Tuesday')],
            ['value' => '3', 'text' => trans('messages.Wednesday')],
            ['value' => '4', 'text' => trans('messages.Thursday')],
            ['value' => '5', 'text' => trans('messages.Friday')],
            ['value' => '6', 'text' => trans('messages.Saturday')],
            ['value' => '7', 'text' => trans('messages.Sunday')],
        ];
    }
    
    /**
     * Day of week arrays.
     *
     * @param string
     *
     * @return array
     */
    public static function weekdaysArray() {
        $array = [];
        foreach(self::dayOfWeekSelectOptions() as $day) {
            $array[$day['value']] = $day['text'];
        }
        
        return $array;
    }
    
    /**
     * Month select options.
     *
     * @param string
     *
     * @return array
     */
    public static function monthSelectOptions() {
        return [
            ['value' => '1', 'text' => trans('messages.January')],
            ['value' => '2', 'text' => trans('messages.February')],
            ['value' => '3', 'text' => trans('messages.March')],
            ['value' => '4', 'text' => trans('messages.April')],
            ['value' => '5', 'text' => trans('messages.May')],
            ['value' => '6', 'text' => trans('messages.June')],
            ['value' => '7', 'text' => trans('messages.July')],
            ['value' => '8', 'text' => trans('messages.August')],
            ['value' => '9', 'text' => trans('messages.September')],
            ['value' => '10', 'text' => trans('messages.October')],
            ['value' => '11', 'text' => trans('messages.November')],
            ['value' => '12', 'text' => trans('messages.December')],
        ];
    }    
    
    /**
     * Month array.
     *
     * @param string
     *
     * @return array
     */
    public static function monthsArray() {
        $array = [];
        foreach(self::monthSelectOptions() as $day) {
            $array[$day['value']] = $day['text'];
        }
        
        return $array;
    }
    
    /**
     * Week select options.
     *
     * @param string
     *
     * @return array
     */
    public static function weekSelectOptions() {
        return [
            ['value' => '1', 'text' => trans('messages.1st_week')],
            ['value' => '2', 'text' => trans('messages.2nd_week')],
            ['value' => '3', 'text' => trans('messages.3rd_week')],
            ['value' => '4', 'text' => trans('messages.4th_week')],
            ['value' => '5', 'text' => trans('messages.5th_week')],
        ];
    }
    
    /**
     * Week array.
     *
     * @param string
     *
     * @return array
     */
    public static function weeksArray() {
        $array = [];
        foreach(self::weekSelectOptions() as $day) {
            $array[$day['value']] = $day['text'];
        }
        
        return $array;
    }
    
    /**
     * Month select options.
     *
     * @param string
     *
     * @return array
     */
    public static function dayOfMonthSelectOptions() {
        $arr = [];
        for($i=1; $i < 32; $i++) {
            $arr[] = ['value' => $i, 'text' => $i];
        }
        return $arr;
    }
    
    /**
     * Get day string from timestamp.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function dayStringFromTimestamp($timestamp) {
        if(isset($timestamp) && $timestamp != '0000-00-00 00:00:00') {
            // @todo: hard day format code: 'Y-m-d'
            $result = \Acelle\Library\Tool::dateTime($timestamp)->format('Y-m-d');
        } else {
            $result = \Acelle\Library\Tool::dateTime(\Carbon\Carbon::now())->format('Y-m-d');
        }        
        return $result;
    }
    
    /**
     * Get time string from timestamp.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function timeStringFromTimestamp($timestamp) {
        if(isset($timestamp) && $timestamp != '0000-00-00 00:00:00') {
            // @todo: hard day format code: 'H:i'
            $result = \Acelle\Library\Tool::dateTime($timestamp)->format('H:i');
        } else {
            $result = \Acelle\Library\Tool::dateTime(\Carbon\Carbon::now())->format('H:i');
        }        
        return $result;
    }
    
    /**
     * Convert numbers array to weekdays array.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function numberArrayToWeekdaysArray($numbers) {
        $weekdays_texts = self::weekdaysArray();
        $weekdays = [];
        foreach($numbers as $number) {
            $weekdays[] = $weekdays_texts[$number];
        }
        
        return $weekdays;
    }
    
    /**
     * Convert numbers array to weeks array.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function numberArrayToWeeksArray($numbers) {
        $weeks_texts = self::weeksArray();
        $weeks = [];
        foreach($numbers as $number) {
            $weeks[] = $weeks_texts[$number];
        }
        
        return $weeks;
    }
    
    /**
     * Convert numbers array to months array.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function numberArrayToMonthsArray($numbers) {
        $month_texts = self::monthsArray();
        $months = [];
        foreach($numbers as $number) {
            $months[] = $month_texts[$number];
        }
        
        return $months;
    }
    
    /**
     * Get day names from array of numbers.
     *
     * @param timestamp
     *
     * @return string
     */
    public static function getDayNamesFromArrayOfNumber($numbers) {
        $names = [];
        
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        foreach($numbers as $number) {            
            if (($number %100) >= 11 && ($number%100) <= 13)
               $names[] = $number. 'th';
            else
               $names[] = $number. $ends[$number % 10];
        }
        
        return $names;
    }
}
