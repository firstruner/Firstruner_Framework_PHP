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

use Exception;

class Base64Url
{
      const UrlEncodingPad = '%'; // allowed for url padding

      private const fromValues = '+/';
      private const toValues = '-_';
      private const equalValue = '=';

      /// <summary>Validates that the specified span of text is comprised of valid base-64 encoded data.</summary>
      /// <param name="base64UrlText">A span of text to validate.</param>
      /// <param name="decodedLength">If the method returns true, the number of decoded bytes that will result from decoding the input text.</param>
      /// <returns><see langword="true"/> if <paramref name="base64UrlText"/> contains a valid, decodable sequence of base-64 encoded data; otherwise, <see langword="false"/>.</returns>
      /// <remarks>
      /// If the method returns <see langword="true"/>, the same text passed to <see cref="Base64Url.DecodeFromChars(ReadOnlySpan{char})"/> and
      /// <see cref="Base64Url.TryDecodeFromChars(ReadOnlySpan{char}, Span{byte}, out int)"/> would successfully decode (in the case
      /// of <see cref="Base64Url.TryDecodeFromChars(ReadOnlySpan{char}, Span{byte}, out int)"/> assuming sufficient output space).
      /// Any amount of whitespace is allowed anywhere in the input, where whitespace is defined as the characters ' ', '\t', '\r', or '\n'.
      /// </remarks>
      public static function IsValid(string $base64UrlText, ?int &$decodedLength = null): bool
      {
            return Base64Helper::IsValid(
                  $base64UrlText,
                  $decodedLength
            );
      }

      public static function EncodeToString(string $input): string
      {
            if (strlen($input) == 0) throw new Exception("Input is not a valid string");

            // Encodage en base64
            $data = base64_encode($input);

            // Remplacement des caractères pour le mode URL-safe
            $data = strtr($data, Base64Url::fromValues, Base64Url::toValues);

            // Suppression des caractères de remplissage
            return rtrim($data, Base64Url::equalValue);
      }

      public static function DecodeToString(string $input): string
      {
            if (!Base64Url::IsValid($input)) throw new Exception("Input is not a Base64 valid string");

            // Remplacement des caractères en mode Base64 standard
            $data = strtr($input, Base64Url::toValues, Base64Url::fromValues);

            // Ajout du remplissage pour que la longueur soit un multiple de 4
            $padding = strlen($data) % 4;

            if ($padding > 0)
                  $data .= str_repeat(Base64Url::equalValue, 4 - $padding);

            // Décodage en Base64
            return base64_decode($data);
      }
}
