<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

interface SubCommandListInterface
{
    public function __toString();

    public function addSubCommand(SubCommandInterface $subcommand);
}
