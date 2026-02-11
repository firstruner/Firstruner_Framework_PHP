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

final class KochProvider
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

        $koch = function ($x1, $y1, $x2, $y2, $depth) use (&$koch, &$carriers) {
            if ($depth == 0) {
                $carriers[] = ((int)$x1 + (int)$y1 + (int)$x2 + (int)$y2) % 256;
                return;
            }
            $dx = ($x2 - $x1) / 3;
            $dy = ($y2 - $y1) / 3;

            $xA = $x1 + $dx;
            $yA = $y1 + $dy;
            $xB = $x1 + 2 * $dx;
            $yB = $y1 + 2 * $dy;
            $xC = $xA + cos(M_PI / 3) * ($xB - $xA) - sin(M_PI / 3) * ($yB - $yA);
            $yC = $yA + sin(M_PI / 3) * ($xB - $xA) + cos(M_PI / 3) * ($yB - $yA);

            $koch($x1, $y1, $xA, $yA, $depth - 1);
            $koch($xA, $yA, $xC, $yC, $depth - 1);
            $koch($xC, $yC, $xB, $yB, $depth - 1);
            $koch($xB, $yB, $x2, $y2, $depth - 1);
        };

        $koch(0, 0, $this->Options->Size->Width, 0, $this->Options->MaximalIteration);

        return $carriers ?: [3];
    }
}
