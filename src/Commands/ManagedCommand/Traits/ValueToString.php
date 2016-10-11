<?php

namespace Shura\Exeggutor\Commands\ManagedCommand\Traits;

trait ValueToString
{
    public function __toString()
    {
        $name = static::PREFIX.$this->getName();
        $values = $this->getValues();

        if (isset($values) && count($values) > 0) {
            $prefix = $name.' ';

            return $prefix.implode($prefix, array_map('escapeshellarg', $values));
        }

        return $name;
    }
}
