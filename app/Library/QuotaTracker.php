<?php

/**
 * QuotaTracker class.
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
 */

namespace Acelle\Library;

class QuotaTracker
{
    protected $series; 
    protected $range;
    protected $quota; // in seconds

    const START_POINT = 0;

    /**
     * Constructor, modeling data from a JSON array 
     *
     * @param Array $series
     *
     * @return void
     */
    public function __construct($range, $quota, $series = [])
    {
        $this->series = $series;
        $this->range = $range;
        $this->quota = $quota;
    }

    /**
     * Check if over quota
     *
     * @param Timestamps $timePoint
     *
     * @return void
     */
    public function add($timePoint) {
        $this->shiftBy($timePoint);
        if ($this->count() >= $this->quota) {
            return false;
        } else {
            $this->series[] = $timePoint;
            return true;
        }
    }
    
    /**
     * Shift the time series until its range fits the new time point
     *
     * @param Timestamps $timePoint
     *
     * @return void
     */
    private function shiftBy($timePoint)
    {
        if (empty($this->series)) {
            return;
        }
        
        $cutOff = $timePoint - $this->range;
        while($this->first() < $cutOff ) {
            array_shift($this->series);
        }
    }

    /**
     * Get the first data point of the time series
     *
     * @return Mixed data point
     */
    public function first()
    {
        return (empty($this->series)) ? NULL : $this->series[self::START_POINT];
    }

    /**
     * Count the time series length
     *
     * @return Integer count
     */
    public function count()
    {
        return sizeof($this->series);
    }
}
