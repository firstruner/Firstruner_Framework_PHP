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

use ArrayObject;
use System\Default\_string;
use System\Guid;

class BenchmarkCollection extends ArrayObject
{
    private function find(string|Guid $name_uid): ?Benchmark
    {
        foreach ($this as $benchmark)
            if ((is_string($name_uid) ? $benchmark->Name : $benchmark->UID) == $name_uid)
                return $benchmark;

        return null;
    }

    public function start(string $name = _string::EmptyString): Guid
    {
        if ($this->find($name) != null)
            throw new \Exception("$name existe déjà !");

        $benchmark = new Benchmark($name);
        $benchmark->start();

        $this->append($benchmark);

        return $benchmark->UID;
    }

    public function stop(string|Guid $name_uid): string|float
    {
        $benchmark = $this->find($name_uid);

        if ($benchmark == null)
            throw new \Exception("$name_uid n'existe pas !");

        return $benchmark->stop();
    }

    public function getElapsedTime(string|Guid $name_uid): string|float
    {
        $benchmark = $this->find($name_uid);

        if ($benchmark == null)
            throw new \Exception("$name_uid n'existe pas !");

        return $benchmark->getElapsedTime();
    }

    public function getAllTimings(): array
    {
        $output = [];

        foreach ($this as $benchmark)
            $output[$benchmark->UID] = new class($benchmark)
            {
                public readonly Benchmark $Sender;
                public readonly string|float $ElapsedTime;

                public function __construct(Benchmark $benchmark)
                {
                    $this->Sender = $benchmark;
                    $this->ElapsedTime = $benchmark->getElapsedTime();
                }
            };

        return $output;
    }

    public function getFormattedTime(string|Guid $name_uid, $decimals = 6)
    {
        $benchmark = $this->find($name_uid);

        if ($benchmark == null)
            throw new \Exception("$name_uid n'existe pas !");

        return $benchmark->getFormattedTime($decimals);
    }

    public function printAllTimings($decimals = 6)
    {
        $output = [];

        foreach ($this as $benchmark)
            $output[$benchmark->UID] = new class($benchmark, $decimals)
            {
                public readonly Benchmark $Sender;
                public readonly string|float $ElapsedTime;

                public function __construct(Benchmark $benchmark, $decimals)
                {
                    $this->Sender = $benchmark;
                    $this->ElapsedTime = $benchmark->getFormattedTime($decimals);
                }
            };

        return $output;
    }
}
