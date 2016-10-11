<?php

namespace Shura\Exeggutor\Traits;

use InvalidArgumentException;

trait CommandExitCode
{
    protected $exitCode = false;

    public function setExitCode($to = true)
    {
        if (!is_string($to) && $to !== null && !is_bool($to)) {
            throw new InvalidArgumentException('Output must be a valid file or null/false');
        }
        $this->exitCode = $to;

        return $this;
    }

    public function getExitCode()
    {
        return $this->exitCode;
    }
}
