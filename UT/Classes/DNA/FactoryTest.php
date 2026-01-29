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

namespace DatasDNA\tests;

use PHPUnit\Framework\TestCase;
use DatasDNA\Classes\Factory\Factory;
use DatasDNA\Classes\Singleton\DNA_Sequenceur;

class FactoryTest extends TestCase
{
    public function testCreateReturnsNewInstance()
    {
        $instance1 = Factory::create();
        $instance2 = Factory::create();

        $this->assertInstanceOf(DNA_Sequenceur::class, $instance1);
        $this->assertInstanceOf(DNA_Sequenceur::class, $instance2);
        $this->assertNotSame($instance1, $instance2); // Vérifie que ce ne sont pas les mêmes objets (Factory ≠ Singleton)
    }
}
