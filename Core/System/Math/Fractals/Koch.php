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

class Koch extends AbsFractal
{
      public function __construct(FractalOptions $_options)
      {
            parent::__construct($_options);
      }

      /**
       * Courbe de Koch (Flocon de neige)
       * Recommended Depth : 5
       */
      public function Draw(Size $_size, ?string $outfile = null): null|array
      {
            $img = imagecreatetruecolor($_size->Width, $_size->Height);
            $bg = imagecolorallocate($img, 0, 0, 30);
            $col = imagecolorallocate($img, 200, 200, 255);
            imagefill($img, 0, 0, $bg);

            function drawKoch($img, $x1, $y1, $x2, $y2, $depth, $col)
            {
                  if ($depth == 0) {
                        imageline($img, $x1, $y1, $x2, $y2, $col);
                  } else {
                        $dx = $x2 - $x1;
                        $dy = $y2 - $y1;
                        $x3 = $x1 + $dx / 3;
                        $y3 = $y1 + $dy / 3;
                        $x5 = $x1 + 2 * $dx / 3;
                        $y5 = $y1 + 2 * $dy / 3;
                        $angle = atan2($dy, $dx) - pi() / 3;
                        $len = sqrt($dx * $dx + $dy * $dy) / 3;
                        $x4 = $x3 + cos($angle) * $len;
                        $y4 = $y3 + sin($angle) * $len;

                        drawKoch($img, $x1, $y1, $x3, $y3, $depth - 1, $col);
                        drawKoch($img, $x3, $y3, $x4, $y4, $depth - 1, $col);
                        drawKoch($img, $x4, $y4, $x5, $y5, $depth - 1, $col);
                        drawKoch($img, $x5, $y5, $x2, $y2, $depth - 1, $col);
                  }
            }

            $p1 = [$_size->Width * 0.1, $_size->Height * 0.8];
            $p2 = [$_size->Width * 0.9, $_size->Height * 0.8];
            $p3 = [$_size->Width * 0.5, $_size->Height * 0.1];

            drawKoch($img, $p1[0], $p1[1], $p2[0], $p2[1], $this->Options->Depth, $col);
            drawKoch($img, $p2[0], $p2[1], $p3[0], $p3[1], $this->Options->Depth, $col);
            drawKoch($img, $p3[0], $p3[1], $p1[0], $p1[1], $this->Options->Depth, $col);

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
