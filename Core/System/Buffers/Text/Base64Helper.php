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


namespace System\Buffers\Text;

use System\Char;
use System\Default\_string;

class Base64Helper
{
      public static function Is_Base64(string $s): bool
      {
            // Check if there are valid base64 characters
            if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;

            // Decode the string in strict mode and check the results
            $decoded = base64_decode($s, true);
            if (false === $decoded) return false;

            // Encode the string again
            if (base64_encode($decoded) != $s) return false;

            return true;
      }

      public static function IsWhiteSpace(Char $value): bool
      {
            return ($value == _string::WhiteSpace);
      }

      public static function IsValid(string $s, ?int &$length = null): bool
      {
            // Vérifie si la chaîne est bien en hexadécimal
            if (!ctype_xdigit($s)) return false;

            // Vérifie que la longueur est paire
            if (strlen($s) % 2 !== 0) return false;

            $length = strlen($s);
            return true;
      }
}
