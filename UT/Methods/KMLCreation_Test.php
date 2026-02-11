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

namespace UT\Methods;

require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../loader.php');

use PHPUnit\Framework\TestCase;

class KMLCreation_Test extends TestCase
{

    /**
     * @test
     * @Todo faire test avec Chamborelle
     * @Result :
     *      <?xml version='1.0' encoding='UTF-8'?>
     *      <kml xmlns='http://www.opengis.net/kml/2.2'>
     *          <Document>
     *              <name>Prospects</name>
     *              <description>Maps created in GRC Chamborelle</description>
     *              <Placemark>
     *                  <styleUrl>#1</styleUrl>
     *                  <name>Cregy les meaux - Chamborelle</name>
     *                  <Point>
     *                      <coordinates>2.8629357,48.9709021,0</coordinates>
     *                  </Point>
     *              </Placemark>
     *              <Style id=""1"">
     *                  <IconStyle>
     *                      <scale>1</scale>
     *                      <Icon>
     *                          <href>https://chamborelle.com/grc/iconmaps/red_shadow_Marker.png</href>
     *                      </Icon>
     *                      <hotSpot x="".3"" y="".8"" xunits=""fraction"" yunits=""fraction"" />
     *                  </IconStyle>
     *                  <LabelStyle>
     *                      <scale>0</scale>
     *                  </LabelStyle>
     *              </Style>
     *          </Document>
     *      </kml>
     */
    public function test_TestKMLCreation()
    {
        //put your code here
    }
}
