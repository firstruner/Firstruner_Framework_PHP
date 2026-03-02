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

namespace System\Security;

use System\Default\_string;
use System\Security\Cryptography\EncryptionMode;

final class SecureString
{
    private string $encryptedData;
    private string $encryptionKey;

    public function __construct(string $input = _string::EmptyString)
    {
        $this->encryptionKey = bin2hex(\random_bytes(16)); // Génère une clé aléatoire
        $this->encryptedData = $this->Encrypt($input);
    }

    private function Encrypt(string $data): string
    {
        return base64_encode(openssl_encrypt($data, EncryptionMode::AES_256_ProtocolName, $this->encryptionKey, 0, substr($this->encryptionKey, 0, 16)));
    }

    private function Decrypt(): string
    {
        return openssl_decrypt(base64_decode($this->encryptedData), EncryptionMode::AES_256_ProtocolName, $this->encryptionKey, 0, substr($this->encryptionKey, 0, 16)) ?: '';
    }

    public function Append(string $input): void
    {
        $decrypted = $this->Decrypt();
        $this->encryptedData = $this->Encrypt($decrypted . $input);
    }

    public function Clear(): void
    {
        $this->encryptedData = $this->Encrypt(_string::EmptyString);
    }

    public function Length(): int
    {
        return strlen($this->Decrypt());
    }

    public function Insert(int $index, string $input): void
    {
        $decrypted = $this->Decrypt();
        if ($index < 0 || $index > strlen($decrypted)) {
            throw new \OutOfBoundsException("Index hors limites");
        }
        $updated = substr($decrypted, 0, $index) . $input . substr($decrypted, $index);
        $this->encryptedData = $this->Encrypt($updated);
    }

    public function Remove(int $index, int $length): void
    {
        $decrypted = $this->Decrypt();
        if ($index < 0 || $index + $length > strlen($decrypted)) {
            throw new \OutOfBoundsException("Index ou longueur hors limites");
        }
        $updated = substr($decrypted, 0, $index) . substr($decrypted, $index + $length);
        $this->encryptedData = $this->Encrypt($updated);
    }

    public function Equals(SecureString $other): bool
    {
        return $this->Decrypt() === $other->Decrypt();
    }

    public function Copy(): SecureString
    {
        return new SecureString($this->Decrypt());
    }

    public function ToString(): string
    {
        return $this->Decrypt();
    }

    public function Dispose(): void
    {
        $this->clear();
    }

    function __destruct()
    {
        $this->Dispose();
    }
}
