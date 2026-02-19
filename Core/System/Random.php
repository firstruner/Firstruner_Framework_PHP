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

namespace System;

use System\Default\_int;

class Random
{
    private int $seed;

    public function __construct(int $seed = null)
    {
        $this->seed = $seed ?? \random_int(_int::MinValue, _int::MaxValue);
        \mt_srand($this->seed);
    }

    public function Next(int $min = 0, int $max = _int::MaxValue): int
    {
        return \mt_rand($min, $max);
    }

    public function NextDouble(): float
    {
        return \mt_rand() / \mt_getrandmax();
    }

    public function NextBytes(int $length): string
    {
        return \random_bytes($length);
    }

    public function NextInt(int $maxValue = _int::MaxValue): int
    {
        return $this->Next(0, $maxValue - 1);
    }

    public function NextIntRange(int $minValue, int $maxValue): int
    {
        return $this->Next($minValue, $maxValue);
    }

    public function Sample(): float
    {
        return $this->NextDouble();
    }

    public function ReSeed(int $newSeed): void
    {
        $this->seed = $newSeed;
        \mt_srand($this->seed);
    }

    public function GetSeed(): int
    {
        return $this->seed;
    }
}
