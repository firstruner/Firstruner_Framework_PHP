<?php

/**
* Copyright since 2024 Firstruner and Contributors
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
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/

namespace System\Drawing;

class Color {
    private int $r;
    private int $g;
    private int $b;
    private int $a;

    private function __construct(int $r, int $g, int $b, int $a = 255) {
        $this->r = max(0, min(255, $r));
        $this->g = max(0, min(255, $g));
        $this->b = max(0, min(255, $b));
        $this->a = max(0, min(255, $a));
    }
  
    public static function FromArgb(int $a, int $r, int $g, int $b): Color {
        return new self($r, $g, $b, $a);
    }
  
    public static function FromName(string $name): ?Color {
        $constantName = "Named_Color::" . ucfirst(strtoupper($name));

        if (!defined($constantName))
            throw new \Exception("Unknown color");

        $constantColor = new self(...(constant($constantName))["RGB"]);
        return $constantColor;
    }

    public function ToHex(): string {
        return sprintf("#%02X%02X%02X", $this->r, $this->g, $this->b);
    }
}
  