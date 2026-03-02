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

class Sierpinski extends AbsFractal
{
      public function __construct(FractalOptions $_options)
      {
            parent::__construct($_options);
      }

      /**
       * Triangle de Sierpinski
       * Recommended Depth : 7
       */
      public function Draw(Size $_size, ?string $outfile = null): null|array
      {
            $img = imagecreatetruecolor($_size->Width, $_size->Height);
            $bg = imagecolorallocate($img, 0, 0, 0);
            $col = imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $bg);

            function drawTriangle($img, $x1, $y1, $x2, $y2, $x3, $y3, $depth, $col)
            {
                  if ($depth == 0) {
                        imagefilledpolygon($img, [$x1, $y1, $x2, $y2, $x3, $y3], 3, $col);
                        return;
                  }
                  $mx12 = ($x1 + $x2) / 2;
                  $my12 = ($y1 + $y2) / 2;
                  $mx23 = ($x2 + $x3) / 2;
                  $my23 = ($y2 + $y3) / 2;
                  $mx31 = ($x3 + $x1) / 2;
                  $my31 = ($y3 + $y1) / 2;

                  drawTriangle($img, $x1, $y1, $mx12, $my12, $mx31, $my31, $depth - 1, $col);
                  drawTriangle($img, $x2, $y2, $mx23, $my23, $mx12, $my12, $depth - 1, $col);
                  drawTriangle($img, $x3, $y3, $mx31, $my31, $mx23, $my23, $depth - 1, $col);
            }

            drawTriangle(
                  $img,
                  $_size->Width / 2,
                  10,
                  10,
                  $_size->Height - 10,
                  $_size->Width - 10,
                  $_size->Height - 10,
                  $this->Options->Depth,
                  $col
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
