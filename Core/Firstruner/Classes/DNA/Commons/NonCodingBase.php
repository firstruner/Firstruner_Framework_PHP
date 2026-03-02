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
 * @Update on : 10/03/2025 by : Nadia TRABELSI
 */



namespace DatasDNA\Commons;

use DatasDNA\Enumerations\NonCodingBasePairs;

class NonCodingBase
{
    private int $countA = 0;
    private int $countT = 0;
    private int $countC = 0;
    private int $countG = 0;

    public function GetNonCodingBase(string $currentBase, string $nextBase): string
    {
        $diffAT = $this->countA - $this->countT;
        $diffGC = $this->countC - $this->countG;

        // Vérifier les fréquences et retourner la paire
        $chosenPair = $this->choosePair($currentBase, $nextBase, $diffAT, $diffGC);

        // Mettre à jour les compteurs selon la paire choisie
        $this->updateBaseCount($chosenPair);

        return $chosenPair; // Tu retournes directement la constante de base
    }

    private function choosePair(string $currentBase, string $nextBase, int $diffAT, int $diffGC): string
    {
        $basePair = $currentBase . $nextBase;

        // Comparaison avec les constantes de classe
        if ($diffAT !== 0 && ($basePair !== NonCodingBasePairs::AA && $basePair !== NonCodingBasePairs::TT)) {
            return $diffAT > 1 ? NonCodingBasePairs::TT : NonCodingBasePairs::AA;
        }

        if ($diffGC !== 0 && ($basePair !== NonCodingBasePairs::GG && $basePair !== NonCodingBasePairs::CC)) {
            return $diffGC > 1 ? NonCodingBasePairs::GG : NonCodingBasePairs::CC;
        }

        return $this->chooseRandomPair($currentBase, $nextBase);
    }

    private function chooseRandomPair(string $currentBase, string $nextBase): string
    {
        $pairs = NonCodingBasePairs::getAllPairs();

        $filteredPairs = array_filter($pairs, function ($pair) use ($currentBase, $nextBase) {
            return strpos($pair, $currentBase) === false && strpos($pair, $nextBase) === false;
        });

        if (!empty($filteredPairs)) {
            return $filteredPairs[array_rand($filteredPairs)];
        }

        // Retourne une paire par défaut si aucune n'est trouvée (éviter '??')
        return NonCodingBasePairs::AA;
    }

    public function updateBaseCount(string $pair): void
    {
        $varATCG = "count{$pair[0]}"; // Utilisation du premier caractère de la paire
        $this->$varATCG += 2;
    }
}
