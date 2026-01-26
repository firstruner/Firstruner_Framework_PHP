<?php

namespace DatasDNA\Classes\Bases;

use DatasDNA\Interfaces\INucleic;
use DatasDNA\Enumerations\EBinaryBases\EBinaryBases;
use DatasDNA\Enumerations\ENucleicBases;

abstract class AbsNucleicBase implements INucleic
{
    public const SYMBOL = "";
    public const WEIGHT = 0.0;
    
    public function Symbol(): string
    {
        return $this::SYMBOL;
    }

    public function Weight(): float
    {
        return $this::WEIGHT;
    }

    public function Name(): string
    {    
        return ENucleicBases::GetName($this::SYMBOL);
    }

    public function BinaryValue(): string
    {
        return EBinaryBases::FromDNABase($this->Symbol());
    }
}
