<?php

/**
 * QuotaTrackerFile class.
 *
 * Provide a data structure for storing and measure quota
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
 * @todo separate the time-series and the quota stuffs
 */

namespace Acelle\Library;
use Carbon\Carbon;

class QuotaTrackerFile extends QuotaTracker implements QuotaTrackerInterface
{
    protected $file;
    protected $reader;
    
    /**
     * Constructor, modeling data from a JSON array 
     *
     * @param Array $series
     *
     * @return void
     */
    public function __construct($file, $interval, $quota)
    {
        parent::__construct($interval, $quota);
        $this->file = $file;
        
        // init an empty file if not exist
        touch($this->file);
    }
    
    /**
     * Check if over quota, add a time point
     *
     * @param Timestamps $timePoint
     *
     * @return void
     */
    public function add(Carbon $timePoint = null) {
        $this->transaction(function() use ($timePoint, &$result) {
            if (!isset($timePoint)) {
                $timePoint = Carbon::now();
            }
    
            if ($this->doCheck($timePoint)) {
                $this->series[] = $timePoint->timestamp;
                $result = true;
            } else {
                $result = false;
            }
        });
        
        return $result;
    }
    
    /**
     * Check if over quota, add a time point
     *
     * @param Timestamps $timePoint
     *
     * @return void
     */
    public function check(Carbon $timePoint = null) {
        $this->transaction(function() use ($timePoint, &$result) {
            $result = $this->doCheck($timePoint);
        });
        
        return $result;
    }

    /**
     * Check if over quota
     *
     * @param Timestamps $timePoint
     *
     * @return void
     */
    public function doCheck(Carbon $timePoint = null) {
        if (!isset($timePoint)) {
            $timePoint = Carbon::now();
        }

        $this->shiftBy($timePoint);
        
        $result = (self::UNLIMITED == $this->quota || $this->usage() < $this->quota);
        return $result;
    }
    
    /**
     * Shift the time series until its range fits the new time point
     *
     * @param Timestamps $timePoint
     *
     * @return void
     */
    private function shiftBy(Carbon $timePoint)
    {
        if (empty($this->series)) {
            return;
        }
        
        $cutOff = $timePoint->copy()->sub($this->interval)->timestamp;
        while($this->first() != null && $this->first() < $cutOff ) {
            array_shift($this->series);
        }
    }

    /**
     * Renew the quota data for the tracker
     * @note this method must be wrapped in a transaction
     * 
     * @param Array $series
     * @return void
     */
    public function renew($series)
    {
        $this->transaction(function() use ($series) {
            $this->series = $series;
        });
    }

    /**
     * Get the first data point of the time series
     *
     * @return Mixed data point
     */
    public function first()
    {
        return (empty($this->series)) ? null : $this->series[self::START_POINT];
    }

    /**
     * Get the last data point of the time series
     *
     * @return Mixed data point
     */
    public function last()
    {
        return (empty($this->series)) ? null : $this->series[sizeof($this->series) - 1];
    }

    /**
     * Count the time series length
     *
     * @return Integer count
     */
    public function usage()
    {
        return sizeof($this->series);
    }

    /**
     * Get usage percentage
     *
     * @return float
     */
    public function usagePercentage()
    {
        return (float) $this->usage() / $this->quota;
    }

    /**
     * Get the data series
     *
     * @return Array data series
     */
    public function getSeries()
    {
        return $this->series = array_map('intval', array_values(array_filter(explode(',', file_get_contents($this->file)))));
    }

    public function showSeries() {
        echo "\n------------------------------------------\n";
        echo "Now: *" . Carbon::now()->formatLocalized("%H:%M:%S") . "*\n";
        foreach($this->series as $t) {
            echo Carbon::createFromTimestamp($t)->formatLocalized("%H:%M:%S") . " | "; 
        }
        echo "\n------------------------------------------\n";
    }
    
    public function transaction($callback)
    {
        $this->reader = fopen($this->file, "r+");
        
        $start = time();
        $timeout = 60;
        while (true) {
            // if timed out
            if (time() - $start > $timeout) {
                fclose($this->reader);
                throw new \Exception("Timeout getting file lock: {$this->file}");
            }
            
            if (flock($this->reader, LOCK_EX | LOCK_NB)) {  // acquire an exclusive lock                
                // retrieve the series data
                $this->series = $this->getSeries();
                
                // execute the callback
                $callback();
                
                ftruncate($this->reader, 0);
                fwrite($this->reader, implode(",", $this->series));
                fflush($this->reader);
                flock($this->reader, LOCK_UN);    // release the lock
                fclose($this->reader);
                break;
            } else {
                // waiting to for the lock
            }
        }
    }
}
