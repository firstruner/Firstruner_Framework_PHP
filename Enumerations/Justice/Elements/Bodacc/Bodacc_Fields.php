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

namespace Firstruner\Enumerations\Justice\Elements\Bodacc;

/* PHP 8+
enum EStyleTags
{
    //case ...;
}
*/

/* PHP 7+*/

abstract class Bodacc_Fields
{
    const PublicationAvis = "publicationavis";
    const ListePersonnes = "listepersonnes";
    const Id = "id";
    const Registre = "registre";
    const Depot = "depot";
    const Ville = "ville";
    const IsPdf_Unitaire = "ispdf_unitaire";
    const CP = "cp";
    const Region_Nom_Officiel = "region_nom_officiel";
    const Commercant = "commercant";
    const DateParution = "dateparution";
    const Tribunal = "tribunal";
    const Pdf_Parution_Subfolder = "pdf_parution_subfolder";
    const Region_Code = "region_code";
    const NumeroAnnonce = "numeroannonce";
    const Parution = "parution";
    const PublicationAvis_Facette = "publicationavis_facette";
    const TypeAvis_Lib = "typeavis_lib";
    const TypeAvis = "typeavis";
    const FamilleAvis_Lib = "familleavis_lib";
    const FamilleAvis = "familleavis";
    const Departement_Nom_Officiel = "departement_nom_officiel";
    const NumeroDepartement = "numerodepartement";
}
