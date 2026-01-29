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

class Console implements IConsole
{
      private static string $currentColor = Console_Color::DEFAULT;

      private const Clear_Windows = "cls";
      private const Clear_Linux = "clear";
      private const OS_Name_Windows_Pattern = "WIN";
      private const ConsoleColorEnumeration_Pattern = "Console_Color::";

      /**
       * Affiche un message dans la console.
       */
      public static function Write(string $message): void
      {
            echo Console::$currentColor . $message;
      }

      /**
       * Affiche un message suivi d'un saut de ligne.
       */
      public static function WriteLine(string $message = _string::EmptyString): void
      {
            echo Console::Write($message . PHP_EOL);
      }

      /**
       * Lit une ligne d'entrée utilisateur depuis la console.
       */
      public static function ReadLine(): string|false
      {
            return trim(fgets(STDIN));
      }

      /**
       * Efface l'écran de la console (fonctionne sous Linux/Mac/Windows).
       */
      public static function Clear(): void
      {
            system(
                  (strtoupper(substr(PHP_OS, 0, 3)) === Console::OS_Name_Windows_Pattern)
                        ? Console::Clear_Windows
                        : Console::Clear_Linux
            );
      }

      /**
       * Change la couleur du texte (supporté uniquement dans certaines consoles).
       */
      public static function SetForegroundColor(string $console_Color): void
      {
            $constantName = Console::ConsoleColorEnumeration_Pattern . strtoupper($console_Color);

            if (!defined($constantName))
                  throw new \Exception("Unknown console color");

            Console::$currentColor = constant($constantName);
      }

      /**
       * Réinitialise les couleurs de la console.
       */
      public static function ResetColor(): void
      {
            Console::$currentColor = Console_Color::DEFAULT;
      }

      private static function WriteSpecificMessage(string $message, string $ConsoleColor)
      {
            $old_Color = Console::$currentColor;
            self::SetForegroundColor($ConsoleColor);

            Console::WriteLine($message);
            self::SetForegroundColor($old_Color);
      }

      /**
       * Affiche un message d'erreur dans la console.
       */
      public static function WriteError(string $message): void
      {
            Console::WriteSpecificMessage(
                  $message,
                  Console_Color::RED
            );
      }

      /**
       * Réinitialise les couleurs de la console.
       */
      public static function WriteWarning(string $message): void
      {
            Console::WriteSpecificMessage(
                  $message,
                  Console_Color::YELLOW
            );
      }

      /**
       * Réinitialise les couleurs de la console.
       */
      public static function WriteArray(array $array): void
      {
            foreach ($array as $key => $value)
                  Console::WriteLine($key . " = " . $value);
      }
}
