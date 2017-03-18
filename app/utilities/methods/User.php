<?php

/**
 * Gives quick access to user object
 * Returns current user by default
 *
 * @param integer $id
 * @return DatabaseModel|null|User
 */
function user($id = null)
{
    if ($id !== null) {
        $user = User::find($id);
    }else{
        $user = Users::getCurrentUser();
    }

    return $user;
}