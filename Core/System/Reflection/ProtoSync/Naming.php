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
