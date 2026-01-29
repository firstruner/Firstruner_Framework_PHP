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

final class MD5CryptoServiceProvider
{
    private string $hashValue;
    private bool $disposed = false;

    public function __construct()
    {
        $this->hashValue = '';
    }

    // Compute hash of the given data
    public function ComputeHash(string $data): string
    {
        $this->hashValue = md5($data, true); // Return raw binary hash
        return $this->hashValue;
    }

    // Transform the hash into a readable hexadecimal string
    public function ComputeHashHex(string $data): string
    {
        return md5($data); // Return hex hash by default
    }

    // Get the current hash value in raw format
    public function Hash(): string
    {
        return $this->hashValue;
    }

    // Dispose method to clean up
    public function Dispose(): void
    {
        if ($this->disposed) {
            return;
        }

        // Reset hash value
        $this->hashValue = '';
        $this->disposed = true;
    }

    // Check if disposed
    public function Disposed(): bool
    {
        return $this->disposed;
    }

    // Clear the hash value, essentially resets the object
    public function Clear(): void
    {
        $this->hashValue = '';
        $this->disposed = false;
    }
}
