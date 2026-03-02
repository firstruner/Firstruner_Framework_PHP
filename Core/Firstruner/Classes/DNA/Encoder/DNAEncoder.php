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
 * @Update on : 11/02/2025 by : Nadia TRABELSI
 */

namespace DatasDNA\Classes\Encoder;

use DatasDNA\Commons\DNARules;
use DatasDNA\MappingMode;
use System\Default\_String;

final class DNAEncoder
{

    private $binToDNAMapping;
    private $DNAToBinMapping;

    public function __construct()
    {
        $this->binToDNAMapping = DNAMapper::Map(MappingMode::ToDNA);
        $this->DNAToBinMapping = DNAMapper::Map(MappingMode::ToBINARY);
    }

    public function StringToDNA($string): string
    {
        $binaryString = $this->stringToBinary($string);
        $rowDNA = $this->binaryToDNA($binaryString);

        $DNARules = new DNARules();

        return $DNARules->insertNonCodingBases($rowDNA);
    }

    public function DNAToString(string $DNASequence): string
    {
        $codingDNA = $this->extractCodingBases($DNASequence);

        $binaryString = _String::EmptyString;
        for ($i = 0; $i < strlen($codingDNA); $i++) {
            $char = $codingDNA[$i];
            $binaryString .= (isset($this->DNAToBinMapping[$char])
                ? $this->DNAToBinMapping[$char]
                : '?');
        }

        $stringMessage = _String::EmptyString;
        for ($i = 0; $i < strlen($binaryString); $i += 8) {
            $byte = substr($binaryString, $i, 8);
            $stringMessage .= chr(bindec($byte));
        }
        return $stringMessage;
    }

    private function stringToBinary(string $stringValue): string
    {
        $binaryString = _String::EmptyString;
        for ($i = 0; $i < strlen($stringValue); $i++) {
            $asciiValue = ord($stringValue[$i]);
            $binaryValue = decbin($asciiValue);
            $binaryString .= str_pad($binaryValue, 8, '0', STR_PAD_LEFT);
        }
        return $binaryString;
    }

    private function binaryToDNA(string $binaryValue): string
    {
        $DNASequence = _String::EmptyString;
        for ($i = 0; $i < strlen($binaryValue); $i += 2) {
            $pair = substr($binaryValue, $i, 2);
            if (isset($this->binToDNAMapping[$pair])) {
                $DNASequence .= $this->binToDNAMapping[$pair];
            } else {
                $DNASequence .= '?'; // error
            }
        }
        return $DNASequence;
    }

    private function extractCodingBases(string $DNASequence): string
    {
        $codingDNA = _String::EmptyString;
        $length = strlen($DNASequence);
        $i = 0;

        while ($i < $length) {
            $codingDNA .= substr($DNASequence, $i, 3);
            $i += 3;
            $i += 2;
        }

        return $codingDNA;
    }
}
