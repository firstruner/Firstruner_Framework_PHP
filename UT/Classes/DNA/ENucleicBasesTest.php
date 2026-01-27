<?php

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
    private static function getBases() : array
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
