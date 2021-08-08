<?php

namespace Acelle\Library;

use File;
use Carbon\Carbon;
use Exception;

class QuotaTracker
{
    protected $filepath;
    protected $mode = 'minute'; // hour, day, month, year
    protected $seperator = ':';
    protected $blockFormat = [
        'minute' => 'YmdHi',
        'hour' => 'YmdH00',
        'day' => 'Ymd0000',
        'month' => 'Ym000000',
        'year' => 'Y00000000',
    ];

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
        $this->createStorageFile();
    }

    private function createStorageFile()
    {
        if (!file_exists($this->filepath)) {
            touch($this->filepath);
        }
    }

    public function check(Carbon $now = null, array $limits = [])
    {
        $lock = new Lockable($this->filepath);
        $lock->getExclusiveLock(function ($fopen) use ($limits) {
            $now = $now ?: Carbon::now();

            $this->test($now, $limits, $fopen);
            $this->count($now, $fopen);
        }, $timeout = 15);
    }

    public function count(Carbon $now, $fopen)
    {
        $currentBlock = $this->makeBlock($now); // create block for the current date/time
        list($lastBlock, $count) = $this->parseLastRecord($fopen);

        // EMPTY() is safer than IS_NULL()
        if ($currentBlock == $lastBlock) {
            $record = $this->buildRecord($lastBlock, $count + 1);
            $this->updateRecord($record, $fopen);
        } else {
            $record = $this->buildRecord($currentBlock, $count = 1);
            $this->addRecord($record, $fopen);
        }
    }

    private function parseLastRecord($fopen)
    {
        $lastRecord = $this->getLastRecord($fopen);
        return $this->parseBlock($lastRecord);
    }

    private function parseBlock(string $record)
    {
        if (empty($record)) {
            return null;
        }

        return explode($this->seperator, $record);
    }

    public function buildRecord($block, $count)
    {
        return "{$block}{$this->seperator}{$count}";
    }

    // Convert the provided datetime $now to a string
    public function makeBlock($now)
    {
        $now = $now ?: Carbon::now();
        $format = $this->blockFormat[$this->mode];
        return $now->format($format);
    }

    private function getLastRecord($fopen)
    {
        // Find offline
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            return ""; // File empty
        }

        fseek($fopen, $offset--); //seek to the end of the line

        // Ignore consecutive empty newlines
        $char = fgetc($fopen);
        while ($offset >= 0 && ($char === "\n")) {
            fseek($fopen, $offset--);
            $char = fgetc($fopen);
        }

        if ($offset < 0) {
            fseek($fopen, 0);
            return trim(fgets($fopen)); // the whole file has Zero or One character (except \n)
        }

        // Continue with offset $offset;
        fseek($fopen, $offset--);
        $char = fgetc($fopen);
        while ($offset >= 0 && $char != "\n") {
            fseek($fopen, $offset--);
            $char = fgetc($fopen);
        }

        if ($offset < 0) { // get to the beginning of file
            fseek($fopen, 0);
        }

        $lastLine = fgets($fopen);
        return trim($lastLine);
    }

    public function updateRecord(string $record, $fopen)
    {
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            return ""; // File empty
        }

        fseek($fopen, $offset--); //seek to the end of the line

        // Ignore consecutive empty newlines
        $char = fgetc($fopen);
        while ($offset >= 0 && ($char === "\n")) {
            fseek($fopen, $offset--);
            $char = fgetc($fopen);
        }

        if ($offset < 0) {
            fseek($fopen, 0); // either a leading newline or leading newline + 1char, overwrite leading "\nX" if any
        }

        // Continue with offset $offset;
        fseek($fopen, $offset);
        $char = fgetc($fopen);
        while ($offset >= 0 && $char != "\n") {
            $offset -= 1;
            fseek($fopen, $offset);
            $char = fgetc($fopen);
        }

        if ($offset < 0) { // get to the beginning of file
            fseek($fopen, 0);
        }

        fwrite($fopen, $record);
    }

    public function truncate()
    {
        $fopen = fopen($this->filepath, 'r+');
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            return; // File empty
        }

        fseek($fopen, $offset); //seek to the end of the line
        $char = fgetc($fopen);
        while ($offset > 0 && ($char === "\n")) {
            $offset -= 1;
            fseek($fopen, $offset);
            $char = fgetc($fopen);
        }

        ftruncate($fopen, ++$offset);
        fclose($fopen);
    }

    public function addRecord(string $record, $fopen)
    {
        fseek($fopen, 0, SEEK_END);
        $offset = ftell($fopen) - 1; // Offset values from: -1, 0, 1, 2...

        if ($offset < 0) {
            fwrite($fopen, $record);
        } else {
            fseek($fopen, $offset); //seek to the end of the line
            $char = fgetc($fopen);
            while ($offset > 0 && ($char === "\n")) {
                $offset -= 1;
                fseek($fopen, $offset);
                $char = fgetc($fopen);
            }

            fseek($fopen, ++$offset);
            fwrite($fopen, "\n".$record);
        }
    }

    public function test(Carbon $now, array $limits, $fopen)
    {
        // [
        //     [
        //         'name' => 'Emails per minute',
        //         'period_base' => 'minute'
        //         'period_value' => '1'
        //         'limit' => 10,
        //     ], [
        //         'name' => 'Emails per 12 hours',
        //         'period_base' => 'hour',
        //         'period_value' => '12',
        //         'limit' => 2000
        //     ], [
        //         ....
        //     ]
        // ]
        //

        foreach ($limits as $limit) {
            $period = sprintf("%s %s", $limit['period_value'], $limit['period_base']);
            $fromDatetime = $now->copy()->subtract($period);

            $creditsUsed = $this->getCreditsUsed($fromDatetime, $now);

            if ($creditsUsed >= $limit['limit']) {
                throw new Exception(sprintf("%s exceeded! %s/%s used", $limit['name'], $creditsUsed, $limit['limit']));
            }
        }
    }

    public function getRecords(Carbon $fromDatetime, Carbon $toDatetime, $fopen)
    {
        $fromDatetimeStr = $this->makeBlock($fromDatetime);
        $toDatetimeStr = $this->makeBlock($toDatetime);

        echo "From: $fromDatetimeStr ==> $toDatetimeStr\n";

        $records = [];
        $file = fopen($this->filepath, 'r');
        while (!feof($file)) {
            $record = trim(fgets($file));
            list($block, $count) = $this->parseBlock($record);

            if (empty($block)) {
                throw new Exception("Invalid block {$record}");
            }

            if ($block > $fromDatetimeStr && $block <= $toDatetimeStr) {
                $records[] = [$block, $count];
            }
        }

        // Close file
        fclose($file);

        // Return
        return $records;
    }

    public function getCreditsUsed(Carbon $fromDatetime, Carbon $toDatetime)
    {
        $records = $this->getRecords($fromDatetime, $toDatetime);
        $counts = array_map(function ($record) {
            list($block, $count) = $record;
            return $count;
        }, $records);

        $total = array_sum($counts);
        return $total;
    }
}
