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
 * Do not edit, reproduce ou modifier ce fichier.
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
 * @Update on : 26/02/2025 by : Nadia TRABELSI
 */

namespace DatasDNA\Classes;

use System\Collections\CCollection;
use DatasDNA\Interfaces\IDNA_Component;
use DatasDNA\Classes\Chromosome;

class DNA_Structure extends CCollection implements IDNA_Component
{

    public function __construct()
    {
        parent::__construct(type: gettype(new Chromosome()));
    }

    public function PoidsTotal(): float
    {
        // Calcul du poids total de tous les chromosomes
        return array_reduce(
            $this->ToArray(),
            function ($carry, $chromosome) {
                return $carry + $chromosome->PoidsTotal();
            },
            0.0
        );
    }
}
