<?php

class Users
{

    private static $current = null;

    /**
     * Returns user access level numeric value
     *
     * @param $user
     * @return mixed
     */
    public static function getAccessLevel($user)
    {
        if (is_null($user)) {
            return array_search(Roles::Guest, self::getRoles());
        }

        return array_search($user->role, self::getRoles());
    }

    /**
     * Converts role name to numeric value
     *
     * @param $roleName
     * @return mixed
     */
    public static function asAccessLevel($roleName){
        return array_search($roleName, self::getRoles());
    }

    /**
     * Returns all possible roles
     *
     * @return array
     */
    public static function getRoles(){
        return array_values(Roles::getConstants());
    }

    /**
     * Returns current logged on user
     *
     * @return DatabaseModel|null
     */
    public static function getCurrentUser()
    {
        if (is_null(self::$current) && isset($_SESSION['id'])) {
            self::$current = User::find($_SESSION['id']);
        }

        return self::$current;
    }

    /**
     * Checks if user is currently logged in
     *
     * @return bool
     */
    public static function isLoggedIn()
    {
        return !is_null(self::$current);
    }

}