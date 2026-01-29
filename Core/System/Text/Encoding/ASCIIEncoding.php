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
 * Please refer to https:*firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Text\Encoding;

final class ASCIIEncoding
{
    public function GetBytes(string $input): array
    {
        return array_map("ord", str_split($input));
    }

    public function GetString(array $bytes): string
    {
        return implode(array_map("chr", $bytes));
    }

    public function GetByteCount(string $input): int
    {
        return strlen($input);
    }

    public function GetCharCount(array $bytes): int
    {
        return count($bytes);
    }

    public function GetChars(array $bytes): array
    {
        return array_map("chr", $bytes);
    }

    public function GetMaxByteCount(int $charCount): int
    {
        return $charCount;
    }

    public function GetMaxCharCount(int $byteCount): int
    {
        return $byteCount;
    }

    public function IsSingleByte(): bool
    {
        return true;
    }

    public function GetPreamble(): array
    {
        return []; // ASCII n'a pas de BOM
    }
}
