<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

class FlagList implements FlagListInterface
{
    protected $flags = [];

    public function __toString()
    {
        return implode(' ', $this->flags);
    }

    public function addFlag(FlagInterface $flag)
    {
        $this->flags[(string) $flag] = $flag;
    }
}
