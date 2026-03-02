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

namespace System\Xml;

use SimpleXMLElement;
use System\Exceptions\DataException;

final class XMLStream
{
      public static function ReadString(string $content): XMLElement
      {
            $xml = simplexml_load_string($content);

            if (!$xml)
                  throw new DataException("XML Parsing error");

            $rootElement = new XMLElement();

            foreach ($xml as $xmlNode) {
                  $rootElement[$xmlNode->getName()] =
                        XMLStream::readNode($rootElement, $xmlNode);
            }

            return $rootElement;
      }

      private static function readNode(XMLElement &$root, SimpleXMLElement $node): XMLElement
      {
            foreach ($node as $xmlNode) {
                  if (isset($root[$node->getName()])) {
                        $root[$node->getName()]->Add();
                  }

                  // if (!isset($rootElement[$xmlElement->getName()]))
                  // {
                  //       $rootElement[$xmlElement->getName()];
                  // }
            }

            return new XMLElement();
      }
}
