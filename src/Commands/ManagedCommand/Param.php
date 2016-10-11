<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

class Param implements ParamInterface
{
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function __toString()
    {
        return escapeshellarg($this->param);
    }
}
