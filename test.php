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

use Firstruner\Cryptography\EncryptDecryptModule;
use System\Guid;
use System\Security\Cryptography\EncryptionMode;

require_once('./loader.php');

$edm = new EncryptDecryptModule(
      [
            "8F2A7C9D4B6E1F3055A2C7D89E1134AB67C5D2E90F1A3B4C",
            "A1B2C3D4E5F60718293A4B5C6D7E8F90123456789ABCDEF0",
            "112233445566778899AABBCCDDEEFF001122334455667788",
            "F0E1D2C3B4A5968778695A4B3C2D1E0FFEDCBA9876543210",
            "9C4D2E1F7A8B6C5D4E3F20112233445566778899AABBCCDD",
            "0123456789ABCDEFFEDCBA98765432100123456789ABCDEF",
            "CAFEBABEDEADBEEF00112233445566778899AABBCCDDEEFF",
            "55AA55AA11221122FF00FF00AABBCCDDEEFF001122334455",
            "7F6E5D4C3B2A19080706050403020100FFEEDDCCBBAA9988",
            "ABCDEF01234567899876543210FEDCBA0011223344556677"
      ]
);
$enc_val = $edm->Encrypt(
       "Welcome to the real world !",
       EncryptionMode::MD5_Value);

echo $enc_val->Value();