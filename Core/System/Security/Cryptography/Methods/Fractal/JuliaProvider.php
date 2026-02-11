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

final class JuliaProvider
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
                  $this->Options->RealPartNumber = -0.7;
                  $this->Options->UnRealPartNumber = 0.27015;
                  $this->Options->MaximalIteration = 200;
            }
      }

      private function checkDependances(string $srcPath)
      {
            if (!extension_loaded('gd')) throw new \Exception("GD extension required");
            if (!file_exists($srcPath)) throw new \Exception("Fichier introuvable: $srcPath");
      }

      /**
       * Calcule l'itération Julia pour la coordonnée (zx, zy) et renvoie
       * le nombre d'itérations avant divergence (<= $maxIter).
       */
      private function juliaIterations(float $zx, float $zy, float $cx, float $cy, int $maxIter): int
      {
            $iter = 0;

            while (($zx * $zx + $zy * $zy) <= 4.0 && $iter < $maxIter) {
                  $xt = $zx * $zx - $zy * $zy + $cx;
                  $zy = 2.0 * $zx * $zy + $cy;
                  $zx = $xt;
                  $iter++;
            }

            return $iter;
      }

      /**
       * Mappe un pixel (px, py) vers le plan complexe défini par bounds.
       */
      private function pixelToComplex(int $px, int $py, int $w, int $h, array $bounds): array
      {
            [$xmin, $xmax, $ymin, $ymax] = $bounds;

            $dx = ($xmax - $xmin) / ($w - 1);
            $dy = ($ymax - $ymin) / ($h - 1);
            $cx = $xmin + $px * $dx;
            $cy = $ymax - $py * $dy; // y inversé pour l'affichage

            return [$cx, $cy];
      }

      /**
       * Embed a message into an image using Julia set membership as carrier mask.
       *
       * @param string $srcPath  Image source (PNG/JPG)
       * @param string $message  Message binaire (raw string)
       * @param string $outPath  Output image path (PNG)
       * @param array|null $bounds optional bounds [xmin,xmax,ymin,ymax] else default
       * @return bool true on success
       * @throws Exception
       */
      public function Encrypt(
            string $srcPath,
            string $message,
            string $outPath,
            ?array $bounds = null
      ): bool {
            $this->checkDependances($srcPath);

            $img = imagecreatefromstring(file_get_contents($srcPath));
            if ($img === false) throw new \Exception("Impossible de charger l'image");

            $w = imagesx($img);
            $h = imagesy($img);

            $bounds = $bounds ?? [-1.8, 1.8, -1.2, 1.2];

            // Collect carrier pixel positions (those qui restent bornés)
            $carriers = [];
            for ($py = 0; $py < $h; $py++) {
                  for ($px = 0; $px < $w; $px++) {
                        [$zx, $zy] = $this->pixelToComplex($px, $py, $w, $h, $bounds);

                        $iter = $this->juliaIterations(
                              $zx,
                              $zy,
                              $this->Options->RealPartNumber,
                              $this->Options->UnRealPartNumber,
                              $this->Options->MaximalIteration
                        );

                        if ($iter === $this->Options->MaximalIteration)
                              $carriers[] = [$px, $py];
                  }
            }

            // Prepare payload bits: 32-bit length header + message bits
            $msgBytes = $message;
            $msgLen = strlen($msgBytes);
            $bits = [];

            // 32-bit big-endian length
            $lenPacked = pack('N', $msgLen);
            $payload = $lenPacked . $msgBytes;
            $payloadLenBits = strlen($payload) * 8;

            if (count($carriers) < $payloadLenBits) {
                  imagedestroy($img);
                  throw new \Exception("Capacité insuffisante: carriers=" . count($carriers) . " bits needed=$payloadLenBits");
            }

            // Build bits array
            for ($i = 0; $i < strlen($payload); $i++) {
                  $b = ord($payload[$i]);

                  for ($bit = 7; $bit >= 0; $bit--)
                        $bits[] = ($b >> $bit) & 1;
            }

            // Embed bits into LSB of blue channel of carrier pixels (in collected order)
            for ($i = 0; $i < $payloadLenBits; $i++) {
                  [$px, $py] = $carriers[$i];
                  $col = imagecolorat($img, $px, $py);
                  $r = ($col >> 16) & 0xFF;
                  $g = ($col >> 8) & 0xFF;
                  $b = $col & 0xFF;
                  $newB = ($b & ~1) | $bits[$i];

                  // allocate or reuse color
                  $newColor = imagecolorallocate($img, $r, $g, $newB);
                  imagesetpixel($img, $px, $py, $newColor);
            }

            // Save as PNG to preserve exact bits
            $ok = imagepng($img, $outPath);
            imagedestroy($img);

            return $ok;
      }

      /**
       * Extract message previously embedded with embedMessageInImageFractal.
       *
       * @param string $srcPath
       * @param array|null $bounds
       * @return string extracted message
       * @throws Exception
       */
      public function Decrypt(
            string $srcPath,
            ?array $bounds = null
      ): string {
            $this->checkDependances($srcPath);

            $img = imagecreatefromstring(file_get_contents($srcPath));
            if ($img === false) throw new \Exception("Impossible de charger l'image");
            $w = imagesx($img);
            $h = imagesy($img);
            $bounds = $bounds ?? [-1.8, 1.8, -1.2, 1.2];

            $carriers = [];
            for ($py = 0; $py < $h; $py++) {
                  for ($px = 0; $px < $w; $px++) {
                        [$zx, $zy] = $this->pixelToComplex($px, $py, $w, $h, $bounds);

                        $iter = $this->juliaIterations(
                              $zx,
                              $zy,
                              $this->Options->RealPartNumber,
                              $this->Options->UnRealPartNumber,
                              $this->Options->MaximalIteration
                        );

                        if ($iter === $this->Options->MaximalIteration)
                              $carriers[] = [$px, $py];
                  }
            }

            // Read first 32 bits to get length
            $bits = [];
            for ($i = 0; $i < 32; $i++) {
                  [$px, $py] = $carriers[$i];
                  $col = imagecolorat($img, $px, $py);
                  $b = $col & 0xFF;
                  $bits[] = $b & 1;
            }

            // Convert first 32 bits to length (big-endian)
            $len = 0;
            for ($i = 0; $i < 32; $i++)
                  $len = ($len << 1) | $bits[$i];

            if ($len === 0) {
                  imagedestroy($img);
                  return "";
            }

            $totalBits = 32 + $len * 8;
            if (count($carriers) < $totalBits) {
                  imagedestroy($img);
                  throw new \Exception("Image ne contient pas assez de carriers pour le message (need $totalBits, have " . count($carriers) . ")");
            }

            $outBytes = '';
            for ($byteIdx = 0; $byteIdx < $len; $byteIdx++) {
                  $b = 0;
                  for ($bit = 0; $bit < 8; $bit++) {
                        $i = 32 + $byteIdx * 8 + $bit;
                        [$px, $py] = $carriers[$i];
                        $col = imagecolorat($img, $px, $py);
                        $blue = $col & 0xFF;
                        $bitVal = $blue & 1;
                        $b = ($b << 1) | $bitVal;
                  }

                  $outBytes .= chr($b);
            }

            imagedestroy($img);
            return $outBytes;
      }

      /**
       * Hachage fractal (expérimental)
       *
       * @param string $data input data
       * @param float $c_re paramètre c réel (Julia)
       * @param float $c_im paramètre c imaginaire (Julia)
       * @param int $iterPerByte nombre d'itérations fractales par octet
       * @param int $finalRounds itérations supplémentaires sur l'état final
       * @return string hex digest (sha256) - 64 hex chars
       */
      public function Hash(string $data, int $iterPerByte = 100, int $finalRounds = 500): string
      {
            // état initial
            $zx = 0.0;
            $zy = 0.0;

            // buffer binaire pour accumulation
            $acc = '';

            $len = strlen($data);
            for ($i = 0; $i < $len; $i++) {
                  $b = ord($data[$i]);

                  // small perturbation from byte value
                  $perturb = ($b / 255.0) - 0.5; // in [-0.5,0.5]
                  $zx += 0.01 * $perturb;
                  $zy += 0.013 * ($perturb); // slightly different scale

                  // iterate fractal a few times
                  for ($k = 0; $k < $iterPerByte; $k++) {
                        $xt = $zx * $zx - $zy * $zy + $this->Options->RealPartNumber;
                        $zy = 2 * $zx * $zy + $this->Options->UnRealPartNumber;
                        $zx = $xt;
                  }

                  // append packed doubles (platform dependent) — acceptable for demo
                  $acc .= pack('d', $zx);
                  $acc .= pack('d', $zy);
            }

            // final extra chaotic mixing
            for ($r = 0; $r < $finalRounds; $r++) {
                  $xt = $zx * $zx - $zy * $zy + $this->Options->RealPartNumber;
                  $zy = 2 * $zx * $zy + $this->Options->UnRealPartNumber;
                  $zx = $xt;
                  // append fractional part influence
                  $acc .= pack('d', $zx * 0.123456 + $zy * 0.654321);
            }

            // derive final digest via SHA-256 of the chaotic buffer
            $digest = hash('sha256', $acc);
            return $digest;
      }
}
