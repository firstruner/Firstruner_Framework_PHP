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

namespace System\Security\Cryptography;

use System\Annotations\NotImplemented;
use System\Security\Cryptography\Methods\CaesarCipher;
use System\Default\_string;

class Encryption
{
    private const cipher = 'aes-256-cbc';

    public static function encrypt(string $mode, string $value, string $key)
    {
        switch ($mode) {
            case EncryptionMode::Caesar:
                return self::encrypt_Caesar($value, intval($key));
            case EncryptionMode::Framework_V1:
                return self::encrypt_V1($value, strval($key));
            case EncryptionMode::Framework_V2:
                return self::encrypt_V2($value, $key);
            case EncryptionMode::Base64:
                return self::encrypt_Base64($value);
            case EncryptionMode::Hex:
                return self::encrypt_Hexa($value);
            default:
                if (!Encryption::HasAvailableMethods($mode))
                    throw new NotImplemented();

                return self::encrypt_common($mode, $value, $key);
        }
    }

    public static function decrypt(string $mode, string $value, string $key): string
    {
        switch ($mode) {
            case EncryptionMode::Caesar:
                return self::decrypt_Caesar($value, intval($key));
            case EncryptionMode::Base64:
                return self::decrypt_Base64($value);
            case EncryptionMode::Hex:
                return self::decrypt_Hexa($value);
            default:
                if (!Encryption::HasAvailableMethods($mode))
                    throw new NotImplemented();

                return self::decrypt_common($mode, $value, $key);
        }
    }

    public static function GetAlgorithms(): array
    {
        $algos = array();
        $sampleDatas = "NoDataToEncode";

        foreach (hash_algos() as $hasher) {
            $rst = hash($hasher, $sampleDatas, false);
            array_push(
                $algos,
                new Algorithm($hasher, strlen($rst))
            );
        }

        return $algos;
    }

    public static function HasAvailableMethods(string $mode): bool
    {
        return in_array($mode, hash_algos());
    }

    private static function encrypt_common(string $mode, string $plaintext, string $key): string
    {
        $key = hash($mode, $key, true);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(Encryption::cipher));
        $ciphertext = openssl_encrypt($plaintext, Encryption::cipher, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $ciphertext);
    }

    private static function decrypt_common(string $mode, string $encrypted, string $key): string
    {
        $key = hash($mode, $key, true);
        $data = base64_decode($encrypted);
        $iv_length = openssl_cipher_iv_length(Encryption::cipher);
        $iv = substr($data, 0, $iv_length);
        $ciphertext = substr($data, $iv_length);

        return openssl_decrypt($ciphertext, Encryption::cipher, $key, OPENSSL_RAW_DATA, $iv);
    }

    private static function encrypt_V1($value, $key)
    {
        $valK = 0;

        $cipher = new CaesarCipher();
        $value = $cipher->encrypt($value);

        for ($i = 0; $i < strlen($key); $i++)
            $valK += ord(substr($key, $i, 1));

        $valK = $valK % strlen($key);

        $final = _string::EmptyString;

        for ($i = 0; $i < strlen($value); $i++) {
            $final = $final . str_pad(
                number_format(
                    ord(
                        substr($value, $i, 1)
                    ) + $valK,
                    0,
                    '',
                    ''
                ),
                4,
                '0',
                STR_PAD_LEFT
            );
        }

        return $final;
    }

    private static function encrypt_V2($value, $keypart)
    {
        $keyA = date('z') + 1;
        $keyB = $keypart % $keyA;
        $finalV = _string::EmptyString;

        for ($i = 0; $i < strlen($value); $i++)
            $finalV = $finalV . str_pad((ord(substr($value, $i, 1)) + $keyB), 3, '0', STR_PAD_LEFT);

        return $finalV;
    }

    private static function encrypt_Caesar($value, $key)
    {
        $cipher = new CaesarCipher();
        $value = $cipher->encrypt($value, $key);
        return $value;
    }

    private static function decrypt_Caesar($value, $key)
    {
        $cipher = new CaesarCipher();
        $value = $cipher->decrypt($value, $key);
        return $value;
    }

    private static function encrypt_Base64($value): string
    {
        return base64_encode($value);
    }

    private static function decrypt_Base64($value): string
    {
        return base64_decode($value);
    }

    private static function encrypt_Hexa($value): string
    {
        return bin2hex($value);
    }

    private static function decrypt_Hexa($value): string
    {
        return hex2bin($value);
    }
}
