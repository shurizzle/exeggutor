<?php

namespace Shura\Exeggutor\Commands;

class CommandBuilder extends ConfigurableCommandBuilder
{
    public function __construct($command)
    {
        if (is_string($command)) {
            $command = new ManagedCommand($command);
        }

        parent::__construct($command,
            \Shura\Exeggutor\Commands\ManagedCommand\Argument::class,
            \Shura\Exeggutor\Commands\ManagedCommand\Flag::class,
            \Shura\Exeggutor\Commands\ManagedCommand\Param::class,
            \Shura\Exeggutor\Commands\ManagedCommand\SubCommand::class);
    }
}
