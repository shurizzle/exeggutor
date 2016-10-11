<?php

namespace Shura\Exeggutor\Commands;

use Shura\Exeggutor\CommandInterface;

interface CommandBuilderInterface extends CommandInterface
{
    public function addSubCommand($subCommand);

    public function addArgument($name, $value = null);

    public function addFlag($name, $value = null);

    public function addParam($name);
}
