<?php

class Cookies
{

    /**
     * Returns cookie with given name
     *
     * @param $name
     * @param null $default
     * @return null
     */
    public static function get($name, $default = null){
        if (isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }

        return $default;
    }

    /**
     * Checks if cookie with given name is present
     *
     * @param $name
     * @return bool
     */
    public static function has($name){
        return (isset($_COOKIE[$name]));
    }

    /**
     * Creates cookie
     *
     * @param $name
     * @param $value
     * @param $days
     * @param int $multiplier
     */
    public static function create($name, $value, $days, $multiplier = 86400){
        setcookie($name, $value, time() + ($days * $multiplier), '/');
    }

    /**
     * Removes cookie
     *
     * @param $name
     */
    public static function remove($name){
        setcookie($name, '', time() - 3600, '/');
    }

}