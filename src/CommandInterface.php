<?php

namespace Shura\Exeggutor;

interface CommandInterface
{
    public function __toString();

    public function getStandardIn();
    public function getExitCode();
    public function getStandardOut();
    public function getStandardError();
}
