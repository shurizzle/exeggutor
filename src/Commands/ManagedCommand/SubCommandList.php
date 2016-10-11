<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

class SubCommandList implements SubCommandListInterface
{
    protected $subCommands = [];

    public function __toString()
    {
        return implode(' ', $this->subCommands);
    }

    public function addSubCommand(SubCommandInterface $subCommand)
    {
        $this->subCommands[] = $subCommand;
    }
}
