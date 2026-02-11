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

namespace Firstruner\Classes\Adresses;

use System\Globalization\CultureInfo;
use System\Globalization\CultureTypes;

final class Pays
{
    public string $French_Name;
    public string $English_Name;
    public string $Land_Code;
    public bool $CP_List_Available;
    public CultureInfo $Culture;

    public function __construct(
        string $frenchName,
        string $englishName,
        string $landCode,
        bool $cpAvailable = false,
        CultureInfo $culture = null
    ) {

        $this->French_Name = $frenchName;
        $this->English_Name = $englishName;
        $this->Land_Code = $landCode;
        $this->CP_List_Available = $cpAvailable;
        $this->Culture = new CultureInfo("fr-FR");
    }

    public function ToItemList()
    {
        //return new ItemList(French_Name, null, this);
        return null;
    }
}
