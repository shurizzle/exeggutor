<?php

namespace Shura\Exeggutor\Commands;

use Shura\Exeggutor\Traits\CommandStandardIO;
use Shura\Exeggutor\Traits\CommandExitCode;
use Shura\Exeggutor\CommandInterface;

class StringCommand implements CommandInterface
{
    use CommandStandardIO;
    use CommandExitCode;

    protected $cmd;

    public function __construct($cmd)
    {
        if (is_string($cmd)) {
            $this->cmd = $cmd;
        } elseif ($cmd instanceof CommandInterface) {
            $this->cmd = (string) $cmd;
            $this->setExitCode($cmd->getExitCode());
            $this->setStandardIn($cmd->getStandardIn())
                ->setStandardOut($cmd->getStandardOut())
                ->setStandardError($cmd->getStandardError());
        }
    }

    public function __toString()
    {
        return $this->cmd;
    }
}
