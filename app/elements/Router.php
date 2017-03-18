<?php

class Router
{
    private static $collection;

    /** @return RouteCollection */
    public static function getCollection()
    {
        return self::$collection;
    }

    /** @param RouteCollection $collection */
    public static function setCollection($collection)
    {
        self::$collection = $collection;
    }


    public static function setup()
    {
        self::$collection = new RouteCollection();
    }


    public static function matchRoute(Route $route)
    {
        $path = $_GET['p'];
        $method = $_SERVER['REQUEST_METHOD'];

        $routeFromURL = flatToAssociative($path);

        return ($route->getAssociativePath() == $routeFromURL
            && array_key_exists($method, $route['controllers']));
    }

    public static function getMethod()
    {
        $path = Input::get('p');
        $method = $_SERVER['REQUEST_METHOD'];

        /** @var RequestPath $requestPath */
        $requestPath = self::$collection->getByURL($path);

        if (is_null($requestPath)) {
            return null;
        }

        $route = $requestPath->getRoute();
        if (!is_null($route) && array_key_exists($method, $route->getControllers())) {
            return $route->getControllers()[$method];
        }

        return null;
    }

    public static function add(Route $route)
    {
        $url = ltrim($route->getPath(), '/');
        $parts = explode('/', $url);

        $route->setAssociativePath(flatToAssociative($parts));
        self::$collection->add($route);
    }

}