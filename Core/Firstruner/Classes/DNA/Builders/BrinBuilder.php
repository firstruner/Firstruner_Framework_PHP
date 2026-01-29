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
 * @Update on : 11/03/2025 by : Nadia TRABELSI
 */

namespace DatasDNA\Classes\Builder;

use DatasDNA\Enumerations\ENucleicBases;
use DatasDNA\Classes\Brin;
use DatasDNA\Interfaces\INucleic;

final class BrinBuilder
{
    public static function BuildFromBase(INucleic $base): Brin
    {
        ENucleicBases::isValid($base);

        $brin = new Brin();
        $brin->add($base);
        return $brin;
    }

    public static function Build(): Brin
    {
        return new Brin();
    }
}
