<?php

/**
 * Copyright since 2024 Firstruner and Contributors
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
 * @copyright Since 2024 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace Firstruner;

use System\Reflection\Dependencies\Loader;

class Framework
{
      public static bool $VendorLoading = true;

      private static bool $loaded = false;
      public static function IsLoaded(): bool
      {
            return Framework::$loaded;
      }

      public static function Load(bool $reload = false)
      {
            if ($reload) {
                  // TODO: Reload fonction
            }

            if (Framework::$loaded)
                  return;

            define("FirstrunerFramework_LogPath", "log/Firstruner.log");

            require_once(__DIR__ . '/Core/System/Reflection/Dependencies/Loader.php');

            if (Framework::$VendorLoading)
            {
                  if (!class_exists("FPDF")) Loader::Load(__DIR__ . '/Core/System/Printing/fpdf/fpdf.php');
                  if (!class_exists("PdfParser")) Loader::Load(__DIR__ . '/Core/System/Printing/fpdi_licencied/src/autoload.php');
                  if (!class_exists("PHPMailer")) Loader::Load(__DIR__ . '/Core/System/Net/Mail/phpmailer');
            }

            Loader::Load(
                  [
                        __DIR__ . '/Enumerations',
                        __DIR__ . '/Interfaces',
                        __DIR__ . '/Core/Prestashop/Exceptions/CoreException.php',
                        __DIR__ . '/Core/Prestashop/Exceptions/DomainException.php',
                        __DIR__ . '/Core/Prestashop/Exceptions/CustomerException.php',
                        __DIR__ . '/Core/Prestashop/Exceptions/EmployeeException.php',
                        __DIR__ . '/Core/Prestashop',
                        __DIR__ . '/Core/System/DBNull.php',
                        __DIR__ . '/Core/System/Annotations',
                        __DIR__ . '/Core/System/Attributes',
                        __DIR__ . '/Core/System/Exceptions',
                        __DIR__ . '/Core/System/UniqueObject.php',
                        __DIR__ . '/Core/System/UniqueObjectArray.php',
                        __DIR__ . '/Core/System/Collections/Iterators',
                        __DIR__ . '/Core/System/Collections',
                        __DIR__ . '/Core/System/Types_System/_Class/EmptyClass.php',
                        __DIR__ . '/Core/System/Types_System/_Class/DynamicClass.php',
                        __DIR__ . '/Core/System/Types_System',

                        __DIR__ . '/Core/System/Data/DataObject.php',
                        __DIR__ . '/Core/System/Data/DataObjectArray.php',
                        __DIR__ . '/Core/System/Data/KeyConstraint.php',

                        __DIR__ . '/Core/System/Data',
                        __DIR__ . '/Core/System/Xml',
                        __DIR__ . '/Core/System/Diagnostics',
                        __DIR__ . '/Core/System/Runtime',
                        __DIR__ . '/Core/System/Environment',
                        __DIR__ . '/Core/System/Globalization',
                        __DIR__ . '/Core/System/Forms',
                        __DIR__ . '/Core/System/Net/Http/Response/Response.php',
                        __DIR__ . '/Core/System/Net/Http/Response/JSonResponse.php',
                        __DIR__ . '/Core/System/IO/File.php',
                        __DIR__ . '/Core/System/IO/Stream.php',
                        __DIR__ . '/Core/System/IO/BytesStream.php',
                        __DIR__ . '/Core/System/IO/MemoryStream.php',
                        __DIR__ . '/Core/System/Threading',
                        __DIR__ . '/Core/System/IO',
                        __DIR__ . '/Core/System/Reflection',
                        __DIR__ . '/Core/System/Security',
                        __DIR__ . '/Core/System/Net',
                        __DIR__ . '/Core/System/Text',
                        __DIR__ . '/Core/System/Configuration',
                        __DIR__ . '/Core/System/Net/Mail/MailAddress.php',
                        __DIR__ . '/Core/System/Net/Mail/MailAddressCollection.php',
                        __DIR__ . '/Core/System/Net/Mail/MailMessage.php',
                        __DIR__ . '/Core/System/Net/Mail/SmtpClient.php',
                        __DIR__ . '/Core/Firstruner'
                  ]
            );

            Framework::$loaded = true;
      }
}
