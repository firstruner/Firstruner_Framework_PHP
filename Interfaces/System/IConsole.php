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

namespace System;

interface IConsole
{
    public static function Write(string $message): void; // ok
    public static function WriteLine(string $message): void; // ok
    public static function WriteError(string $message): void;
    public static function WriteWarning(string $message): void;
    public static function WriteArray(array $array): void;
    public static function Clear(): void; // ok
    public static function ReadLine(): string|false; // ok
    public static function SetForegroundColor(string $named_Color): void; // ok
    public static function ResetColor(): void; // ok
}
