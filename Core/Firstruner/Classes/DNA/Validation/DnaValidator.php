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



/** 
 * -- File description --
 * @Type : MethodClass
 * @Mode : DDD Creation
 * @Author : Nadia TRABELSI
 * @Update on : 03/03/2025 by : Nadia TRABELSI
 */

namespace DatasDNA\Classes\Validation;

use DatasDNA\Classes\Exceptions\ATCG_Pair_Exception;


class DNAValidator
{

    /**
     * Vérifie qu'il n'y a pas plus de 4 bases identiques consécutives.
     * @param string $sequence
     * @return bool
     * @throws DNAValidationException
     */
    public static function CheckRepeatedBases($sequence): bool
    {
        $pattern = '/(A{5,}|C{5,}|G{5,}|T{5,})/i';
        if (preg_match_all($pattern, $sequence, $matches, PREG_OFFSET_CAPTURE)) {
            $errors = [];
            foreach ($matches[0] as $match) {
                $repeatedSequence = $match[0];
                $position = $match[1];
                $errors[] = "Séquence '$repeatedSequence' détectée à la position $position";
            }
            throw new ATCG_Pair_Exception("Répétition excessive détectée : " . implode(', ', $errors));
        }
        return true;
    }
}
