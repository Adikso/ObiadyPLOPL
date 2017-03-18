<?php

class Input
{

    /**
     * Returns input field with given name
     *
     * Routes params are also included
     *
     * @param $name
     * @param null $default
     * @return string|object
     */
    public static function get($name, $default = null)
    {
        if (isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        } else {
            return $default;
        }
    }

    /**
     * Returns all input values
     *
     * @return mixed
     */
    public static function all()
    {
        return $_REQUEST;
    }

    /**
     * Checks if input field with given name is present
     *
     * @param $name
     * @return bool
     */
    public static function has($name)
    {
        $args_num = func_num_args();
        if ($args_num > 1) {
            for ($i = 0; $i < $args_num; $i++) {
                $exist = self::has(func_get_arg($i));
                if (!$exist) {
                    return false;
                }
            }
            return true;
        } else {
            return (isset($_REQUEST[$name])) || (isset($_COOKIE[$name]));
        }
    }

    /**
     * Checks if input field is empty and present
     *
     * @param $name
     * @return bool
     */
    public static function isEmpty($name)
    {
        $args_num = func_num_args();
        if ($args_num > 1) {
            for ($i = 0; $i < $args_num; $i++) {
                $exist = self::has(func_get_arg($i));
                $value = self::get(func_get_arg($i));
                if (!$exist || empty($value)) {
                    return true;
                }
            }
            return false;
        } else {
            return !(
                (isset($_REQUEST[$name]) && !empty($_REQUEST[$name])) ||
                (isset($_COOKIE[$name]) && !empty($_COOKIE[$name]))
            );
        }
    }

    /**
     * Returns input field value as date
     *
     * Check is made to ensure it is correct date
     *
     * @param $name
     * @return bool|string
     */
    public static function getDate($name)
    {
        $value = self::get($name, null);

        if (validateDate($value)) {
            return $value;
        }

        return false;
    }

    /**
     * Returns session field value by name
     *
     * @param $name
     * @return array|object
     */
    public static function session($name){
        if (isset($_SESSION[$name])){
            return $_SESSION[$name];
        }

        return null;
    }
}