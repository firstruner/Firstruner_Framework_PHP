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

use System\Exceptions\ArgumentException;

final class ULong
{
      public const Min = 0;
      public const Max = 18446744073709551615;

      private float $valeur;

      private function setValue(float $val)
      {
            if (($val < ULong::Min) || ($val > ULong::Max)) throw new ArgumentException("Value was incorrect");
            $this->valeur = $val;
      }

      public function __construct(float $value = 0) {
            $this->setValue($value);
      }

      public function __set($name = "Value", $value) {
            if ($name === 'Value' && is_float($value)) $this->setValue($value);
      }

      public function __get($name = "Value") : float {
            if ($name === 'Value') return $this->valeur;
      }

      public function __invoke() {
            return $this->valeur;
      }
}