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

use System\Drawing\Named_Color;

abstract class ConsoleColor {
      public const BLACK = "\033[30m";
      public const DARK_BLUE = "\033[34m";
      public const DARK_GREEN = "\033[32m";
      public const DARK_CYAN = "\033[36m";
      public const DARK_RED = "\033[31m";
      public const DARK_MAGENTA = "\033[35m";
      public const DARK_YELLOW = "\033[33m";
      public const GRAY = "\033[37m";
      public const DARK_GRAY = "\033[90m";
      public const BLUE = "\033[94m";
      public const GREEN = "\033[92m";
      public const CYAN = "\033[96m";
      public const RED = "\033[91m";
      public const MAGENTA = "\033[95m";
      public const YELLOW = "\033[93m";
      public const WHITE = "\033[97m";
      public const DEFAULT = "\033[0m";

      public static function ListColors(): array {
            $reflection = new \ReflectionClass(ConsoleColor::class);
            return $reflection->getConstants();        
      }
}
