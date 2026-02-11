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

namespace DatasDNA\UT;

use DatasDNA\Enumerations\ENucleicBases;
use DatasDNA\Classes\Bases\Adenine;
use DatasDNA\Classes\Bases\Thymine;
use DatasDNA\Classes\Bases\Cytosine;
use DatasDNA\Classes\Bases\Guanine;
use PHPUnit\Framework\TestCase;

class ENucleicBasesTest extends TestCase
{
    // Définir un tableau avec les symboles et leurs noms
    private static function getBases(): array
    {
        return [
            new Adenine(),
            new Thymine(),
            new Cytosine(),
            new Guanine()
        ];
    }

    // Utilisation du tableau dans le test
    public function test_getNames_returns_correct_names()
    {
        // $this->assertEquals(self::$bases, ENucleicBases::getNames());
    }

    public function test_GetName_returns_correct_name()
    {
        // Itérer sur les bases pour tester les noms
        // foreach (self::$bases as $symbol => $name) {
        //     $this->assertEquals($name, ENucleicBases::GetName($symbol));
        // }
    }

    public function test_GetName_throws_exception_for_invalid_base()
    {
        // $this->expectException(InvalidBaseException::class);
        ENucleicBases::GetName("X"); // Base inconnue
    }
}
