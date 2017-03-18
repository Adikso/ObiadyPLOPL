<?php


class Assets
{

    /**
     * Returns path to public resource
     *
     * @param $url
     * @return string
     */
    public static function get($url)
    {
        return config('general.baseURL') . $url;
    }

}