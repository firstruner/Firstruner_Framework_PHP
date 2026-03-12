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
use const Firstruner\Frameworks\LoadingLists\Enumerations\Firstruner_Framework_Enumerations;
use const Firstruner\Frameworks\LoadingLists\Interfaces\Firstruner_Framework_Interfaces;
use const Firstruner\Frameworks\LoadingLists\Prestashop\PrestashopClasses;
use const Firstruner\Frameworks\LoadingLists\Core\Firstruner_Framework_Core;
use const Firstruner\Frameworks\LoadingLists\Corp\Firstruner_Framework_CorpExceptions;
use const Firstruner\Frameworks\LoadingLists\Corp\Firstruner_Framework_CorpClasses;
use const Firstruner\Frameworks\LoadingLists\DNA\Firstruner_Framework_DNAClasses;

class Framework
{
      public const FrameworkVersion = "3.3.1";

      public static bool $VendorLoading = true;

      private static bool $loaded = false;
      public static function IsLoaded(): bool
      {
            return Framework::$loaded;
      }

      public static function Load(bool $reload = false, bool $details = false, bool $passErrors = false, bool $showStackTrace = false)
      {
            if ($reload) {
                  // TODO: Reload fonction
            }

            if (Framework::$loaded)
                  return;

            define("FirstrunerFramework_LogPath", "log/Firstruner.log");

            require_once(HOME_LOADER . '/Core/System/Reflection/Dependencies/Loader.php');

            Loader::$debug = $details;
            Loader::$passErrors = $passErrors;
            Loader::$stackTrace = $showStackTrace;

            if (Framework::$VendorLoading) {
                  if (!class_exists("FPDF")) Loader::Load(HOME_LOADER . '/Core/System/Printing/fpdf/fpdf.php');
                  if (!class_exists("PdfParser")) Loader::Load(HOME_LOADER . '/Core/System/Printing/fpdi_licencied/src/autoload.php');
                  if (!class_exists("PHPMailer")) Loader::Load(HOME_LOADER . '/Core/System/Net/Mail/phpmailer');
            }

            Loader::Load(
                  array_merge(
                        Firstruner_Framework_Enumerations,
                        Firstruner_Framework_Interfaces,
                        PrestashopClasses,
                        Firstruner_Framework_Core,
                        Firstruner_Framework_CorpExceptions,
                        Firstruner_Framework_CorpClasses,
                        Firstruner_Framework_DNAClasses,
                  )
            );

            Framework::$loaded = true;
      }
}
