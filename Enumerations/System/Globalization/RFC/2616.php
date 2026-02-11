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



/** 
 * -- File description --
 * @Type : MethodClass
 * @Mode : XP/BDD Creation
 * @Author : Christophe BOULAS
 * @Update on : 21/01/2026 by : Christophe BOULAS
 */

namespace System\Globalization\RFC;

//use System\Globalization\IRFC;

final class RFC2616 //implements IRFC
{
      public static function Series(): array
      {
            return [
                  // RFC 2616: "any CHAR except CTLs or separators".
                  // CHAR           = <any US-ASCII character (octets 0 - 127)>
                  // CTL            = <any US-ASCII control character
                  //                  (octets 0 - 31) and DEL (127)>
                  // separators     = "(" | ")" | "<" | ">" | "@"
                  //                | "," | ";" | ":" | "\" | <">
                  //                | "/" | "[" | "]" | "?" | "="
                  //                | "{" | "}" | SP | HT
                  // SP             = <US-ASCII SP, space (32)>
                  // HT             = <US-ASCII HT, horizontal-tab (9)>
                  // <">            = <US-ASCII double-quote mark (34)>
                  '!',
                  '#',
                  '$',
                  '%',
                  '&',
                  "'",
                  '*',
                  '+',
                  '-',
                  '.',
                  '0',
                  '1',
                  '2',
                  '3',
                  '4',
                  '5',
                  '6',
                  '7',
                  '8',
                  '9',
                  'A',
                  'B',
                  'C',
                  'D',
                  'E',
                  'F',
                  'G',
                  'H',
                  'I',
                  'J',
                  'K',
                  'L',
                  'M',
                  'N',
                  'O',
                  'P',
                  'Q',
                  'R',
                  'S',
                  'T',
                  'U',
                  'V',
                  'W',
                  'X',
                  'Y',
                  'Z',
                  '^',
                  '_',
                  '`',
                  'a',
                  'b',
                  'c',
                  'd',
                  'e',
                  'f',
                  'g',
                  'h',
                  'i',
                  'j',
                  'k',
                  'l',
                  'm',
                  'n',
                  'o',
                  'p',
                  'q',
                  'r',
                  's',
                  't',
                  'u',
                  'v',
                  'w',
                  'x',
                  'y',
                  'z',
                  '|',
                  '~',
            ];
      }
}
