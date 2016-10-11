<?php

namespace Shura\Exeggutor\Commands;

use Shura\Exeggutor\Traits\CommandStandardIO;
use Shura\Exeggutor\Traits\CommandExitCode;
use InvalidArgumentException;

class ConfigurableCommandBuilder implements CommandBuilderInterface
{
    use CommandStandardIO;
    use CommandExitCode;

    protected $command;
    protected $argumentClass;
    protected $flagClass;
    protected $paramClass;
    protected $subCommandClass;

    public function __construct(ManagedCommandInterface $command, $argumentClass, $flagClass, $paramClass, $subCommandClass)
    {
        self::assertValidInstanceOf($argumentClass, \Shura\Exeggutor\Commands\ManagedCommand\ArgumentInterface::class);
        self::assertValidInstanceOf($flagClass, \Shura\Exeggutor\Commands\ManagedCommand\FlagInterface::class);
        self::assertValidInstanceOf($paramClass, \Shura\Exeggutor\Commands\ManagedCommand\ParamInterface::class);
        self::assertValidInstanceOf($subCommandClass, \Shura\Exeggutor\Commands\ManagedCommand\SubCommandInterface::class);

        $this->command = $command;
        $this->argumentClass = $argumentClass;
        $this->flagClass = $flagClass;
        $this->paramClass = $paramClass;
        $this->subCommandClass = $subCommandClass;
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

    public function addSubCommand($subCommand)
    {
        $subCommandClass = $this->subCommandClass;
        $this->command->addSubCommand(new $subCommandClass($subCommand));

        return $this;
    }

    public function addArgument($name, $value = null)
    {
        $argumentClass = $this->argumentClass;
        $this->command->addArgument(new $argumentClass($name, $value));

        return $this;
    }

    public function addFlag($name, $value = null)
    {
        $flagClass = $this->flagClass;
        $this->command->addFlag(new $flagClass($name, $value));

        return $this;
    }

    public function addParam($name)
    {
        $paramClass = $this->paramClass;
        $this->command->addParam(new $paramClass($name));

        return $this;
    }

    public function __toString()
    {
        return (string) $this->command;
    }
}
