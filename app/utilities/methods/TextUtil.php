<?php

/**
 * Filters string from html tags
 *
 * @param $var
 */
function filter(&$var)
{
    if (isset($var)) {
        $var = strip_tags($var, '<b><i><mark><del><ins>');
    }
}

/**
 * Checks if string starts with the given substring
 *
 * @param $needle
 * @param $haystack
 * @return bool
 */
function startsWith($needle, $haystack)
{
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

/**
 * Checks if string ends with the given substring
 *
 * @param $needle
 * @param $haystack
 * @return bool
 */
function endsWith($needle, $haystack)
{
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

/**
 * Generates random hash in hex format
 *
 * @param int $size - How many bytes to generate
 * @return string
 */
function generateHash($size = 20)
{
    return bin2hex(random_bytes($size));
}

/**
 * Generates random string
 *
 * @param int $length
 * @param string $characters
 * @return string
 */
function generateRandomString($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

