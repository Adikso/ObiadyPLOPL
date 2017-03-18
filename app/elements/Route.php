<?php

class Route
{

    private $id;

    /** Possible controllers **/
    private $controllers;

    /** Default parameters */
    private $defaults;

    /** Original path */
    private $path;

    /** Path 1 => 2 => 3 => 4 */
    private $associativePath;

    private $minRole;


    public function getId()
    {
        return $this->id;
    }

    public function getControllers()
    {
        return $this->controllers;
    }

    public function getDefaults()
    {
        return $this->defaults;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getAssociativePath()
    {
        return $this->associativePath;
    }

    public function getMinRole()
    {
        return $this->minRole;
    }


    public function setAssociativePath($associativePath)
    {
        $this->associativePath = $associativePath;
    }


    public function __construct($id, $path, $controllers, $minRole, $defaults = [])
    {
        $this->id = $id;
        $this->path = $path;
        $this->controllers = $controllers;
        $this->minRole = $minRole;
        $this->defaults = $defaults;
    }

}