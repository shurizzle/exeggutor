<?php

namespace Shura\Exeggutor\Commands\ManagedCommand;

use Shura\Exeggutor\Commands\ManagedCommand\Traits\ValueToString;

abstract class BaseValue implements ValueInterface
{
    use ValueToString;

    protected $name;
    protected $values;

    public function __construct($name, $values = null)
    {
        $this->name = $name;

        if (!is_array($values) && $values !== null) {
            $values = [$values];
        }

        $this->values = $values;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValues()
    {
        return $this->values;
    }
}
