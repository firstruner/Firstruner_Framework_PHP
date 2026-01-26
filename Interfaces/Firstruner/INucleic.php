<?php

namespace DatasDNA\Interfaces;

interface INucleic
{
    public function Symbol(): string;
    public function Weight(): float;
    public function Name(): string;
    public function BinaryValue(): string;
}
