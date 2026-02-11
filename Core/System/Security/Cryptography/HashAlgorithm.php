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

namespace System\Security\Cryptography;

abstract class HashAlgorithm
{
    protected string $algorithm;
    protected bool $canReuseTransform = true;
    protected bool $canTransformMultipleBlocks = true;
    protected int $hashSize;
    protected ?string $hashValue = null;

    public function ComputeHash(string $input): string
    {
        $this->hashValue = hash($this->algorithm, $input, true);
        return $this->hashValue;
    }

    public function HashFinal(): string
    {
        return $this->hashValue ?? throw new \RuntimeException("Aucun hachage calculé.");
    }

    public function Initialize(): void
    {
        $this->hashValue = null;
    }

    public function TransformBlock(string $input): string
    {
        return hash($this->algorithm, $input, true);
    }

    public function TransformFinalBlock(string $input): string
    {
        return $this->ComputeHash($input);
    }

    public function HashSize(): int
    {
        return $this->hashSize;
    }

    // Method to get the computed hash value
    public function GetHash(): string
    {
        return $this->hashValue;
    }

    // Method to get the hash size
    public function GetHashSize(): int
    {
        return $this->hashSize;
    }

    // Disposing of resources (if any)
    public function Dispose()
    {
        $this->hashValue = null;
        $this->hashSize = 0;
    }
}
