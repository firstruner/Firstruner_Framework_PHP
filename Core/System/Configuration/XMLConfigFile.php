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

namespace System\Configuration;

final class XMLConfigFile
{
      private string $path;
      private \SimpleXMLElement $xml;

      public function __construct($path)
      {
            $this->path = $path;
            $this->Reload();
      }

      public function Reload()
      {
            if (file_exists($this->path)) {
                  $this->xml = simplexml_load_file($this->path);
            } else {
                  $this->xml = new \SimpleXMLElement(
                        '<?xml version="1.0"?>' . PHP_EOL .
                              '<config></config>'
                  );
            }
      }

      public function Get($key)
      {
            $result = $this->xml->xpath("//setting[@name='$key']");
            return !empty($result) ? (string)$result[0] : null;
      }

      public function Set($key, $value)
      {
            $result = $this->xml->xpath("//setting[@name='$key']");
            if (!empty($result)) {
                  $result[0][0] = $value;
            } else {
                  $setting = $this->xml->addChild('setting', $value);
                  $setting->addAttribute('name', $key);
            }
      }

      public function Remove($key)
      {
            $result = $this->xml->xpath("//setting[@name='$key']");
            if (!empty($result)) {
                  $dom = dom_import_simplexml($result[0]);
                  $dom->parentNode->removeChild($dom);
            }
      }

      public function Save()
      {
            $this->xml->asXML($this->path);
      }
}
