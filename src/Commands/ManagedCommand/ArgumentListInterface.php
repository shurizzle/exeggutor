<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

interface ArgumentListInterface
{
    public function __toString();

    public function addArgument(ArgumentInterface $subcommand);
}
