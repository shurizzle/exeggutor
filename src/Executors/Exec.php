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

class Exec implements ExecutorInterface
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
        if (!CommandStandardIO::isValidStandardInput($in)) {
            throw new InvalidArgumentException('Invalid input parameter.');
        }

        $in = $cmd->getStandardIn();
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

        if ($in === false) {
            $in = null;
        }

        if ($out === false) {
            $out = null;
        } elseif ($out === true) {
            $out = 1;
        }

        $p_in = null;
        if (is_array($in)) {
            $p_in = $in[1];
        }

        $cmdStr = Executor::wrap($cmd, $p_in, $out, $err);

        exec($cmdStr, $stdout, $exitCode);

        $stdout = $out === 1 ? implode("\n", $stdout) : null;
        $exitCode = $cmd->getExitCode() ? $exitCode : null;

        if ($stdout !== null || $exitCode !== null) {
            return new CommandResult($exitCode, $stdout, null);
        }
    }

    public static function isValid()
    {
        return Functions::isValid('exec');
    }
}
