<?php
namespace DatasDNA\Classes\Builder;

use DatasDNA\Classes\Singleton\DNA_Sequenceur;

final class DNA_SequenceurBuilder
{
    public static function Build(): DNA_Sequenceur
    {
        return new DNA_Sequenceur(); 
    }
}
