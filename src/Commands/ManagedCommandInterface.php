<?php

namespace Shura\Exeggutor\Commands;

use Shura\Exeggutor\Commands\ManagedCommand\SubCommandInterface;
use Shura\Exeggutor\Commands\ManagedCommand\ParamInterface;
use Shura\Exeggutor\Commands\ManagedCommand\ArgumentInterface;
use Shura\Exeggutor\Commands\ManagedCommand\FlagInterface;

interface ManagedCommandInterface
{
    public function __toString();

    public function addSubCommand(SubCommandInterface $subCommand);

    public function addParam(ParamInterface $param);

    public function addArgument(ArgumentInterface $argument);

    public function addFlag(FlagInterface $flag);
}
