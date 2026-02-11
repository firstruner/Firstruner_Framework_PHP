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


/*
 * -- File description --
 * Type : UnitTest
 * Mode : TDD Creation
 * Author : Christophe
 * @Update on : 11/02/2026 by : Christophe BOULAS
 * UT : PassRisk
 */

namespace UT\WebServices;

require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../loader.php');

use PHPUnit\Framework\TestCase;

class WSGoogleGeoLoc_Test extends TestCase
{
    /**
     * @test
     * @Todo Test Récupération du contenu XML
     * @Todo Récupération Long / Lat
     * @Result Long=2.3522219 / Lat=48.8566140
     */
    public function test_TestWSGoogleGeoLoc()
    {
        //put your code here
    }
}
