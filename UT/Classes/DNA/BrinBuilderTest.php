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
 * @Update on : 02/03/2026 by : Christophe BOULAS
 */

namespace DatasDNA\tests;

require(__DIR__ . '/../../UT_Helper.php');

use DatasDNA\Classes\Bases\Adenine;
use PHPUnit\Framework\TestCase;
use DatasDNA\Classes\Builder\BrinBuilder;
use DatasDNA\Classes\Brin;

class BrinBuilderTest extends TestCase
{
    public function testAddBaseWithValidBase()
    {
        $brin = BrinBuilder::buildFromBase(new Adenine());

        $this->assertInstanceOf(Brin::class, $brin);
    }

    public function testBuild()
    {
        $brin = BrinBuilder::Build();
        $this->assertInstanceOf(Brin::class, $brin);
    }
}
