<?php

namespace Shura\Exeggutor\Executors;

use Shura\Exeggutor\Functions;
use Shura\Exeggutor\ExecutorInterface;
use Shura\Exeggutor\CommandInterface;
use Shura\Exeggutor\CommandResult;
use Shura\Exeggutor\Executor;
use Shura\Exeggutor\Traits\CommandStandardIO;
use InvalidArgumentException;

class Proc implements ExecutorInterface
{
    public function run(CommandInterface $cmd)
    {
        $in = $cmd->getStandardIn();
        $out = $cmd->getStandardOut();
        $err = $cmd->getStandardError();

        if ($in === false) {
            $in = null;
        }

        if (!CommandStandardIO::isValidStandardInput($in)) {
            throw new InvalidArgumentException('Invalid input parameter.');
        }

        if ($out === false) {
            $out = null;
        } elseif ($out === true) {
            $out = 1;
        }

        if ($err === false) {
            $err = null;
        } elseif ($err === true) {
            $err = 2;
        }

        $p_in = null;
        if (is_array($in)) {
            $p_in = $in[1];
        }

        $cmdStr = Executor::wrap($cmd, $p_in, $out, $err);

        $proc = proc_open($cmdStr, [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ], $pipes);

        if (isset($in) && is_string($in)) {
            fwrite($pipes[0], $in);
        }
        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        $exitCode = proc_close($proc);

        $stdout = $out === 1 ? $stdout : null;
        $stderr = $err === 2 ? $stderr : null;
        $exitCode = $cmd->getExitCode() ? $exitCode : null;

        if ($stdout !== null || $stderr !== null || $exitCode !== null) {
            return new CommandResult($exitCode, $stdout, $stderr);
        }
    }

    public static function isValid()
    {
        return Functions::isValid('proc_open');
    }
}
