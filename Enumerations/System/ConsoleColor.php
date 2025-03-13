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

abstract class ConsoleColor {
    private static array $colors = [
        "Black" => "\033[30m",
        "DarkBlue" => "\033[34m",
        "DarkGreen" => "\033[32m",
        "DarkCyan" => "\033[36m",
        "DarkRed" => "\033[31m",
        "DarkMagenta" => "\033[35m",
        "DarkYellow" => "\033[33m",
        "Gray" => "\033[37m",
        "DarkGray" => "\033[90m",
        "Blue" => "\033[94m",
        "Green" => "\033[92m",
        "Cyan" => "\033[96m",
        "Red" => "\033[91m",
        "Magenta" => "\033[95m",
        "Yellow" => "\033[93m",
        "White" => "\033[97m",
        "Reset" => "\033[0m"
    ];

    public static function GetColor(string $name): ?string {
        return self::$colors[$name] ?? null;
    }

    public static function ListColors(): array {
        return array_keys(self::$colors);
    }
}
