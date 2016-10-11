<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

class ArgumentList implements ArgumentListInterface
{
    protected $arguments = [];

    public function __toString()
    {
        return implode(' ', $this->arguments);
    }

    public function addArgument(ArgumentInterface $argument)
    {
        $this->arguments[$argument->getName()] = $argument;
    }
}
