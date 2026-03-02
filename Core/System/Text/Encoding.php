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

namespace System\Text;

use System\Text\Enumerations\Encoding as EEncoding;

abstract class Encoding
{
    // Méthode pour obtenir l'encodage en UTF-8 d'une chaîne
    public static function UTF8(): string
    {
        return EEncoding::UTF8;
    }

    // Méthode pour obtenir l'encodage ASCII d'une chaîne
    public static function ASCII(): string
    {
        return EEncoding::ASCII;
    }

    // Convertir une chaîne en tableau d'octets en fonction de l'encodage spécifié
    public static function GetBytes(string $string, string $encoding = EEncoding::UTF8): string
    {
        // On vérifie si l'encodage est supporté par mb_convert_encoding
        if (!in_array($encoding, mb_list_encodings())) {
            throw new \InvalidArgumentException("Encoding non supporté: $encoding");
        }

        return mb_convert_encoding($string, $encoding);
    }

    // Convertir un tableau d'octets en chaîne en fonction de l'encodage spécifié
    public static function GetString(string $bytes, string $encoding = EEncoding::UTF8): string
    {
        // Vérifier si l'encodage est valide
        if (!in_array($encoding, mb_list_encodings())) {
            throw new \InvalidArgumentException("Encoding non supporté: $encoding");
        }

        return mb_convert_encoding($bytes, 'UTF-8', $encoding);
    }

    // Convertir un tableau d'octets en chaîne selon l'encodage spécifié
    public static function GetStringFromBytes(string $bytes, string $encoding = EEncoding::UTF8): string
    {
        // Utiliser mb_convert_encoding pour décoder les octets
        return self::GetString($bytes, $encoding);
    }

    // Convertir une chaîne en tableau d'octets selon l'encodage spécifié
    public static function GetBytesFromString(string $string, string $encoding = EEncoding::UTF8): string
    {
        return self::GetBytes($string, $encoding);
    }
}
