<?php

class User extends DatabaseModel
{

    public $id;
    public $login;
    public $email;

    public $password;

    public $firstname;
    public $secondname;

    public $balance;
    public $classId;
    public $role = Roles::User;
    public $lastlogin;
    public $icon;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->id = $data['id'];
            $this->login = $data['login'];
            $this->email = $data['email'];

            $this->password = $data['password'];

            $this->firstname = $data['firstname'];
            $this->secondname = $data['secondname'];

            $this->balance = $data['balance'];
            $this->classId = $data['classId'];
            $this->role = $data['role'];
            $this->lastlogin = $data['lastlogin'];
        }
    }

    public function getFullName()
    {
        return $this->firstname . ' ' . $this->secondname;
    }

    public function getClass(){
        return SchoolClass::find($this->classId);
    }

    public function isCurrentUser()
    {
        return (user()->id === $this->id);
    }

    public function getRule($name)
    {
        return Rules::getRule($name);
    }

    public function getPermissions()
    {
        return Permissions::getPermissions($this);
    }

    public function getPermission($name)
    {
        return Permissions::getPermissionValue($this, $name);
    }

    public function hasPermission($name)
    {
        return ($this->getPermission($name) != null);
    }

    public function checkPermission($name, $value)
    {
        return ($value === $this->getPermission($name));
    }

    public function getOriginTable()
    {
        return "users";
    }

}