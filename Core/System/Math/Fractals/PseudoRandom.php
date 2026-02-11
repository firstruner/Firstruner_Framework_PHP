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
 * Please refer to https:*firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Math\Fractals;

use System\Drawing\Size;

class PseudoRandom extends AbsFractal
{
      public function __construct(FractalOptions $_options)
      {
            parent::__construct($_options);
      }

      public function Generate(): string
      {
            $zx = 0.0;
            $zy = 0.0;
            $out = "";

            for ($i = 0; $i < $this->Options->Depth; $i++) {
                  $tmp = $zx * $zx - $zy * $zy + $this->Options->RealPartNumber;
                  $zy = 2 * $zx * $zy + $this->Options->UnRealPartNumber;
                  $zx = $tmp;

                  $val = (int)(($zx * $zx + $zy * $zy) * 1000000) % 256;
                  $out .= chr($val);
            }

            return $out; // suite de bytes pseudo-aléatoires
      }
}
