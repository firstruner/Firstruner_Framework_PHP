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

namespace Firstruner\Classes\Justice\Elements\Bodacc_Elements;

use Firstruner\Enumerations\Justice\Elements\Bodacc\Bodacc_Fields;

final class Fields
{
    public string $publicationavis;
    public string $listepersonnes;
    public string $id;
    public string $registre;
    public string $depot;
    public string $ville;
    public bool $ispdf_unitaire;
    public string $cp;
    public string $region_nom_officiel;
    public string $commercant;
    public string $dateparution;
    public string $tribunal;
    public bool $pdf_parution_subfolder;
    public int $region_code;
    public int $numeroannonce;
    public string $parution;
    public string $publicationavis_facette;
    public string $typeavis_lib;
    public string $typeavis;
    public string $familleavis_lib;
    public string $familleavis;
    public string $departement_nom_officiel;
    public int $numerodepartement;

    function __construct($array)
    {
        $this->publicationavis = $array[Bodacc_Fields::PublicationAvis];
        $this->listepersonnes = json_decode($array[Bodacc_Fields::ListePersonnes]);
        $this->id = $array[Bodacc_Fields::Id];
        $this->registre = $array[Bodacc_Fields::Registre];
        $this->depot = json_decode($array[Bodacc_Fields::Depot]);
        $this->ville = $array[Bodacc_Fields::Ville];
        $this->ispdf_unitaire = $array[Bodacc_Fields::CP];
        $this->cp = $array[Bodacc_Fields::IsPdf_Unitaire];
        $this->region_nom_officiel = $array[Bodacc_Fields::Region_Nom_Officiel];
        $this->commercant = $array[Bodacc_Fields::Commercant];
        $this->dateparution = $array[Bodacc_Fields::DateParution];
        $this->tribunal = $array[Bodacc_Fields::Tribunal];
        $this->pdf_parution_subfolder = $array[Bodacc_Fields::Pdf_Parution_Subfolder];
        $this->region_code = $array[Bodacc_Fields::Region_Code];
        $this->numeroannonce = $array[Bodacc_Fields::NumeroAnnonce];
        $this->parution = $array[Bodacc_Fields::Parution];
        $this->publicationavis_facette = $array[Bodacc_Fields::PublicationAvis_Facette];
        $this->typeavis_lib = $array[Bodacc_Fields::TypeAvis_Lib];
        $this->typeavis = $array[Bodacc_Fields::TypeAvis];
        $this->familleavis_lib = $array[Bodacc_Fields::FamilleAvis_Lib];
        $this->familleavis = $array[Bodacc_Fields::FamilleAvis];
        $this->departement_nom_officiel = $array[Bodacc_Fields::Departement_Nom_Officiel];
        $this->numerodepartement = $array[Bodacc_Fields::NumeroDepartement];
    }
}
