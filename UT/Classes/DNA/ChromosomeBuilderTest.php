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
use DatasDNA\Classes\Builder\ChromosomeBuilder;
use DatasDNA\Classes\Chromosome;
use DatasDNA\Classes\Brin;
use DatasDNA\Classes\Builder\BrinBuilder;

class ChromosomeBuilderTest extends TestCase
{
    public function testAddBrin()
    {
        $brin = BrinBuilder::buildFromBase('A'); // Ajout d'une base valide
        $chromosome = ChromosomeBuilder::buildFromBrin($brin);

        // Vérifie que l'objet retourné est bien un Chromosome
        $this->assertInstanceOf(Chromosome::class, $chromosome);

        // Récupère les brins du chromosome en utilisant ToArray()
        $brins = $chromosome->ToArray();

        // Vérifie qu'il y a exactement 1 brin
        $this->assertCount(1, $brins);

        // Vérifie que le brin ajouté est bien celui qu'on a inséré
        $this->assertSame($brin, $brins[0]);
    }

    public function testBuild()
    {
        $chromosome = ChromosomeBuilder::Build();

        // Vérifie que l'objet retourné est bien un Chromosome
        $this->assertInstanceOf(Chromosome::class, $chromosome);

        // Vérifie que le chromosome est vide au départ
        $this->assertEmpty($chromosome->ToArray());
    }
}
