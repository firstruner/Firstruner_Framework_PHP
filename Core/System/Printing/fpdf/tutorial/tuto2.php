<?php

/**
* Copyright since 2024 Firstruner and Contributors
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
* @copyright Since 2024 Firstruner and Contributors
* @license   Proprietary
* @version 2.0.0
*/

require('../fpdf.php');

class PDF extends FPDF
{
// En-t�te
function Header()
{
	// Logo
	$this->Image('logo.png',10,6,30);
	// Police Arial gras 15
	$this->SetFont('Arial','B',15);
	// D�calage � droite
	$this->Cell(80);
	// Titre
	$this->Cell(30,10,'Titre',1,0,'C');
	// Saut de ligne
	$this->Ln(20);
}

// Pied de page
function Footer()
{
	// Positionnement � 1,5 cm du bas
	$this->SetY(-15);
	// Police Arial italique 8
	$this->SetFont('Arial','I',8);
	// Num�ro de page
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation de la classe d�riv�e
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
	$pdf->Cell(0,10,'Impression de la ligne num�ro '.$i,0,1);
$pdf->Output();
?>
