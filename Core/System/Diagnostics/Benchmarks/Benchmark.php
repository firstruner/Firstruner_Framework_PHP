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

namespace System\Diagnostics;

use System\Default\_string;
use System\Guid;

final class Benchmark
{
    public readonly string $Name;
    public readonly Guid $UID;

    private string|float $startTimes;
    private string|float $endTimes;

    public function __construct(string $name = _string::EmptyString)
    {
        $this->Name = $name;
        $this->UID = Guid::NewGuid();
    }

    public function start(): Guid
    {
        $this->startTimes = microtime(true);
        return $this->UID;
    }

    public function stop(): string|float
    {
        if (!isset($this->startTimes))
            throw new \Exception($this->Name . " (" . $this->UID . ") n'est pas démarrer");

        $this->endTimes = microtime(true);
        return $this->endTimes - $this->startTimes;
    }

    public function getElapsedTime(): string|float
    {
        if (!isset($this->endTimes))
            return microtime(true) - $this->startTimes;

        return $this->endTimes - $this->startTimes;
    }

    public function getFormattedTime($decimals = 6): string|float
    {
        if (!isset($this->endTimes))
            throw new \Exception($this->Name . " (" . $this->UID . ") n'est pas arrêter");

        return number_format(($this->endTimes - $this->startTimes), $decimals) . ' seconds';
    }
}
