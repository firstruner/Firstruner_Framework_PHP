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

namespace Firstruner;

use System\Reflection\Dependencies\Loader;

class Framework_Symfony
{
      public static bool $VendorLoading = true;

      private static bool $loaded = false;
      public static function IsLoaded(): bool
      {
            return Framework_Symfony::$loaded;
      }

      public static function Load(bool $reload = false)
      {
            if ($reload) {
                  // TODO: Reload fonction
            }

            if (Framework_Symfony::$loaded)
                  return;

            define("SymfonyFramework_LogPath", "log/Symfony.log");

            require_once(__DIR__ . '/Core/System/Reflection/Dependencies/Loader.php');

            Loader::Load(
                  [
                        __DIR__ . '/Core/Symfony/http-foundation/Interfaces',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/Bases',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/File',

                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/BadRequestException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/ConflictingHeadersException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/ExpiredSignedUriException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/JsonException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/LogicException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/SessionNotFoundException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/SuspiciousOperationException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/UnsignedUriException.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Exceptions/UnverifiedSignedUriException.php',

                        __DIR__ . '/Core/Symfony/http-foundation/HeaderUtils.php',
                        __DIR__ . '/Core/Symfony/http-foundation/HeaderBag.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Cookie.php',
                        __DIR__ . '/Core/Symfony/http-foundation/ResponseHeaderBag.php',
                        __DIR__ . '/Core/Symfony/http-foundation/Response_Light.php',
                  ]
            );

            Framework_Symfony::$loaded = true;
      }
}
