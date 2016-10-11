<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

class SubCommand implements SubCommandInterface
{
    protected $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function __toString()
    {
        return $this->command;
    }
}
