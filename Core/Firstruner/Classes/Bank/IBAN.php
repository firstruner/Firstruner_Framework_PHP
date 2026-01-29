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

use Firstruner\Classes\Bank\AbsBankingAccounts;
use Firstruner\Classes\Bank\RIB;
use System\Default\_string;
use System\Exceptions\BankingException;

final class IBAN extends AbsBankingAccounts
{
    // public function ToString(string $chaineIban) : string {
    //     $chaine = $this->SpaceRemove($chaineIban);
    //     return trim(chunk_split($chaine, 4, ' '));
    // }

    public function IBAN_GetKey(string $codePays, string $rib): string
    {
        $ribNum = $this->Sanitize_IBAN($rib);
        $codePaysNum = $this->Sanitize_IBAN($codePays);

        if (strlen($codePays) != 2 || strlen($codePaysNum) != 4)
            throw new BankingException("Code pays incorrect");

        $concat = $ribNum . $codePaysNum . AbsBankingAccounts::DefaultNumber . AbsBankingAccounts::DefaultNumber;

        $li = 0;
        $retenue = "";
        while ($li < strlen($concat)) {
            $bloc = $retenue . substr($concat, $li, 9);
            $mod = bcmod($bloc, 97);
            $retenue = $mod;
            $li += 9;
        }

        $cleNum = 98 - $mod;
        $cleStr = str_pad($cleNum, 2, AbsBankingAccounts::DefaultNumber, STR_PAD_LEFT);

        return $codePays . $cleStr . $rib;
    }

    private function Sanitize_IBAN(string $chaineNombre): string
    {
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

    public function IBAN_Check($iban): bool
    {
        $ibanClean = $this->SpaceRemove($iban);
        $pays = substr($ibanClean, 0, 2);
        $cle = substr($ibanClean, 2, 2);
        $rib = substr($ibanClean, 4);

        if (!ctype_alpha($pays))
            throw new BankingException("Code pays incorrect");

        $ibanCalc = $this->IBAN_GetKey($pays, $rib);
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
            $cleRibCalc = (new RIB())->RIB_GetKey($codeBanque, $codeGuichet, $noCompte);

            return ($cleRib != $cleRibCalc);
        }

        return true;
    }
}
