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
 * @Update on : 11/03/2025 by : Nadia TRABELSI
 */

namespace DatasDNA\tests;

use PHPUnit\Framework\TestCase;
use DatasDNA\Classes\Singleton\DNA_Sequenceur;
use DatasDNA\Classes\DNA_Structure;
use DatasDNA\Classes\Chromosome;

class DNA_SequenceurTest extends TestCase
{
    public function testGetInstanceReturnsSingletonInstance()
    {
        $instance1 = DNA_Sequenceur::getInstance();
        $instance2 = DNA_Sequenceur::getInstance();

        $this->assertInstanceOf(DNA_Sequenceur::class, $instance1);
        $this->assertInstanceOf(DNA_Sequenceur::class, $instance2);

        // Vérifie que c'est bien le même objet (singleton)
        $this->assertSame($instance1, $instance2);
    }

    public function testGenerateSequenceFromText()
    {
        $sequencer = DNA_Sequenceur::getInstance();
        $text = "ATCG";

        // Générer la séquence ADN
        $dnaStructure = $sequencer->generateSequenceFromText($text);

        // Vérifie que l'objet retourné est bien un DNA_Structure
        $this->assertInstanceOf(DNA_Structure::class, $dnaStructure);

        // Vérifie que la structure contient bien 4 chromosomes (un par base)
        $this->assertCount(4, $dnaStructure);

        // Vérifie que chaque chromosome contient bien un brin correspondant
        $expectedBases = str_split($text); // ["A", "T", "C", "G"]
        foreach ($dnaStructure->ToArray() as $index => $chromosome) {
            $this->assertInstanceOf(Chromosome::class, $chromosome);
            $brin = $chromosome->first(); // Supposons que Chromosome hérite de CCollection et a `first()`
            $this->assertEquals($expectedBases[$index], $brin->getBase()); // Vérifie la base
        }
    }
}
