<?php

/**
 * Copyright since 2024 Firstruner and Contributors
 * Firstruner is an Registered Trademark & Property of Christophe BOULAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Freemium License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@firstruner.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit, reproduce ou modify this file.
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright Since 2024 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System;

class Enumerations
{
    public static function HasFlag(int $flags, int $flag) : bool
    {
        //return ($value / $searched) > 1;
        return (($flags & $flag) === $flag);
    }

    public static function HasFlag_FromArray(array $collection, int $key) : bool
    {
        foreach ($collection as $value) {
            if ($value == $key) { return true; }
        }
        
        return false;
    }

    private static function extractEnumsKeyValues(string $enumName): array
    {
        $reflect = new \ReflectionClass($enumName);
        return $reflect->getConstants();
    }

    public static function GetKey_FromValue(string $enumName, $value)
    {
        foreach (Enumerations::extractEnumsKeyValues($enumName) as $_key => $_value)
            if ($value == $_value) return $_key;

        return null;
    }

    public static function GetValue_FromKey(string $enumName, string $key)
    {
        foreach (Enumerations::extractEnumsKeyValues($enumName) as $_key => $_value)
            if ($key == $_key) return $_value;

        return null;
    }

    public static function Exist_Key(string $enumName, string $key): bool
    {
        foreach (Enumerations::extractEnumsKeyValues($enumName) as $_key => $_value)
            if ($key == $_key) return true;

        return false;
    }

    public static function Exist_Value(string $enumName, string $value): bool
    {
        foreach (Enumerations::extractEnumsKeyValues($enumName) as $_key => $_value)
            if ($value == $_value) return true;

        return false;
    }
}