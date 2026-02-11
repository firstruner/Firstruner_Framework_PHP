<?php

/**
 * Copyright 2024-2026 Firstruner and Contributors
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
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

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
