<?php


namespace System\Reflection\ProtoSync;

final class Cli
{
    /** @return array<string,string> */
    public static function parseArgs(array $argv): array
    {
        $out = [];
        foreach ($argv as $i => $arg) {
            if ($i === 0) continue;
            if (str_starts_with($arg, '--') && str_contains($arg, '=')) {
                [$k, $v] = explode('=', substr($arg, 2), 2);
                $out[$k] = $v;
            }
        }
        return $out;
    }

    public static function must(array $args, string $key): string
    {
        if (!isset($args[$key]) || $args[$key] === '') {
            fwrite(STDERR, "Missing required --{$key}=...\n");
            exit(2);
        }
        return $args[$key];
    }
}
