<?php

namespace Shura\Exeggutor\Commands;

use Shura\Exeggutor\Traits\CommandStandardIO;
use Shura\Exeggutor\Traits\CommandExitCode;
use Shura\Exeggutor\CommandInterface;
use Shura\Exeggutor\Commands\ManagedCommand\SubCommandInterface;
use Shura\Exeggutor\Commands\ManagedCommand\ParamInterface;
use Shura\Exeggutor\Commands\ManagedCommand\ArgumentInterface;
use Shura\Exeggutor\Commands\ManagedCommand\FlagInterface;
use InvalidArgumentException;

class ConfigurableManagedCommand implements CommandInterface, ManagedCommandInterface
{
    use CommandStandardIO;
    use CommandExitCode;

    protected $command;
    protected $arguments;
    protected $flags;
    protected $params;
    protected $subCommands;

    public function __construct($command, $argumentListClass, $flagListClass, $paramListClass, $subCommandListClass)
    {
        self::assertValidInstanceOf($argumentListClass, \Shura\Exeggutor\Commands\ManagedCommand\ArgumentListInterface::class);
        self::assertValidInstanceOf($flagListClass, \Shura\Exeggutor\Commands\ManagedCommand\FlagListInterface::class);
        self::assertValidInstanceOf($paramListClass, \Shura\Exeggutor\Commands\ManagedCommand\ParamListInterface::class);
        self::assertValidInstanceOf($subCommandListClass, \Shura\Exeggutor\Commands\ManagedCommand\SubCommandListInterface::class);

        $this->command = $command;

        $this->arguments = new $argumentListClass();
        $this->flags = new $flagListClass();
        $this->params = new $paramListClass();
        $this->subCommands = new $subCommandListClass();
    }

    public function __toString()
    {
        return $this->command.
            $this->pad($this->subCommands).
            $this->pad($this->flags).
            $this->pad($this->arguments).
            $this->pad($this->params);
    }

    private function pad($string)
    {
        $string = (string) $string;

        return empty($string) ? $string : " $string";
    }

    public function addSubCommand(SubCommandInterface $subCommand)
    {
        $this->subCommands->addSubCommand($subCommand);

        return $this;
    }

    public function addParam(ParamInterface $param)
    {
        $this->params->addParam($param);

        return $this;
    }

    public function addArgument(ArgumentInterface $argument)
    {
        $this->arguments->addArgument($argument);

        return $this;
    }

    public function addFlag(FlagInterface $flag)
    {
        $this->flags->addFlag($flag);

        return $this;
    }

    private static function assertValidInstanceOf($klass, $interface)
    {
        if (!self::isValidInstanceOf($klass, $interface)) {
            throw new InvalidArgumentException('Class '.$klass.' is not a valid implementation of '.$interface.'.');
        }
    }

    private static function isValidInstanceOf($klass, $interface)
    {
        if (class_exists($klass)) {
            $is = class_implements($klass);

            return isset($is[$interface]);
        }

        return false;
    }
}
