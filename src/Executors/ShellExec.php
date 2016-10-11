<?php

namespace Shura\Exeggutor\Executors;

use Shura\Exeggutor\Functions;
use Shura\Exeggutor\ExecutorInterface;
use Shura\Exeggutor\CommandInterface;
use Shura\Exeggutor\CommandResult;
use Shura\Exeggutor\Executor;
use Shura\Exeggutor\Traits\CommandStandardIO;
use Shura\Exeggutor\Files;
use Shura\Exeggutor\Commands\StringCommand;
use InvalidArgumentException;

class ShellExec implements ExecutorInterface
{
    protected $tmp;

    public function __construct($tmp = null)
    {
        $this->tmp = $tmp;
    }

    public function setTempDir($tmp)
    {
        $this->tmp = $tmp;
    }

    public function run(CommandInterface $cmd)
    {
        $in = $cmd->getStandardIn();

        if (!CommandStandardIO::isValidStandardInput($in)) {
            throw new InvalidArgumentException('Invalid input parameter.');
        }

        if (is_string($in)) {
            $executor = $this;

            return Files::withTemp(function ($file) use (&$executor, &$cmd) {
                file_put_contents($file, $cmd->getStandardIn());

                return $executor->run((new StringCommand($cmd))->setStandardIn(['files', $file]));
            }, $executor->tmp);
        }

        $err = $cmd->getStandardError();

        if ($err === false) {
            $err = null;
        } elseif ($err === true) {
            $err = 2;
        }

        if ($err === 2) {
            $executor = $this;

            return Files::withTemp(function ($file) use (&$executor, &$cmd) {
                $res = $executor->run((new StringCommand($cmd))->setStandardError($file));
                if (isset($res)) {
                    return new CommandResult($res->getExitCode(), $res->getStandardOut(), file_get_contents($file));
                }

                return new CommandResult(null, null, file_get_contents($file));
            }, $executor->tmp);
        }

        $out = $cmd->getStandardOut();

        if ($out === false) {
            $out = null;
        } elseif ($out === true) {
            $out = 1;
        }

        if ($out === 1) {
            $executor = $this;

            return Files::withTemp(function ($file) use (&$executor, &$cmd) {
                $res = $executor->run((new StringCommand($cmd))->setStandardOut($file));
                if (isset($res)) {
                    return new CommandResult($res->getExitCode(), file_get_contents($file), $res->getStandardError());
                }

                return new CommandResult(null, file_get_contents($file), null);
            }, $executor->tmp);
        }

        $exitCode = $cmd->getExitCode();
        if ($exitCode === true) {
            $executor = $this;

            return Files::withTemp(function ($file) use (&$executor, &$cmd) {
                $res = $executor->run((new StringCommand($cmd))->setExitCode($file));
                if (isset($res)) {
                    return new CommandResult((int) trim(file_get_contents($file)), $res->getStandardOut(), $res->getStandardError());
                }

                return new CommandResult((int) trim(file_get_contents($file)), null, null);
            });
        }

        if ($in === false) {
            $in = null;
        }

        $p_in = null;
        if (is_array($in)) {
            $p_in = $in[1];
        }

        if ($exitCode === false) {
            $exitCode = null;
        }

        $cmdStr = Executor::wrap($cmd, $p_in, $out, $err, $exitCode);

        shell_exec($cmdStr);

        return;
    }

    public static function isValid()
    {
        return Functions::isValid('shell_exec');
    }
}
