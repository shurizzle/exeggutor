<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

interface ValueInterface
{
    public function __toString();

    public function getName();

    public function getValues();
}
