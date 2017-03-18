<?php

class Legacy
{

    /**
     * Authenticates user and update old password
     *
     * @param string $password Plaintext password given by user
     * @param string $hash Password hash from database
     * @param User $user User instance
     * @return bool
     */
    public static function verifyAndUpdate($password, $hash, &$user)
    {
        if (sha1($password) === $hash) { // SHA1
            $newPassword = Passwords::create($password);
            $user->password = $newPassword;
            $user->update();

            return true;
        }

        return false;
    }

}