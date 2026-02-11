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
use DatasDNA\Classes\Builder\DNA_StructureBuilder;
use DatasDNA\Classes\DNA_Structure;
use DatasDNA\Classes\Chromosome;
use DatasDNA\Classes\Builder\ChromosomeBuilder;

class DNA_StructureBuilderTest extends TestCase
{

    public function testAddChromosome()
    {
        $chromosome = ChromosomeBuilder::Build();
        // TODO : Erreurs dûes à une mauvaise utilisation des classes et du Builder
        $DNAStructure = DNA_StructureBuilder::buildFromChromosome($chromosome);
        $this->assertInstanceOf(DNA_Structure::class, $DNAStructure);
    }

    public function testBuild()
    {
        $DNAStructure = DNA_StructureBuilder::Build();
        $this->assertInstanceOf(DNA_Structure::class, $DNAStructure);
    }
}
