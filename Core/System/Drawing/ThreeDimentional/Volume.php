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

namespace System\Drawing\ThreeDimentional;

use System\Drawing\ThreeDimentional\ISurface;
use System\Exception\ThreeDimentional\NoPlaceAvailable;

class Volume extends Surface implements ISurface
{
      public float $hauteur = 0;
      protected array $internalVolumes;

      public function __construct(Surface $_surface, float $_hauteur)
      {
            parent::__construct($_surface->Position, $_surface->Superficie);
            $this->hauteur = $_hauteur;
            $this->internalVolumes = [];
      }

      public function Volume(): float
      {
            return $this->hauteur * $this->GetSuperficie();
      }

      protected function addVolume(Volume $_volume)
      {
            $totalAire = (float)0;
            $totalVol = (float)0;

            foreach ($this->internalVolumes as $vol) {
                  $totalAire += $vol->GetSuperficie();
                  $totalVol += $vol->Volume();
            }

            if (($this->GetSuperficie() < $totalAire)
                  || ($this->Volume() < $totalVol)
            )
                  throw new NoPlaceAvailable();

            array_push(
                  $this->internalVolumes,
                  $_volume
            );
      }
}
