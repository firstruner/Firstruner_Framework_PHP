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

namespace Firstruner\Classes\Justice\Societe;

use Firstruner\Classes\Adresses\Magistrat;
use Firstruner\Enumerations\Adresses\AdresseType;
use Firstruner\Enumerations\Justice\Decision;
use System\Collections\Item;

final class Sante extends Item
{
    public \DateTime $DateJugement;
    public int $Decision = Decision::Active;
    public Magistrat $Greffe;
    public string $Titre;
    public string $Détails;

    public function Sante()
    {
        $this->Greffe = new Magistrat(AdresseType::Societe);
    }
}
