<?php

class Device
{

    /**
     * Checks if user visits a page with mobile device
     *
     * This check is based on user agent
     *
     * @return bool
     */
    public static function isMobile()
    {
        return self::getBrowser($_SERVER['HTTP_USER_AGENT'])['type'] === 'mobile';
    }

    /**
     * Returns basic informations about user browser
     * such as useragent, browser name, platform and type
     *
     * It returns an array of following elements:
     * userAgent, name, platform, type
     *
     * @param null $useragent
     * @return array
     */
    public static function getBrowser($useragent = null)
    {

        if (is_null($useragent)) {
            $useragent = $_SERVER['HTTP_USER_AGENT'];
        }

        $u_agent = $useragent;
        $bname = 'Unknown';
        $platform = 'Unknown';
        $type = 'Unknown';

        if (preg_match('/Android/i', $u_agent)) {
            $platform = 'android';
            $type = 'mobile';
        } elseif (preg_match('/iPhone/i', $u_agent)) {
            $platform = 'ios';
            $type = 'mobile';
        } else if (preg_match('/Windows Phone/i', $u_agent)) {
            $platform = 'wp';
            $type = 'mobile';
        } elseif (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
            $type = 'computer';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
            $type = 'computer';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
            $type = 'computer';
        }

        $ub = null;

        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
        } elseif (preg_match('/Opera Mini/i', $u_agent)) {
            $bname = 'Opera Mini';
        } elseif (preg_match('/Vivaldi/i', $u_agent)) {
            $bname = 'Vivaldi';
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
        } elseif (preg_match('/Chromium/i', $u_agent)) {
            $bname = 'Chromium';
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
        } elseif (preg_match('/Facebook/i', $u_agent)) {
            $bname = 'Facebook';
        }

        return [
            'userAgent' => $u_agent,
            'name' => $bname,
            'platform' => $platform,
            'type' => $type
        ];
    }

}