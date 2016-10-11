<?php

namespace Shura\Exeggutor;

class Functions
{
    private static $disabled_functions;

    public static function getDisabledFunctions()
    {
        if (!isset(self::$disabled_functions)) {
            self::$disabled_functions = preg_split('/\s*,\s*/', ini_get('disable_functions'), -1, PREG_SPLIT_NO_EMPTY);
        }

        return self::$disabled_functions;
    }

    public static function isEnabled($name)
    {
        return !self::isDisabled($name);
    }

    public static function isDisabled($name)
    {
        return in_array($name, self::getDisabledFunctions());
    }

    public static function exists($name)
    {
        return function_exists($name);
    }

    public static function isValid($name)
    {
        return self::exists($name) && self::isEnabled($name);
    }
}
