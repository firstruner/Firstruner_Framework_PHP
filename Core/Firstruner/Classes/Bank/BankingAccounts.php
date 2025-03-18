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
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright Since 2024 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace Firstruner\Classes\Bank;

use System\Default\_string;
use System\Exceptions\BankingException;

abstract class BankingAccounts
{
    private const DefaultRibKey = "97";
    private const DefaultNumber = "0";

    private static function Sanitize_Number(string $chaineNombre) : string {
        return preg_replace('/\D/', _string::EmptyString, $chaineNombre);
    }

    private static function Sanitize_Account(string $chaineNombre) : string {
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

        return $result;
    }

    private static function Sanitize_IBAN(string $chaineNombre) : string {
        $chaine = $chaineNombre;
        $result = _string::EmptyString;

        for ($i = 0; $i < strlen($chaine); $i++) {
            $char = $chaine[$i];
            $ascii = ord($char);
            if (ctype_digit($char)) {
                $result .= $char;
            } elseif (ctype_upper($char)) {
                $result .= $ascii - 55; // A=10 ... Z=35
            } elseif (ctype_lower($char)) {
                $result .= $ascii - 87; // a=10 ... z=35
            } else {
                throw new BankingException("Caractères invalides");
            }
        }

        return $result;
    }

    private static function SpaceRemove(string $chaineNombre) : string {
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

    public static function ToString(string $chaineIban) : string {
        $chaine = BankingAccounts::SpaceRemove($chaineIban);
        return trim(chunk_split($chaine, 4, ' '));
    }

    public static function RIB_GetKey(string $codeBanque, string $codeGuichet, string $noCompte) : string {
        $codeBanque = BankingAccounts::Sanitize_Number($codeBanque);
        $codeGuichet = BankingAccounts::Sanitize_Number($codeGuichet);
        $noCompte = BankingAccounts::Sanitize_Account($noCompte);

        if (strlen($codeBanque) != 5)
            throw new BankingException("Code banque incorrect");

        if (strlen($codeGuichet) != 5)
            throw new BankingException("Code guichet incorrect");

        if (strlen($noCompte) > 11)
            throw new BankingException("Numéro de compte incorrect");

        $lA = 8 * intval($codeBanque);
        $lA = $lA % 97;

        $lB = 15 * intval($codeGuichet);
        $lB = 97 - ($lB % 97);

        $lC = 3 * intval($noCompte);
        $lC = 97 - ($lC % 97);

        $lG = $lA + $lB + $lC;
        $cle = $lG % 97;

        return ($cle == 0) ? BankingAccounts::DefaultRibKey : str_pad($cle, 2, BankingAccounts::DefaultNumber, STR_PAD_LEFT);
    }

    public static function IBAN_GetKey(string $codePays, string $rib) : string {
        $ribNum = BankingAccounts::Sanitize_IBAN($rib);
        $codePaysNum = BankingAccounts::Sanitize_IBAN($codePays);

        if (strlen($codePays) != 2 || strlen($codePaysNum) != 4)
            throw new BankingException("Code pays incorrect");

        $concat = $ribNum . $codePaysNum . BankingAccounts::DefaultNumber . BankingAccounts::DefaultNumber;

        $li = 0;
        $retenue = "";
        while ($li < strlen($concat)) {
            $bloc = $retenue . substr($concat, $li, 9);
            $mod = bcmod($bloc, 97);
            $retenue = $mod;
            $li += 9;
        }

        $cleNum = 98 - $mod;
        $cleStr = str_pad($cleNum, 2, BankingAccounts::DefaultNumber, STR_PAD_LEFT);

        return $codePays . $cleStr . $rib;
    }

    public static function IBAN_Check($iban) : bool {
        $ibanClean = BankingAccounts::SpaceRemove($iban);
        $pays = substr($ibanClean, 0, 2);
        $cle = substr($ibanClean, 2, 2);
        $rib = substr($ibanClean, 4);

        if (!ctype_alpha($pays))
            throw new BankingException("Code pays incorrect");

        $ibanCalc = BankingAccounts::IBAN_GetKey($pays, $rib);
        $cleCalc = substr($ibanCalc, 2, 2);

        if ($cle != $cleCalc)
            throw new BankingException("Clé IBAN incorrecte");

        if ($pays == "FR") {
            if (strlen($rib) != 23)
                throw new BankingException("RIB incorrect");

            $codeBanque = substr($rib, 0, 5);
            $codeGuichet = substr($rib, 5, 5);
            $noCompte = substr($rib, 10, 11);
            $cleRib = substr($rib, 21, 2);
            $cleRibCalc = BankingAccounts::RIB_GetKey($codeBanque, $codeGuichet, $noCompte);

            return ($cleRib != $cleRibCalc);
        }

        return true;
    }
}