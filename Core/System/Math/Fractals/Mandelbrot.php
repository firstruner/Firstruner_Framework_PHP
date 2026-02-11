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

class Mandelbrot extends AbsFractal
{
      public function __construct(FractalOptions $_options)
      {
            parent::__construct($_options);
      }

      /**
       * Mandelbrot Set
       * Recommended iteration : 300
       */
      public function Draw(Size $_size, ?string $outfile = null): null|array
      {
            $xmin = -2.5;
            $xmax = 1.0;
            $ymin = -1.2;
            $ymax = 1.2;
            $dx = ($xmax - $xmin) / ($_size->Width - 1);
            $dy = ($ymax - $ymin) / ($_size->Height - 1);

            $img = imagecreatetruecolor($_size->Width, $_size->Height);
            $black = imagecolorallocate($img, 0, 0, 0);

            for ($py = 0; $py < $_size->Height; $py++) {
                  $cy = $ymax - $py * $dy;
                  for ($px = 0; $px < $_size->Width; $px++) {
                        $cx = $xmin + $px * $dx;
                        $zx = 0;
                        $zy = 0;
                        $iter = 0;

                        while ($zx * $zx + $zy * $zy <= 4 && $iter < $this->Options->Depth) {
                              $tmp = $zx * $zx - $zy * $zy + $cx;
                              $zy = 2 * $zx * $zy + $cy;
                              $zx = $tmp;
                              $iter++;
                        }

                        $col = $iter == $this->Options->Depth
                              ? $black
                              : imagecolorallocate($img, $iter % 256, ($iter * 5) % 256, ($iter * 13) % 256);

                        imagesetpixel($img, $px, $py, $col);
                  }
            }

            if ($outfile) {
                  imagepng($img, $outfile);
                  return null;
            }

            ob_start();
            imagepng($img);
            $data = ob_get_clean(); // Contenu binaire PNG
            imagedestroy($img);

            return array_values(unpack('C*', $data));
      }
}
