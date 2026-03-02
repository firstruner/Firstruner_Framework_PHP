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

namespace Firstruner\Classes\Bank;

use System\Default\_string;
use System\Exceptions\BankingException;

abstract class AbsBankingAccounts
{
    protected const DefaultRibKey = "97";
    protected const DefaultNumber = "0";

    protected function Sanitize_Number(string $chaineNombre): string
    {
        return preg_replace('/\D/', _string::EmptyString, $chaineNombre);
    }

    protected function Sanitize_Account(string $chaineNombre): string
    {
        $chaine = strtoupper($chaineNombre);
        $result = _string::EmptyString;

        for ($i = 0; $i < strlen($chaine); $i++) {
            $char = $chaine[$i];
            if (ctype_digit($char)) {
                $result .= $char;
            } elseif (ctype_upper($char)) {
                $nb = ord($char) - 64;
                if ($nb > 9) $nb -= 9;
                if ($nb > 9) $nb -= 8;
                $result .= $nb;
            } else {
                throw new BankingException("Caractères invalides");
            }
        }

        return $this->Sanitize_Account_Letters($result);
    }

    /**
     * Convertit les lettres d'un numéro de compte RIB en chiffres.
     *
     * Règle : A/J=1, B/K/S=2, C/L/T=3, ..., I/R/Z=9.
     *
     * @param string $rib
     * @return string
     */
    private function Sanitize_Account_Letters(string $account): string
    {
        // Tableau de correspondance lettre → chiffre
        $map = [
            'A' => '1',
            'J' => '1',
            'B' => '2',
            'K' => '2',
            'S' => '2',
            'C' => '3',
            'L' => '3',
            'T' => '3',
            'D' => '4',
            'M' => '4',
            'U' => '4',
            'E' => '5',
            'N' => '5',
            'V' => '5',
            'F' => '6',
            'O' => '6',
            'W' => '6',
            'G' => '7',
            'P' => '7',
            'X' => '7',
            'H' => '8',
            'Q' => '8',
            'Y' => '8',
            'I' => '9',
            'R' => '9',
            'Z' => '9',
        ];

        $str = _string::EmptyString;
        $chars = str_split(strtoupper($account)); // majuscules et découpage

        foreach ($chars as $ch) {
            if (ctype_digit($ch)) {
                // Si c’est déjà un chiffre → on garde
                $str .= $ch;
            } elseif (isset($map[$ch])) {
                // Si c’est une lettre connue dans le mapping → conversion
                $str .= $map[$ch];
            } else {
                // Caractère non prévu → soit on l’ignore, soit on lève une erreur
                // Ici, on l’ignore :
                continue;
            }
        }

        return $str;
    }

    protected function SpaceRemove(string $chaineNombre): string
    {
        $chaine = $chaineNombre;
        $result = _string::EmptyString;

        for ($i = 0; $i < strlen($chaine); $i++) {
            $char = $chaine[$i];
            $ascii = ord($char);

            if (ctype_digit($char)) {
                $result .= $char;
            } elseif (ctype_upper($char)) {
                $result .= $char;
            } elseif (ctype_lower($char)) {
                $result .= strtoupper($char);
            } else {
                throw new BankingException("Caractères invalides");
            }
        }

        return $result;
    }
}
