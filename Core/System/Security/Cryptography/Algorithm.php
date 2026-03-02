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

namespace System\Security\Cryptography;

final class Algorithm
{
      public readonly string $Name;
      public readonly int $Length;

      public function __construct(string $name, string $lenght)
      {
            $this->Name = $name;
            $this->Length = $lenght;
      }

      /**
       * Retourne les informations dans un format précis
       * 
       * @param int $OutputFormat 0 => Format standard | 1 => Format tabulé | 2 => Format Pad sur 15 char | 3 => Format CSV
       */
      public function ToString(int $OutputFormat = 0)
      {
            switch ($OutputFormat) {
                  default:
                  case 0:
                        return $this->Name;
                  case 1:
                        return $this->Name . '\t' . $this->Length;
                  case 2:
                        return str_pad($this->Name, 15) . $this->Length;
                  case 3:
                        return $this->Name . ';' . $this->Length;
            }
      }

      public function __toString()
      {
            return $this->ToString();
      }
}
