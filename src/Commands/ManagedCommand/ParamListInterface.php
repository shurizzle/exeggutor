<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

interface ParamListInterface
{
    public function __toString();

    public function addParam(ParamInterface $subcommand);
}
