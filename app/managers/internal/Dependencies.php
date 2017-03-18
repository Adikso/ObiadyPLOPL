<?php

class Dependencies
{

    public static function setup()
    {
        set_include_path(realpath(ROOT_DIR));

        require 'vendor/autoload.php';

        spl_autoload_register(function ($classname) {
            self::autoloadFrom($classname, 'app/managers');
            self::autoloadFrom($classname, 'app/managers/internal');
            self::autoloadFrom($classname, 'app/managers/features');
            self::autoloadFrom($classname, 'app/elements');
            self::autoloadFrom($classname, 'app/elements/enums');
            self::autoloadFrom($classname, 'app/controllers');
            self::autoloadFrom($classname, 'app/models');
            self::autoloadFrom($classname, 'app/services');
            self::autoloadFrom($classname, 'app/utilities');
        });
        self::loadUtilities();
    }

    public static function loadUtilities()
    {
        require 'app/utilities/methods/Utilities.php';
        require 'app/utilities/methods/User.php';
        require 'app/utilities/methods/TextUtil.php';
        require 'app/utilities/methods/NamesUtil.php';
        require 'app/utilities/methods/ConfigUtil.php';
        require 'app/utilities/methods/ArrayUtil.php';
    }

    public static function autoloadFrom($classname, $dirname)
    {
        $filename = $dirname . '/' . $classname . ".php";
        if (is_readable(realpath(ROOT_DIR . $filename))) {
            include $filename;
        }
    }

}