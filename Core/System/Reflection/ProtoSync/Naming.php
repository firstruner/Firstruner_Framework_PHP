<?php


namespace System\Reflection\ProtoSync;

final class Naming
{
    public static function toSnake(string $s): string
    {
        // Basic Camel/Pascal → snake
        $s = preg_replace('/([a-z])([A-Z])/', '$1_$2', $s) ?? $s;
        $s = preg_replace('/\s+/', '_', $s) ?? $s;
        return strtolower($s);
    }

    public static function toPascal(string $s): string
    {
        $s = str_replace(['-', '_'], ' ', $s);
        $s = ucwords($s);
        return str_replace(' ', '', $s);
    }
}
