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

abstract class EncryptionMode
{
    public const Caesar = "caesar";
    public const Framework_V1 = "V1";
    public const Framework_V2 = "V2";
    public const Framework_V3 = "V3";

    public const AES = "aes";
    public const AES_128 = "aes128";
    public const AES_256 = "aes256";
    public const SHA = "sha";
    public const SHA_1 = "sha1";
    public const SHA_128 = "sha128";
    public const SHA_256 = "sha256";
    public const SHA_512 = "sha512";
    public const MD5 = "md5";
    public const Base64 = "B64";
    public const Hex = "HEX";

    public const MD5_Value = 0x10;
    public const RSA_Value = 0x20;
    public const SHA256_Value = 0x30;
    public const SHA512_Value = 0x40;
    public const X509_Value = 0x50;
    public const SHA1_Value = 0x70;
    public const AES_Value = 0x80;
    public const Caesar_Value = 0x90;
    public const PlayFair_Value = 0x91;

    public const AES_256_ProtocolName = "AES-256-CBC";
    public const DES_ProtocolName = "des-ede3-cbc";

    public const Fractal = "[FRACTALS]";
}
