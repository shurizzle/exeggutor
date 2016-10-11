<?php

namespace Shura\Exeggutor;

class CommandResult
{
    private $exitCode;
    private $out;
    private $err;

    public function __construct($exitCode, $out, $err)
    {
        $this->exitCode = $exitCode;
        $this->out = $out;
        $this->err = $err;
    }

    public function getExitCode()
    {
        return $this->exitCode;
    }

    public function getStandardOut()
    {
        return $this->out;
    }

    public function getStandardError()
    {
        return $this->err;
    }
}
