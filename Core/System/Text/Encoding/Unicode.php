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

namespace System\Text\Encoding;

use System\Byte;

final class Unicode implements IEncoding
{
      public const Name = "UNICODE";

      public static function GetBytes(string $text, int $charCount = 0, ?Byte $bytes = null, int $byteCount = 0): array
      {
            $utf8Text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

            // Convert the UTF-8 encoded text to a byte array
            $byteArray = unpack('C*', $utf8Text);

            return array_values($byteArray);
      }
}
