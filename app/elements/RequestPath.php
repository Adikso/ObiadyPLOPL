<?php


class RequestPath
{

    private $route;
    private $parameters;

    /** @return Route */
    public function getRoute()
    {
        return $this->route;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    function __construct(Route $route, $parameters)
    {
        $this->route = $route;
        $this->parameters = $parameters;
    }

}