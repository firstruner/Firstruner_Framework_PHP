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

namespace System\IO;

use System\Xml\XMLElement;

final class XMLStreamWriter
{
      public static function Write(XMLElement $elements, string $path)
      {
            $output = XMLStreamWriter::CreateStringElement($elements);
            file_put_contents($path, $output);
      }

      private static function CreateStringElement(XMLElement $element, int $tabCount = 0): string
      {
            $output = "";
            $currentTabIndentation = "";

            if ($tabCount > 0)
                  for ($i = 0; $i < $tabCount; $i++)
                        $currentTabIndentation .= "\t";

            foreach ($element as $xmlKey => $xmlValue) {
                  if (is_array($xmlValue)) {
                        $output .= $currentTabIndentation . "<$xmlKey>" . PHP_EOL;

                        foreach ($xmlValue as $xmlElement)
                              $output .= XMLStreamWriter::CreateStringElement(
                                    $xmlElement,
                                    $tabCount + 1
                              );

                        $output .= $currentTabIndentation . "</$xmlKey>";
                  } else {
                        $output .= XMLStreamWriter::CreateStringXmlElement(
                              $xmlKey,
                              $xmlValue,
                              $currentTabIndentation
                        ) . PHP_EOL;
                  }
            }

            return $output;
      }

      private static function CreateStringXmlElement(
            string $elementKey,
            string $elementValue,
            string $indentation
      ): string {
            return "\t<$elementKey>$elementValue</$elementKey>";
      }
}
