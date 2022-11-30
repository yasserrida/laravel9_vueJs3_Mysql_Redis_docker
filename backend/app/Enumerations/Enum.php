<?php

namespace App\Enumerations;

use ReflectionClass;

abstract class Enum
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public static function getConstList()
    {
        $rf = new ReflectionClass(get_called_class());

        return $rf->getConstants();
    }

    public static function getConstNamebyVal($val)
    {
        $constants = self::getConstList();
        foreach ($constants as $name => $value) {
            if ($value == $val) {
                return $name;
            }
        }

        return null;
    }

    public static function getValbyConstName($val)
    {
        $constants = self::getConstList();
        foreach ($constants as $name => $value) {
            if ($name == $val) {
                return $value;
            }
        }

        return null;
    }
}
