<?php

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