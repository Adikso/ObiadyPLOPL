<?php

class Log
{

    /**
     * Adds information to daily log
     *
     * @param $action
     * @param $details
     */
    public static function info($action, $details)
    {
        self::_log($action, $details);
    }

    /**
     * Adds information to errors log
     *
     * @param $text
     */
    public static function error($text)
    {
        $source = debug_backtrace()[1]['function'];
        self::_log(null, $text, $source, "errors.log");
    }

    private static function _log($action, $data, $source = null, $filename = null)
    {
        $date = date('Y-m-d');
        $his = date('Y-m-d H:i:s');
        $path = 'logs/' . $date . '.log';

        if (!is_null($filename)) {
            $path = 'logs/' . $filename;
        }

        if (file_exists(ROOT_DIR . $path)) {
            $fp = fopen(ROOT_DIR . $path, 'a');
        } else {
            $fp = fopen(ROOT_DIR . $path, 'w');
        }

        if (isset($source)) {
            $source = " [" . $source . "]";
        }


        $log = $his . (isset($source) ? $source : "") . " " . $action . " - " . $data . PHP_EOL;

        try{
            fwrite($fp, $log);
        }catch (Exception $exception){
            Debug::info($exception->getMessage());
        }


    }

}