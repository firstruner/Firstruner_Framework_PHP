<?php

require(__DIR__ . '/loader.php');
require(__DIR__ . '/DemoClass/article.php');
require(__DIR__ . '/DemoClass/client.php');
require(__DIR__ . '/DemoClass/piece.php');
require(__DIR__ . '/DemoClass/sav.php'); // En dernier car dépendance

$mySAV = new Sav();

$clt = new client();
$clt->Nom = "BOULAS";
$clt->Prenom = "Christophe";
$clt->BirthDate = new DateTime("01/01/1990");

$art = new article();
$art->Reference = "Article0";
$art->Description = "Livre du lutin";
$art->Tarif = (float)67.90;

$pcs1 = new piece();
$pcs1->codePiece = 36;
$pcs1->textePiece = "Reliure tissu";

$pcs2 = new piece();
$pcs2->codePiece = 97;
$pcs2->textePiece = "Agrafes";

$mySAV->client = $clt;
$mySAV->article = $art;
$mySAV->addPiece($pcs1);
$mySAV->addPiece($pcs2);

//echo $mySAV->SerializeToXml();

use System\Runtime\Serialization\XmlSerializer;
$serializer = new XmlSerializer();
$xml = $serializer->Serialize($mySAV);
echo $xml;