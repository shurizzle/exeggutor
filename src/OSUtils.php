<?php

namespace Shura\Exeggutor;

class OSUtils
{
    private static $null;

    public static function isWindows()
    {
        return strtolower(substr(PHP_OS, 0, 3)) === 'win';
    }

    public static function null($tempDir = null)
    {
        if (!isset(self::$null)) {
            if (self::isWindows()) {
                self::$null = 'NUL';
            } elseif (is_writable('/dev/null')) {
                self::$null = '/dev/null';
            } else {
                self::$null = tempnam(isset($tempDir) ? $tempDir : sys_get_temp_dir(), 'dev-null-');

                register_shutdown_function(function () {
                    @unlink(\Shura\Exeggutor\OSUtils::null());
                });
            }
        }

        return self::$null;
    }

    public static function exitCode()
    {
        return self::isWindows() ? '%errorlevel%' : '$?';
    }
}
