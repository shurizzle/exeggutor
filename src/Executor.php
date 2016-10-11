<?php

namespace Shura\Exeggutor;

class Executor
{
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::setExecutor(self::defaultExecutors());
        }

        return self::$instance;
    }

    public static function defaultExecutors()
    {
        return [
            \Shura\Exeggutor\Executors\Proc::class,
            \Shura\Exeggutor\Executors\Exec::class,
            \Shura\Exeggutor\Executors\Passthru::class,
            \Shura\Exeggutor\Executors\System::class,
            \Shura\Exeggutor\Executors\ShellExec::class,
        ];
    }

    public static function getFirstValidExecutor($executors)
    {
        if (is_string($executors)) {
            $executors = [$executors];
        }

        if (is_array($executors)) {
            foreach ($executors as $executor) {
                $interfaces = class_implements($executor);
                if (class_exists($executor) &&
                    $interfaces &&
                    in_array(ExecutorInterface::class, $interfaces) &&
                    method_exists($executor, 'isValid') &&
                    $executor::isValid()) {
                    return $executor;
                }
            }
        }
    }

    public static function setExecutor($executors)
    {
        $executor = self::getFirstValidExecutor($executors);

        if (isset($executor)) {
            return self::$instance = new $executor();
        }
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::getInstance(), $name], $arguments);
    }

    public static function wrapFd($fd)
    {
        if (!isset($fd)) {
            return OSUtils::null();
        }
        if (is_int($fd)) {
            return '&'.$fd;
        }

        return escapeshellarg($fd);
    }

    public static function wrap(CommandInterface $cmd, $in = null, $out = 1, $err = 2, $exitCode = null)
    {
        if (!isset($out) && !isset($err)) {
            $err = 1;
        }

        if ($out != 1 || $err != 2 || isset($in)) {
            $cmd = '('.$cmd.')';
        }

        if (isset($in)) {
            $cmd .= ' <'.escapeshellarg($in);
        }

        if ($out != 1) {
            $cmd .= ' >'.self::wrapFd($out);
        }

        if ($err != 2) {
            $cmd .= ' 2>'.self::wrapFd($err);
        }

        if (isset($exitCode)) {
            $cmd .= ';echo '.OSUtils::exitCode().' >'.escapeshellarg($exitCode);
        }

        return $cmd;
    }
}
