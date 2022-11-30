<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class StringHelper
{
    public static function toString($value): string
    {
        if (is_null($value)) {
            return 'NULL';
        }
        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }
        if (is_array($value)) {
            return 'ARRAY';
        }
        if (is_object($value)) {
            return 'OBJECT';
        }

        return $value;
    }

    public static function toSlug(string $value): string
    {
        return Str::upper(str_replace(' ', '_', $value));
    }

    public static function toCamelCase(string $value): string
    {
        return Str::lower(str_replace('_', ' ', $value));
    }
}
