<?php

/**
 * MyLogger Class.
 *
 * Main class for Sender logging
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://mailixa.net/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@mailixa.net>
 * @author     L. Pham <l.pham@mailixa.net>
 * @copyright  Acelle Co., Ltd.
 * @license    Acelle Co., Ltd.
 *
 * @version    1.0
 *
 * @link       http://mailixa.net
 */

namespace Acelle\Library;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Read configuration settings
 * Setup init CONSTANTS.
 */
class Log
{
    public static $logger;
    public static $path;

    public static function debug($message)
    {
        self::$logger->debug($message);
    }

    public static function info($message)
    {
        self::$logger->info($message);
    }

    public static function notice($message)
    {
        self::$logger->notice($message);
    }

    public static function warning($message)
    {
        self::$logger->warning($message);
    }

    public static function error($message)
    {
        self::$logger->error($message);
    }

    public static function critical($message)
    {
        self::$logger->critical($message);
    }

    public static function alert($message)
    {
        self::$logger->alert($message);
    }

    public static function emergency($message)
    {
        self::$logger->emergency($message);
    }

    // reconfigure log format for the forked process
    public static function fork()
    {
        $pid = getmypid();
        $output = '[%datetime%] #'.$pid." %level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $stream = new StreamHandler(self::$path, Logger::INFO);
        $stream->setFormatter($formatter);

        self::$logger = new Logger('mailer');
        self::$logger->pushHandler($stream);
    }

    public static function configure($path)
    {
        $pid = getmypid();
        $output = '[%datetime%] #'.$pid." %level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $stream = new StreamHandler($path, Logger::INFO);
        $stream->setFormatter($formatter);

        self::$logger = new Logger('mailer');
        self::$logger->pushHandler($stream);
        self::$path = $path;
    }
}
