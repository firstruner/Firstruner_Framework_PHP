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

namespace System;

class Console implements IConsole
{
      /**
       * Affiche un message dans la console.
       */
      public static function Write($message) {
          echo $message;
      }
  
      /**
       * Affiche un message suivi d'un saut de ligne.
       */
      public static function WriteLine($message = "") {
          echo $message . PHP_EOL;
      }
  
      /**
       * Lit une ligne d'entrée utilisateur depuis la console.
       */
      public static function ReadLine() {
          return trim(fgets(STDIN));
      }
  
      /**
       * Efface l'écran de la console (fonctionne sous Linux/Mac/Windows).
       */
      public static function Clear() {
            system(
                  (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
                  ? 'cls'
                  : 'clear'
            );
      }
  
      /**
       * Change la couleur du texte (supporté uniquement dans certaines consoles).
       */
      public static function SetForegroundColor(string $named_Color) {
          $colors = [
              'Black' => '0;30', 'Red' => '0;31', 'Green' => '0;32', 'Yellow' => '0;33',
              'Blue' => '0;34', 'Magenta' => '0;35', 'Cyan' => '0;36', 'White' => '0;37',
              'Default' => '0'
          ];
  
          if (isset($colors[$named_Color])) {
              echo "\033[" . $colors[$named_Color] . "m";
          }
      }
  
      /**
       * Réinitialise les couleurs de la console.
       */
      public static function ResetColor() {
          echo "\033[0m";
      }

      /**
       * Affiche un message d'erreur dans la console.
       */
      public static function WriteError($message)
      {
            self::SetForegroundColor()
      }

      /**
       * Réinitialise les couleurs de la console.
       */
      public static function WriteWarning($message);

      /**
       * Réinitialise les couleurs de la console.
       */
      public static function WriteArray($array);
  }
  