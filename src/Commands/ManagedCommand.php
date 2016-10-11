<?php

namespace Shura\Exeggutor\Commands;

class ManagedCommand extends ConfigurableManagedCommand
{
    public function __construct($command)
    {
        parent::__construct($command,
            \Shura\Exeggutor\Commands\ManagedCommand\ArgumentList::class,
            \Shura\Exeggutor\Commands\ManagedCommand\FlagList::class,
            \Shura\Exeggutor\Commands\ManagedCommand\ParamList::class,
            \Shura\Exeggutor\Commands\ManagedCommand\SubCommandList::class);
    }
}
