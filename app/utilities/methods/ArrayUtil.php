<?php

function find($day, $key, $value)
{
    foreach ($day as $dish) {
        if ($dish[$key] == $value) {
            return true;
        }
    }

    return false;
}

/**
 * Groups arrays by their field value
 *
 * @param $array
 * @param $key
 * @return array
 */
function group($array, $key){
    $groups = [];
    foreach ($array as $another){
        if (array_key_exists($key, $another)){
            $value = $another[$key];
            $groups[$value][] = $another;
        }
    }

    return $groups;
}

function array_get_default($element, $array, $defaultValue)
{
    if (!array_key_exists($element, $array)) {
        return $defaultValue;
    }

    return $array[$element];
}

function array_remove_keys(&$array, $keys){
    foreach ($keys as $key){
        if (array_key_exists($key, $array)){
            unset($array[$key]);
        }
    }
}

function flatToAssociative($array)
{
    $first = reset($array);
    $newArray = [];

    array_shift($array);
    $current = &$newArray[$first];

    foreach ($array as $dir) {
        $current[$dir] = [];
        $current = &$current[$dir];
    }

    return $newArray;
}