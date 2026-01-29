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

use Obselete;

class Enumerations
{
    #[Obselete("Use Enum class")]
    public static function HasFlag(int $flags, int $flag): bool
    {
        return Enum::HasFlag($flags, $flag);
    }

    #[Obselete("Use Enum class")]
    public static function HasFlag_FromArray(array $collection, int $key): bool
    {
        return Enum::HasFlag_FromArray($collection, $key);
    }

    #[Obselete("Use Enum class")]
    public static function GetKey_FromValue(string $enumName, $value)
    {
        return Enum::GetKey_FromValue($enumName, $value);
    }

    #[Obselete("Use Enum class")]
    public static function GetValue_FromKey(string $enumName, string $key)
    {
        return Enum::GetValue_FromKey($enumName, $key);
    }

    #[Obselete("Use Enum class")]
    public static function Exist_Key(string $enumName, string $key): bool
    {
        return Enum::Exist_Key($enumName, $key);
    }

    #[Obselete("Use Enum class")]
    public static function Exist_Value(string $enumName, string $value): bool
    {
        return Enum::Exist_Value($enumName, $value);
    }
}
