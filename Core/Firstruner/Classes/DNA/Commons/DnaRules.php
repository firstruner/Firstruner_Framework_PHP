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
 * @Update on : 14/03/2025 by : Nadia TRABELSI
 */

namespace DatasDNA\Commons;

use DatasDNA\Classes\Validation\DNAValidator;
use DatasDNA\Commons\NonCodingBase;
use DatasDNA\Enumerations\ENucleicBases;
use System\Default\_String;
use System\_Float;
use DatasDNA\Classes\Bases\Adenine;
use DatasDNA\Classes\Bases\Thymine;
use DatasDNA\Classes\Bases\Cytosine;
use DatasDNA\Classes\Bases\Guanine;

class DNARules
{

    private $countA = 0;
    private $countT = 0;
    private $countC = 0;
    private $countG = 0;

    private $nonCodingBase;
    private $validator;

    public function __construct()
    {
        // Initialisation des dépendances
        $this->nonCodingBase = new NonCodingBase();
        $this->validator = new DNAValidator();
    }

    public function InsertNonCodingBases($DNASequence)
    {
        $modifiedDNA = _String::EmptyString;
        $intervalCount = 0;



        for ($i = 0; $i < strlen($DNASequence); $i++) {
            $currentBase = $DNASequence[$i];
            $nextBase = $DNASequence[$i + 1] ?? _String::EmptyString;


            switch ($currentBase) {
                case Adenine::SYMBOL:
                    $this->countA++;
                    break;
                case Thymine::SYMBOL:
                    $this->countT++;
                    break;
                case Cytosine::SYMBOL:
                    $this->countC++;
                    break;
                case Guanine::SYMBOL:
                    $this->countG++;
                    break;
            }

            // Ajoute la base actuelle à la séquence modifiée
            $modifiedDNA .= $currentBase;
            $intervalCount++;

            // Ajouter une base non codante tous les 3 intervalles
            if ($intervalCount === 3) {
                $nonCodingBase = $this->nonCodingBase->getNonCodingBase($currentBase, $nextBase);
                $modifiedDNA .= $nonCodingBase;
                $intervalCount = 0;
            }
        }

        // Vérification des bases répétées
        $this->validator->checkRepeatedBases($modifiedDNA);

        return $modifiedDNA;
    }


    public function GetStats(): array
    {
        $total = _Float::Sum([$this->countA, $this->countT, $this->countC, $this->countG]);



        return [
            Adenine::SYMBOL => $this->countA,
            Thymine::SYMBOL => $this->countT,
            Cytosine::SYMBOL => $this->countC,
            Guanine::SYMBOL => $this->countG,
            'total' => $total,
            'A_percentage' => ($total > 0) ? round(($this->countA * 100) / $total, 2) : 0,
            'T_percentage' => ($total > 0) ? round(($this->countT * 100) / $total, 2) : 0,
            'C_percentage' => ($total > 0) ? round(($this->countC * 100) / $total, 2) : 0,
            'G_percentage' => ($total > 0) ? round(($this->countG * 100) / $total, 2) : 0
        ];
    }

    public function DisplayStats(): void
    {
        $stats = $this->GetStats();
        echo "Statistiques finales:\n";

        $bases = ENucleicBases::NAMES;



        foreach ($bases as $base => $label) {
            $percentageKey = "{$base}_percentage";
            echo "{$label} ({$base}): {$stats[$base]} ({$stats[$percentageKey]}%)\n";
        }

        echo "Total des bases: {$stats['total']}\n";
    }
}
