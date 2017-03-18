<?php

/**
 * Returns value from config by its path
 *
 * @param $path
 * @param null $relative
 * @return null
 */
function config($path, $relative = null)
{
    global $config;
    return configSetting($path, $config, $relative);
}

function configSetting($path, $config, $relative = null)
{
    $parts = explode(".", $path);

    $current = (isset($relative) ? $relative : $config);

    foreach ($parts as $key) {
        if (!array_key_exists($key, $current)) {
            return $current;
        }
        $current = $current[$key];
    }

    return $current;
}