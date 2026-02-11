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

namespace System;

use System\Default\_string;
use System\Diagnostics\Webbrowser_Logger;
use System\Forms\MessageType;

final class BrowserConsole extends Console
{
      /**
       * Affiche un message dans la console du navigateur (sans saut de ligne).
       */
      public static function Write(string $message): void
      {
            Webbrowser_Logger::WriteInConsole($message);
      }

      /**
       * Affiche un message suivi d'un saut de ligne (équivalent à WriteLine en C#).
       */
      public static function WriteLine(string $message = _string::EmptyString): void
      {
            self::Write($message); // Même comportement que Write()
      }

      /**
       * Affiche un message en tant qu'erreur dans la console.
       */
      public static function WriteError(string $message): void
      {
            Webbrowser_Logger::WriteInConsole($message, MessageType::Error);
      }

      /**
       * Affiche un message en tant qu'avertissement dans la console.
       */
      public static function WriteWarning(string $message): void
      {
            Webbrowser_Logger::WriteInConsole($message, MessageType::Warning);
      }

      /**
       * Affiche un objet ou une variable sous forme d'un tableau dans la console.
       */
      public static function WriteTable(array $array): void
      {
            Webbrowser_Logger::WriteInConsole($array);
      }

      /**
       * Efface la console du navigateur.
       */
      public static function Clear(): void
      {
            Webbrowser_Logger::Clear();
      }
}
