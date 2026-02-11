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

class Tree extends AbsFractal
{
      public function __construct(FractalOptions $_options)
      {
            parent::__construct($_options);
      }

      /**
       * Arbre fractal (récursif)
       * Recommended Depth : 10
       */
      public function Draw(Size $_size, ?string $outfile = null): null|array
      {
            $img = imagecreatetruecolor($_size->Width, $_size->Height);
            $bg = imagecolorallocate($img, 20, 20, 20);
            $white = imagecolorallocate($img, 200, 255, 200);
            imagefill($img, 0, 0, $bg);

            function drawBranch($img, $x1, $y1, $angle, $depth, $color)
            {
                  if ($depth == 0) return;
                  $length = $depth * 10;
                  $x2 = $x1 + cos($angle) * $length;
                  $y2 = $y1 - sin($angle) * $length;

                  imageline($img, $x1, $y1, $x2, $y2, $color);

                  drawBranch($img, $x2, $y2, $angle - deg2rad(20), $depth - 1, $color);
                  drawBranch($img, $x2, $y2, $angle + deg2rad(20), $depth - 1, $color);
            }

            drawBranch(
                  $img,
                  $_size->Width / 2,
                  $_size->Height - 20,
                  deg2rad(90),
                  $this->Options->Depth,
                  $white
            );

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
