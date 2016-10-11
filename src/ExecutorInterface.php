<?php

namespace Shura\Exeggutor;

interface ExecutorInterface
{
    public function run(CommandInterface $cmd);
}
