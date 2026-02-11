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

use System\Exceptions\BankingException;

final class RIB extends AbsBankingAccounts
{
    // public function ToString(string $chaine): string
    // {
    //     $chaine = $this->SpaceRemove($chaineIban);
    //     return trim(chunk_split($chaine, 4, ' '));
    // }

    public function RIB_GetKey(string $codeBanque, string $codeGuichet, string $noCompte): string
    {
        $codeBanque = $this->Sanitize_Number($codeBanque);
        $codeGuichet = $this->Sanitize_Number($codeGuichet);
        $noCompte = $this->Sanitize_Account($noCompte);

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

        return ($cle == 0)
            ? RIB::DefaultRibKey
            : str_pad($cle, 2, RIB::DefaultNumber, STR_PAD_LEFT);
    }

    private function stringToRib(string $rib): array
    {
        $bank    = substr($rib, 0, 5);
        $guichet = substr($rib, 5, 5);
        $compte  = substr($rib, 10, 11);
        $cle     = substr($rib, 21, 2);

        return [
            "bank" => $bank,
            "guichet" => $guichet,
            "compte" => $compte,
            "cle" => $cle
        ];
    }

    public function RIB_Check(string $rib): bool
    {
        $arrayRIB = $this->stringToRib($rib);

        return
            $arrayRIB["bank"] .
            $arrayRIB["guichet"] .
            $arrayRIB["compte"] .
            $this->RIB_GetKey(
                $arrayRIB["bank"],
                $arrayRIB["guichet"],
                $arrayRIB["compte"]
            )
            == $rib;
    }
}
