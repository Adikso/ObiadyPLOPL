<?php

class RouteCollection
{

    protected $routes = [];

    /**
     * Adds route to collection
     *
     * @param Route $route
     */
    public function add(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     * Returns route by its id
     *
     * @param $id
     * @return mixed|null
     */
    public function getById($id)
    {
        foreach ($this->routes as $route) {
            if ($route->getId() === $id) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Returns Route matching given URL
     *
     * @var $url
     * @return null|RequestPath
     */
    public function getByURL($url)
    {
        $variables = [];
        $parts = explode('/', $url);

        /** @var Route $route */
        foreach ($this->routes as $route) {
            $current = $route->getAssociativePath();

            $level = 0;
            foreach ($parts as $part) {
                $level++;

                if (!array_key_exists($part, $current)) {
                    foreach (array_keys($current) as $anotherPart) {
                        if (startsWith('{', $anotherPart)) {
                            $variableName = trim($anotherPart, '{}');

                            $variables[$variableName] = $part;
                            $current = $current[$anotherPart];
                            continue 2;
                        }
                    }

                    continue 2;
                }

                $current = $current[$part];
            }

            if ($level != count($route->getAssociativePath(), COUNT_RECURSIVE)) {
                continue;
            }

            return new RequestPath($route, $variables);
        }

        return null;

    }

}