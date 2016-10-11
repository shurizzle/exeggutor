<?php

namespace Shura\Exeggutor\Traits;

use InvalidArgumentException;

trait CommandStandardIO
{
    protected $stdin = null;
    protected $stdout = null;
    protected $stderr = null;

    public function setStandardIn($in)
    {
        if (!self::isValidStandardInput($in)) {
            throw new InvalidArgumentException('Input must be a valid input string or file');
        }
        $this->stdin = $in;

        return $this;
    }

    public function getStandardIn()
    {
        return $this->stdin;
    }

    public function setStandardOut($out = true)
    {
        if (!self::isValidStandardOutput($out)) {
            throw new InvalidArgumentException('Output must be a valid file or file descriptor');
        }
        $this->stdout = $out;

        return $this;
    }

    public function getStandardOut()
    {
        return $this->stdout;
    }

    public function setStandardError($err = true)
    {
        if (!self::isValidStandardOutput($err)) {
            throw new InvalidArgumentException('Output must be a valid file or file descriptor');
        }
        $this->stderr = $err;

        return $this;
    }

    public function getStandardError()
    {
        return $this->stderr;
    }

    public static function isValidStandardOutput($output)
    {
        return is_string($output) ||
            is_int($output) ||
            $output === null ||
            is_bool($output);
    }

    public static function isValidStandardInput($input)
    {
        return is_string($input) ||
            self::isValidStandardFileInput($input) ||
            $input === null ||
            is_bool($input);
    }

    private static function isValidStandardFileInput($in)
    {
        return is_array($in) && count($in) == 2 && isset($in[0]) &&
            isset($in[1]) && $in[0] === 'file' && is_string($in[1]);
    }
}
