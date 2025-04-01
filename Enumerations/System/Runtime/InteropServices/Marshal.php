<?php

/**
* Copyright since 2024 Firstruner and Contributors
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
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/

namespace System\Runtime\InteropService;

use System\Exceptions\COMException;

final class Marshal
{
    /**
     * Convertit une chaîne en tableau d'octets (Byte Array)
     */
    public static function StringToByteArray(string $str): array
    {
        return array_map('ord', str_split($str));
    }

    /**
     * Convertit un tableau d'octets en chaîne
     */
    public static function ByteArrayToString(array $bytes): string
    {
        return implode(array_map('chr', $bytes));
    }

    /**
     * Alloue un bloc de mémoire et retourne un pointeur simulé (adresse mémoire en hexadécimal)
     */
    public static function AllocHGlobal(int $size): string
    {
        return bin2hex(random_bytes($size));
    }

    /**
     * Copie un tableau d'octets vers une chaîne simulant un pointeur mémoire
     */
    public static function Copy(array $source, int $startIndex, int $length): string
    {
        return self::ByteArrayToString(array_slice($source, $startIndex, $length));
    }

    /**
     * Renvoie la taille en octets d'une variable
     */
    public static function SizeOf(mixed $value): int
    {
        return strlen(serialize($value));
    }

    /**
     * Convertit une variable en une représentation binaire
     */
    public static function StructToBinary(mixed $value): string
    {
        return serialize($value);
    }

    /**
     * Convertit une représentation binaire en variable
     */
    public static function BinaryToStruct(string $binary): mixed
    {
        return unserialize($binary);
    }

    /**
     * Convertit un tableau d'octets en une valeur hexadécimale
     */
    public static function ByteArrayToHex(array $bytes): string
    {
        return bin2hex(implode(array_map('chr', $bytes)));
    }

    /**
     * Convertit une chaîne hexadécimale en tableau d'octets
     */
    public static function HexToByteArray(string $hex): array
    {
        return array_map('ord', str_split(hex2bin($hex)));
    }

    /**
     * Copie un bloc mémoire d'une source vers une destination
     */
    public static function MemoryCopy(string $source, string &$destination, int $length): void
    {
        $destination = substr($source, 0, $length);
    }

    /**
     * Efface la mémoire en mettant des zéros sur toute la longueur d'une chaîne
     */
    public static function ZeroMemory(string &$memory): void
    {
        $memory = str_repeat("\0", strlen($memory));
    }

    /**
     * Simule la méthode GetHRForException, retourne un code d'erreur COM à partir d'une exception
     */
    public static function GetHRForException(\Throwable $exception): int
    {
        // Le code d'erreur COM est un entier de 32 bits.
        // On peut utiliser un code d'erreur générique, ou personnaliser davantage.
        // Exemple de code : -2147024894 (code pour E_FAIL, erreur générique)
        
        // Si l'exception est une instance de COMException, on récupère son code HRESULT
        if ($exception instanceof COMException) {
            return $exception->getCode(); // Code HRESULT de l'exception COM
        }
        
        // Sinon, on retourne un code d'erreur générique.
        // Utilisation d'un code HRESULT d'échec générique (E_FAIL).
        return 0x80004005; // HRESULT pour E_FAIL
    }
}

