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

namespace System\Text\Encoding;

final class UnicodeEncoding
{
    private $bigEndian;
    private $byteOrderMark;

    // Constructeur
    public function __construct(bool $bigEndian = false, bool $byteOrderMark = true)
    {
        $this->bigEndian = $bigEndian;
        $this->byteOrderMark = $byteOrderMark;
    }

    // Convertir une chaîne en tableau d'octets (UTF-16)
    public function GetBytes(string $s): string
    {
        $encoded = mb_convert_encoding($s, 'UTF-16', 'UTF-8');

        // Ajouter un BOM si nécessaire
        if ($this->byteOrderMark) {
            $bom = "\xFF\xFE"; // BOM pour UTF-16 Little Endian
            if ($this->bigEndian) {
                $bom = "\xFE\xFF"; // BOM pour UTF-16 Big Endian
            }
            $encoded = $bom . $encoded;
        }

        return $encoded;
    }

    // Convertir un tableau d'octets en chaîne (UTF-16)
    public function GetString(string $bytes): string
    {
        $decoded = mb_convert_encoding($bytes, 'UTF-8', 'UTF-16');
        return $decoded;
    }

    // Obtenir la valeur de la propriété 'bigEndian'
    public function GetBigEndian(): bool
    {
        return $this->bigEndian;
    }

    // Modifier la valeur de la propriété 'bigEndian'
    public function SetBigEndian(bool $bigEndian): void
    {
        $this->bigEndian = $bigEndian;
    }

    // Obtenir la valeur de la propriété 'byteOrderMark'
    public function GetByteOrderMark(): bool
    {
        return $this->byteOrderMark;
    }

    // Modifier la valeur de la propriété 'byteOrderMark'
    public function SetByteOrderMark(bool $byteOrderMark): void
    {
        $this->byteOrderMark = $byteOrderMark;
    }
}
