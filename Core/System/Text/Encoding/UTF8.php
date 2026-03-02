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

final class UTF8
{
      public const Name = "UTF-8";

      public static function GetBytes(string $str): array
      {
            return array_values(unpack('C*', $str)); // Convertit la chaîne en tableau d'octets
      }

      // Méthode GetString pour convertir un tableau de bytes en une chaîne UTF-8
      public static function GetString(array $bytes): string
      {
            return \mb_convert_encoding(implode(array_map("chr", $bytes)), UTF8::Name);
      }
}
