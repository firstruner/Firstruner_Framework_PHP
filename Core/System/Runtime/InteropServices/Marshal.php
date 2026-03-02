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

namespace System\Runtime\InteropService;

use System\Exceptions\COMException;

final class Marshal
{
      // Déclaration des variables de classe nécessaires
      public static int $E_FAIL = 0x80004005;

      public static function StringToByteArray(string $s): array
      {
            $bytes = [];
            $len = strlen($s);
            for ($i = 0; $i < $len; $i++) {
                  $bytes[] = ord($s[$i]);
            }
            return $bytes;
      }

      public static function ByteArrayToString(array $bytes): string
      {
            $out = '';
            foreach ($bytes as $b) {
                  $out .= chr((int)$b);
            }
            return $out;
      }

      public static function AllocHGlobal(int $size): string
      {
            return bin2hex(random_bytes($size));
      }

      public static function Copy(array $source, int $startIndex, int $length): string
      {
            return self::ByteArrayToString(array_slice($source, $startIndex, $length));
      }

      public static function SizeOf($value): int
      {
            return strlen(serialize($value));
      }

      public static function StructToBinary($value): string
      {
            return serialize($value);
      }

      public static function BinaryToStruct(string $binary)
      {
            return unserialize($binary);
      }

      public static function ByteArrayToHex(array $bytes): string
      {
            $bin = self::ByteArrayToString($bytes);
            return bin2hex($bin);
      }

      public static function HexToByteArray(string $hex_str): array
      {
            $bin = hex2bin($hex_str);
            if ($bin === false) {
                  return [];
            }
            return self::StringToByteArray($bin);
      }

      public static function MemoryCopy(string $source, string &$destination, int $length): void
      {
            $destination = substr($source, 0, $length);
      }

      public static function ZeroMemory(string &$memory): void
      {
            $memory = str_repeat("\0", strlen($memory));
      }

      public static function GetHRForException(\Throwable $exception): int
      {
            $code = null;

            // Référence à COMException sans la créer : on teste seulement si elle existe.
            if (class_exists('COMException') && $exception instanceof COMException) {
                  $code = $exception->getCode();
            }

            if (is_int($code)) {
                  return $code;
            }

            $exCode = $exception->getCode();
            if (is_int($exCode) && $exCode !== 0) {
                  return $exCode;
            }

            return self::$E_FAIL;
      }
}
