<?php

class Permissions
{

    private static $cache = [];

    /**
     * Retrives all user permissions
     *
     * Permissions are later stored in 'cache'
     *
     * @param $user
     * @return DatabaseModel|DatabaseModel[]|mixed
     */
    public static function getPermissions($user)
    {
        if (array_key_exists($user->id, self::$cache)) {
            return self::$cache[$user->id];
        } else {
            $expression = new Expression;
            $expression->equals('user', $user->id);
            $permissions = Permission::findByExpression($expression);

            self::$cache[$user->id] = $permissions;

            return $permissions;
        }
    }

    /**
     * Returns permission value
     *
     * @param $user
     * @param $name
     * @return bool
     */
    public static function getPermissionValue($user, $name)
    {
        $permissions = self::getPermissions($user);

        /** @var Permission $permission */
        foreach ($permissions as $permission) {
            if ($permission->user === $user->id && $permission->name == $name) {
                return $permission->value;
            }
        }

        return false;
    }

}