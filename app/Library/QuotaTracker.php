<?php

namespace Acelle\Library;

use File;
use Carbon\Carbon;

class QuotaTracker
{
    protected $filepath;
    protected $mode = 'minute'; // hour, day, month, year
    protected $seperator = ':';
    protected $blockFormat = [
        'minute' => 'Ymdhi',
        'hour' => 'Ymdh00',
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

    public function count($now = null)
    {
        $lock = new Lockable($this->filepath);
        $lock->getExclusiveLock(function ($fopen) use ($now) {
            $currentBlock = $this->newBlock($now);
            list($lastBlock, $count) = $this->parseLastRecord($fopen);

            var_dump($currentBlock);
            var_dump($lastBlock);
            // EMPTY() is safer than IS_NULL()
            if ($currentBlock == $lastBlock) {
                echo "update\n";
                $record = $this->buildRecord($lastBlock, $count + 1);
                $this->updateRecord($record, $fopen);
            } else {
                echo "add\n";
                $record = $this->buildRecord($currentBlock, $count = 1);
                $this->addRecord($record, $fopen);
            }
        }, $timeout = 5);
    }

    private function parseLastRecord($fopen)
    {
        $lastRecord = $this->getLastRecord($fopen);
        if (empty($lastRecord)) {
            return null;
        }

        return explode($this->seperator, $lastRecord);
    }

    public function buildRecord($block, $count)
    {
        return "{$block}{$this->seperator}{$count}";
    }

    public function newBlock($now = null)
    {
        echo $this->blockFormat[$this->mode];
        $format = $this->blockFormat[$this->mode];
        return Carbon::now()->format($format);
    }

    public function test()
    {
        return true || false;
    }

    // Thanks to https://stackoverflow.com/questions/15025875
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
            echo "Result is is N\n";
            echo "Next with $offset\n";
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

    public function readContent()
    {
        echo file_get_contents($this->filepath);
    }
}
