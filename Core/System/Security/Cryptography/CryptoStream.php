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

use System\OpenSSL;

final class CryptoStream
{
    private $stream;
    private $cipher;
    private string $key;
    private string $iv;
    private int $mode;

    public function __construct(string $cipher, string $key, string $iv, int $mode)
    {
        if (strlen($key) !== 32 || strlen($iv) !== 16) {
            throw new \InvalidArgumentException("Clé (32 octets) et IV (16 octets) requis.");
        }

        $this->cipher = $cipher;
        $this->key = $key;
        $this->iv = $iv;
        $this->mode = $mode;

        // Utilisation d'un flux mémoire
        $this->stream = fopen("php://memory", "w+");
    }

    public function Write(string $data): void
    {
        if ($this->mode !== OpenSSL::OPENSSL_ENCRYPT) {
            throw new \RuntimeException("CryptoStream est en mode déchiffrement.");
        }

        $encryptedData = \openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
        fwrite($this->stream, $encryptedData);
    }

    public function Read(): string
    {
        if ($this->mode !== OpenSSL::OPENSSL_DECRYPT) {
            throw new \RuntimeException("CryptoStream est en mode chiffrement.");
        }

        rewind($this->stream);
        $encryptedData = \stream_get_contents($this->stream);
        return \openssl_decrypt($encryptedData, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
    }

    public function GetEncryptedBytes(): string
    {
        if ($this->mode !== OpenSSL::OPENSSL_ENCRYPT) {
            throw new \RuntimeException("CryptoStream est en mode déchiffrement.");
        }

        rewind($this->stream);
        return stream_get_contents($this->stream);
    }


    public function Close(): void
    {
        fclose($this->stream);
    }
}
