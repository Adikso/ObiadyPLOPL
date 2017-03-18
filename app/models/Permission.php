<?php


class Permission extends DatabaseModel
{

    public $id;
    public $name;
    public $value;
    public $user;

    public function getOriginTable()
    {
        return 'permissions';
    }

}