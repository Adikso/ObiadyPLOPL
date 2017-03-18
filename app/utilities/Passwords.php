<?php

class Passwords
{
    /**
     * Return password with salt and algorithm type in it
     * @param $password
     * @return String
     */
    public static function create($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Compares given password with hash
     *
     * @param $password
     * @param $db_hash
     * @return bool
     */
    public static function verify($password, $db_hash)
    {
        return password_verify($password, $db_hash);
    }

}