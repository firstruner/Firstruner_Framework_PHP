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
require('../Enumerations/System/Default/_string.php');
require('../Core/System/Types_System/_String/_String_Common.partial_php');

use System\_String;
use System\Default\_string as Default_string;

$content = file_get_contents("test.jpg");

$hexa = bin2hex($content);
$bin = _String::ToBinary($hexa);
$output = Default_string::EmptyString;

$arValues =
      [
            "00" => "A",
            "01" => "T",
            "10" => "C",
            "11" => "G"
      ];

$arValues2 =
      [
            "A" => "00",
            "T" => "01",
            "C" => "10",
            "G" => "11"
      ];

for ($i = 0; $i < strlen($bin); $i += 2)
      $output .= $arValues[substr($bin, $i, 2)];

// echo "[CONTENT]" . PHP_EOL . $content . PHP_EOL . PHP_EOL;
// echo "[HEXA]" . PHP_EOL . $hexa . PHP_EOL . PHP_EOL;
// echo "[BIN]" . PHP_EOL . $bin . PHP_EOL . PHP_EOL;
// echo "[DNA]" . PHP_EOL . $output;

$revBin = Default_string::EmptyString;
for ($i = 0; $i < strlen($output); $i++)
      $revBin .= $arValues2[$output[$i]];

function binaryToHex($binary)
{
      $hex = Default_string::EmptyString;

      for ($i = 0; $i < strlen($binary); $i += 2)

            // Diviser la chaîne binaire en groupes de 4 bits
            $hex = '';
      for ($i = 0; $i < strlen($binary); $i += 2) {
            // Prendre 4 bits à la fois
            $binSegment = substr($binary, $i, 2);
            // Convertir ces 4 bits en un nombre décimal, puis en hexadécimal
            $hex .= dechex(bindec($binSegment));
      }
      return strtoupper($hex); // Retourner en majuscule pour convention hexadécimale
}

$revHexa = binaryToHex($revBin);
$revContent = hex2bin($revHexa);

file_put_contents("output.jpg", $revContent);
