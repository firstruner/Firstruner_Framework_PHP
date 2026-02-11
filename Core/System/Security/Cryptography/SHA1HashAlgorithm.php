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

final class SHA1HashAlgorithm extends HashAlgorithm
{
    protected string $algorithm = EncryptionMode::SHA_1;

    // Initialize method (sets hash size and prepares for computation)
    public function Initialize(): void
    {
        $this->hashValue = '';
        $this->hashSize = 160; // SHA-1 hash size in bits
    }

    // Method to compute hash for the input data
    public function ComputeHash(string $data): string
    {
        $this->hashValue = hash($this->algorithm, $data);
        return $this->hashValue;
    }
}
