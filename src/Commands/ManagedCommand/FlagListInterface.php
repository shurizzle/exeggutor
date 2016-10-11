<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

interface FlagListInterface
{
    public function __toString();

    public function addFlag(FlagInterface $subcommand);
}
