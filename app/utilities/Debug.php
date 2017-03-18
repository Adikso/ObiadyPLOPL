<?php

use DebugBar\StandardDebugBar;

class Debug
{

    public static $debugBar = null;
    public static $debugBarRenderer = null;

    /**
     * Setup instance of Debug Bar
     */
    public static function setup()
    {
        self::$debugBar = new StandardDebugBar();
        self::$debugBarRenderer = self::$debugBar->getJavascriptRenderer();
    }

    /**
     * Adds message to Debug Bar
     *
     * @param $content
     */
    public static function info($content)
    {
        self::$debugBar['messages']->addMessage($content);
    }

    /**
     * Checks if site is in debug mode
     *
     * Check is based on `general.debug` configuration key
     *
     * @return null
     */
    public static function isDebugMode(){
        return config('general.debug');
    }

}
