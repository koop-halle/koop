<?php

namespace Application\Util;

/**
 * Class Debugger
 *
 * @package Application\Util
 */
class Debugger
{
    /**
     * @param array ...$debug
     */
    public static function dieDebug(... $debug)
    {
        foreach (func_get_args() as $current) {
            self::debug($current);
        }
        die('die debug called. stopping here...' . PHP_EOL);
    }


    /**
     * @param array ...$debug
     */
    public static function debug(... $debug)
    {
        $backtrace = debug_backtrace(true);
        $trace     = $backtrace[0];
        if (__FILE__ === $trace['file']) {
            $trace = $backtrace[1];
        }
        $line = isset($trace['line']) ? $trace['line'] : 666;
        $file = isset($trace['file']) ? $trace['file'] : 'somewhere';
        $pre  = '';
        $post = '';
        $last = PHP_EOL . '____________________' . PHP_EOL . PHP_EOL;
        if (false === self::isCli()) {
            $pre  = '<div class="debug" style="border: solid 2px #000; margin: 5px; padding: 10px; background: #eee;"><pre>';
            $post = '</pre>';
            $last = '</div>';
        }
        $text = 'debug from ' . (str_replace(getcwd(), '', $file)) . ' line ' . $line . ':' . PHP_EOL;
        echo sprintf('%s%s%s', $pre, $text, $post);
        foreach (func_get_args() as $arg) {
            #var_dump($arg);
            #echo PHP_EOL;
            #echo PHP_EOL;
            #echo '<hr>';
            print_r($arg);
        }
        echo $last;
    }


    /**
     * checks if the application runs in cli mode
     *
     * @return boolean
     */
    public static function isCli()
    {
        return php_sapi_name() === 'cli';
    }
}
