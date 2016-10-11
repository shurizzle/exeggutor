<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

class ParamList implements ParamListInterface
{
    protected $params = [];

    public function __toString()
    {
        return implode(' ', $this->params);
    }

    public function addParam(ParamInterface $param)
    {
        $this->params[] = $param;
    }
}
