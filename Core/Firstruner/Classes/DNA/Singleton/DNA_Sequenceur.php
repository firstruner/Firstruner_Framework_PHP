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

namespace DatasDNA\Classes\Singleton;

use DatasDNA\Classes\Bases\Adenine;
use DatasDNA\Classes\Bases\Cytosine;
use DatasDNA\Classes\Bases\Guanine;
use DatasDNA\Classes\Bases\Thymine;
use DatasDNA\Classes\DNA_Structure;
use DatasDNA\Classes\Builder\BrinBuilder;
use DatasDNA\Classes\Builder\ChromosomeBuilder;
use DatasDNA\Classes\Builder\DNA_StructureBuilder;
use DatasDNA\Classes\Exceptions\ATCG_Exception;
use DatasDNA\Classes\Exceptions\InvalidBaseException;
use DatasDNA\Enumerations\ENucleicBases;
use DatasDNA\Interfaces\INucleic;

class DNA_Sequenceur
{
    private static ?DNA_Sequenceur $instance = null;
    private static $lock = false; // Verrou pour thread-safety
    private ?DNA_Structure $DNAStructure = null; // Création différée

    // Constructeur privé pour empêcher l'instanciation externe
    private function __construct() {}

    // Empêche le clonage du singleton
    private function __clone()
    {
        throw new \Exception("Not authorized");
    }

    // Empêche la sérialization/désérialization
    private function __wakeup()
    {
        throw new \Exception("Not authorized");
    }

    public function __serialize(): array
    {
        throw new \Exception("Not authorized");
    }

    // Méthode thread-safe pour récupérer l'instance unique
    public static function GetInstance(): DNA_Sequenceur
    {
        if (self::$instance === null) {
            if (!self::$lock) {
                self::$lock = true;
                self::$instance = new DNA_Sequenceur();
            }
        }

        return self::$instance;
    }

    private function InstanciateNucleicBase(string $value): INucleic
    {
        switch ($value) {
            case ENucleicBases::ADENINE:
                return new Adenine();
            case ENucleicBases::THYMINE:
                return new Thymine();
            case ENucleicBases::CYTOSINE:
                return new Cytosine();
            case ENucleicBases::GUANINE:
                return new Guanine();
            default:
                throw new ATCG_Exception();
        }
    }

    public function GenerateSequenceFromText(string $text): DNA_Structure
    {
        // Utilisation du builder pour créer l'objet DNA_Structure
        $dnaStructure = DNA_StructureBuilder::build();

        foreach (str_split($text) as $char) {
            // `IsValid` déclenche une exception si le caractère est invalide, donc pas besoin de vérifier ici

            // Construire l'objet Brin à partir de la base valide
            $brin = BrinBuilder::buildFromBase($this->InstanciateNucleicBase($char));

            // Construire le chromosome à partir du Brin
            $chromosome = ChromosomeBuilder::buildFromBrin($brin);

            // Ajouter le chromosome à la structure de l'ADN
            $dnaStructure->add($chromosome);
        }

        return $dnaStructure;
    }


    public function GetDNA(): DNA_Structure
    {
        return $this->DNAStructure;
    }
}
