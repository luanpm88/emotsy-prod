<?php

namespace Mailixa\Library;

class Tool
{
    /**
    * Copy a file, or recursively copy a folder and its contents
    * @param       string   $source    Source path
    * @param       string   $dest      Destination path
    * @param       int      $permissions New folder creation permissions
    * @return      bool     Returns true on success, false on failure
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
            Tool::xcopy("$source/$entry", "$dest/$entry", $permissions);
        }
   
        // Clean up
        $dir->close();
        return true;
    }
   
    /**
     * Delete a file, or recursively delete a folder and its contents
     * @param       string   $source    Source path
     * @return      bool     Returns true on success, false on failure
     */
    public static function xdelete($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object)) {
                        Tool::xdelete($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
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
            $zones_array[$key]['text'] = "(GMT" . date('P', $timestamp) . ") " . $zones_array[$key]['zone'];
            $zones_array[$key]['order'] = str_replace("-", "1", str_replace("+", "2", date('P', $timestamp))).$zone;
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
        foreach(Tool::allTimeZones() as $timezone) {
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
    public static function formatDateTime($datetime) {
        $result = Tool::dateTime($datetime)->format(trans('messages.datetime_format'));
        
        return $result;
    }
    
    /**
      * Format display datetime.
      *
      * @var string
      */
    public static function dateTime($datetime) {
        $timezone = \Auth::user()->timezone;
        $result = $datetime;
        if (!empty($timezone)) {
            $result = $result->timezone($timezone);
        }       
        return $result;
   }
}
