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

use System\IO\XMLStreamWriter;
use System\Xml\XMLElement;

require_once('./loader.php');

/*
use System\Data\DataSet;

$ds = new DataSet();
$ds->LoadXML("output.xml");

dumpexit($ds->ToXMLString());
*/

$elem1 = new XMLElement();
$elem1["Name"] = "Toto";

$elem2 = new XMLElement();
$elem2["Name"] = "Tata";

$elem3 = new XMLElement();
$elem3["Name"] = "Titi";

$elem4 = new XMLElement();
$elem4["Name"] = "Tutu";

$main = new XMLElement();
$main["UserNames"] = [
      $elem1,
      $elem2,
      $elem3,
      $elem4
];

XMLStreamWriter::Write($main, "log.xml");
