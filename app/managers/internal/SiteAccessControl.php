<?php


class SiteAccessControl
{

    public static function verify(){

        if (!config('general.enabled') && !isset($_COOKIE['debug'])) {
            echo 'Strona została tymczasowo wyłączona';
            die;
        }

        if (isset($_COOKIE['debug']) || config("general.debug")) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }

        if (config('access.whitelist.enable')) {
            if (!in_array($_SERVER['REMOTE_ADDR'], config('access.whitelist.entries'))) {
                die();
            }
        }

        if (config('access.blacklist.enable')) {
            if (in_array($_SERVER['REMOTE_ADDR'], config('access.blacklist.entries'))) {
                die();
            }
        }

        if (config('access.block-iframe')) {
            header("X-Frame-Options: SAMEORIGIN");
        }

        date_default_timezone_set(config('other.timezone'));

    }

    public static function isHttps(){
        return !empty($_SERVER['HTTPS']);
    }

}