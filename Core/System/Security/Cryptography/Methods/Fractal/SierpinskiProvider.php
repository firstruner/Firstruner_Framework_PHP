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

final class SierpinskiProvider
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

      public function Encrypt(string $data): array
      {
            $carriers = $this->generateCarriers();
            $bytes = unpack("C*", $data);
            foreach ($bytes as $i => $b) {
                  $bytes[$i] = $b ^ ($carriers[$i % count($carriers)] ?? 0);
            }
            return array_values($bytes);
      }

      public function Decrypt(array $bytes): string
      {
            $carriers = $this->generateCarriers();
            foreach ($bytes as $i => $b) {
                  $bytes[$i] = $b ^ ($carriers[$i % count($carriers)] ?? 0);
            }
            return pack("C*", ...$bytes);
      }

      public function Hash(string $data): string
      {
            $carriers = $this->generateCarriers();
            $hash = hash_init("sha256");
            hash_update($hash, $data . implode("", $carriers));
            return hash_final($hash);
      }

      private function generateCarriers(): array
      {
            $carriers = [];

            for ($y = 0; $y < $this->Options->Size->Height; $y++) {
                  for ($x = 0; $x < $this->Options->Size->Width; $x++) {
                        $px = $x;
                        $py = $y;
                        $isFilled = true;
                        while ($px > 0 || $py > 0) {
                              if ($px % 2 == 1 && $py % 2 == 1) {
                                    $isFilled = false;
                                    break;
                              }
                              $px = intdiv($px, 2);
                              $py = intdiv($py, 2);
                        }

                        if ($isFilled) {
                              $carriers[] = ($x ^ $y) % 256;
                        }
                  }
            }

            return $carriers ?: [4];
      }
}
