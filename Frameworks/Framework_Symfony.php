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
 * @version 3.3.0
 */

namespace Firstruner\Frameworks;

use System\Reflection\Dependencies\Loader;

use const Firstruner\Frameworks\LoadingLists\Symfony\SymfonyFramework;

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

            require_once(HOME_LOADER . '/Core/System/Reflection/Dependencies/Loader.php');

            Loader::Load(
                  SymfonyFramework
            );

            Framework_Symfony::$loaded = true;
      }
}
