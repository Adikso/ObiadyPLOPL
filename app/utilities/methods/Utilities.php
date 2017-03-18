<?php

/**
 * Checks if given string is valid date
 *
 * @param $date
 * @return bool|null
 */
function validateDate($date)
{
    if (is_null($date)) return false;

    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}

/**
 * Returns anticsrf form field as HTML
 *
 * @return string
 */
function csrfField()
{
    $token = NoCSRF::generate('csrf_token');
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Checks if csrf_token is valid
 *
 * @return bool
 */
function checkCSRF()
{
    try {
        NoCSRF::check('csrf_token', $_POST, true, 60 * 10, false);
        return true;
    } catch (Exception $e) {
        Debug::$debugBar['exceptions']->addException($e);
        return false;
    }
}

/**
 * Redirects user to given url
 * Data can be send to other page via $data param
 *
 * @param $url
 * @param null $data
 */
function redirect($url, $data = null)
{
    if ($data !== null){
        $_SESSION['data'] = $data;
    }

    header('Location: ' . $url);
    die();
}

/**
 * Returns client real ip
 *
 * @return array|false|string
 */
function getClientIp()
{
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Groups pizzas by ingredients
 *
 * @param $pizzas
 * @return array
 */
function groupPizzas($pizzas)
{
    $result = [];

    foreach ($pizzas as $pizza) {
        $pizza = explode(',', $pizza);
        sort($pizza);

        $string = trim(strtolower(implode(',', $pizza)));
        if (array_key_exists($string, $result)) {
            $result[$string]++;
        } else {
            $result[$string] = 1;
        }
    }

    return $result;
}

/**
 * Generates path to route
 *
 * @param $id
 * @param null $params
 * @return mixed|null
 */
function route($id, $params = null)
{
    $route = Router::getCollection()->getById($id);

    if (is_null($route)){
        return null;
    }

    $path = $route->getPath();

    if (is_array($params)) {
        foreach ($params as $key => $value) {
            $fieldName = '{' . $key . '}';
            $path = str_replace($fieldName, $value, $path);
        }
    }

    return $path;
}

/**
 * Returns text if condition is met
 * If not then returns empty string
 *
 * @param $text
 * @param $expression
 * @return string
 */
function insertIf($text, $expression){
    if ($expression){
        return $text;
    }

    return "";
}