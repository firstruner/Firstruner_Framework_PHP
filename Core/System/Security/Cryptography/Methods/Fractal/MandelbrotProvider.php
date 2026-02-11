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

namespace System\Security\Cryptography\Methods\Fractal;

use System\Math\Fractals\FractalOptions;

final class MandelbrotProvider
{
      private FractalOptions $Options;

      /**
       * Steganographie fractale (Julia) - embed + extract
       *
       * Dépendances : extension GD
       */

      public function __construct(?FractalOptions $_options = null)
      {
            $this->Options = $_options ?? new FractalOptions();

            if (!$_options) {
                  $this->Options->Size->Width = 200;
                  $this->Options->Size->Height = 200;
                  $this->Options->MaximalIteration = 50;
            }
      }


      public function Encrypt(string $data, FractalOptions $options): array
      {
            $carriers = $this->generateCarriers($options);
            $bytes = unpack("C*", $data);
            foreach ($bytes as $i => $b) {
                  $bytes[$i] = $b ^ ($carriers[$i % count($carriers)] ?? 0);
            }
            return array_values($bytes);
      }

      public function Decrypt(array $bytes, FractalOptions $options): string
      {
            $carriers = $this->generateCarriers($options);
            foreach ($bytes as $i => $b) {
                  $bytes[$i] = $b ^ ($carriers[$i % count($carriers)] ?? 0);
            }
            return pack("C*", ...$bytes);
      }

      public function Hash(string $data, FractalOptions $options): string
      {
            $carriers = $this->generateCarriers($options);
            $hash = hash_init("sha256");
            hash_update($hash, $data . implode("", $carriers));
            return hash_final($hash);
      }

      private function generateCarriers(FractalOptions $options): array
      {
            $carriers = [];
            for ($y = 0; $y < $this->Options->Size->Height; $y++) {
                  for ($x = 0; $x < $this->Options->Size->Width; $x++) {
                        $c_re = ($x - $this->Options->Size->Width / 2.0) * 4.0 / $this->Options->Size->Width;
                        $c_im = ($y - $this->Options->Size->Height / 2.0) * 4.0 / $this->Options->Size->Width;
                        $re = $im = 0;
                        $iter = 0;

                        while ($re * $re + $im * $im <= 4 && $iter < $this->Options->MaximalIteration) {
                              $tmp = $re * $re - $im * $im + $c_re;
                              $im = 2 * $re * $im + $c_im;
                              $re = $tmp;
                              $iter++;
                        }

                        if ($iter == $this->Options->MaximalIteration) {
                              $carriers[] = ($x * $y) % 256;
                        }
                  }
            }
            return $carriers ?: [1]; // fallback
      }
}
