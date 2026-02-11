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
 * Mode : TDD/BDD Creation
 * Author : Chris
 * @Update on : 11/02/2026 by : Christophe BOULAS
 * UT : Pass
 */

namespace UT\Classes;

require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../loader.php');

use PHPUnit\Framework\TestCase;
use System\Collections\KeyValuePair;

class CKeyPair_Test extends TestCase
{
    public function test_Creation()
    {
        $KP = new KeyValuePair("test", "value");
        $this->assertTrue($KP != null, "isNull");
        $this->assertEquals("test", $KP->GetKey(), "GetKey");
        $this->assertEquals("value", $KP->Value, "GetValue");
    }
}
