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

namespace System\Drawing;

class Color
{
    public readonly int $Red;
    public readonly int $Green;
    public readonly int $Blue;
    public readonly int $Alpha;

    private function __construct(int $Red, int $Green, int $Blue, int $Alpha = 255)
    {
        $this->Red = max(0, min(255, $Red));
        $this->Green = max(0, min(255, $Green));
        $this->Blue = max(0, min(255, $Blue));
        $this->Alpha = max(0, min(255, $Alpha));
    }

    public static function FromArgb(int $Alpha, int $Red, int $Green, int $Blue): Color
    {
        return new self($Red, $Green, $Blue, $Alpha);
    }

    public static function FromRGB(int $Red, int $Green, int $Blue): Color
    {
        return new self($Red, $Green, $Blue);
    }

    public static function FromName(string $named_name): ?Color
    {
        $constantName = Named_Color::class . "::" . ucfirst(strtoupper($named_name));

        if (!defined($constantName))
            throw new \Exception("Unknown named color");

        $constantColor = new self(...(constant($constantName))["RGB"]);
        return $constantColor;
    }

    public function ToHex(): string
    {
        return sprintf("#%02X%02X%02X", $this->Red, $this->Green, $this->Blue);
    }
}
