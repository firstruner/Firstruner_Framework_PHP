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

namespace System\Net\Config;

use System\Annotations\NotImplemented;
use System\Collections\CCollection;
use System\Collections\KeyValuePair;
use System\Net\Keys\AppKeys;

// PHP 7+

class StaticsParams extends CCollection
{
    public function __construct()
    {
        parent::__construct();
        $this->Add(new KeyValuePair(AppKeys::DateFormat, "Y-m-d"));
        $this->Add(new KeyValuePair(AppKeys::DateTimeFormat, "Y-m-d H:i:s")); // G,i,s <= A checker avec DB
        $this->Add(new KeyValuePair(AppKeys::SlideURL, "slides.xml"));
        $this->Add(new KeyValuePair(AppKeys::DoubleTab, "\t\t"));
        $this->Add(new KeyValuePair(AppKeys::TripleTab, "\t\t\t"));
        $this->Add(new KeyValuePair(AppKeys::ApiMode, false));
        $this->Add(new KeyValuePair(AppKeys::GoogleApi_Geoloc, "APIKEY INVALID - MUST BE REDEFINE"));
        $this->Add(new KeyValuePair(AppKeys::AccesTimeLimitation, "22:00;7:30"));
        $this->Add(new KeyValuePair(AppKeys::AccesDateTimeLimitation_LevelByPass, 6));
        $this->Add(new KeyValuePair(AppKeys::LevelManagerMenu, 6));
        $this->Add(new KeyValuePair(AppKeys::NightMode, "16:00;9:00"));
        $this->Add(new KeyValuePair(AppKeys::DateFormatAffichage, "D d M Y"));
        $this->Add(new KeyValuePair(AppKeys::AccesDayLimitation, "6;7"));
        $this->Add(new KeyValuePair(AppKeys::MaxCourtoisieVisit, 5));
        $this->Add(new KeyValuePair(AppKeys::LevelAdmin, 9));

        $this->Add(new KeyValuePair(AppKeys::DebugMode, false));
        $this->Add(new KeyValuePair(AppKeys::SimsMode, true));
    }

    public function InitializedValues()
    {
        return ($this->Count() > 0);
    }

    public function GetValue(string $keyname)
    {
        foreach ($this->ToArray() as $KP) {
            if ($KP->GetKey() == $keyname) {
                return $KP->Value;
            }
        }

        return null;
    }

    public function SetKeyValue(string $keyname, string $value, bool $autoAdd = true)
    {
        if (!isset($this[$keyname]) && !$autoAdd)
            throw new NotImplemented("Clé '{$keyname}' introuvable");

        foreach ($this->ToArray() as $KP) {
            if ($KP->GetKey() == $keyname) {
                $KP->Value = $value;
                return;
            }
        }

        if (!$autoAdd) {
            die("Clé '{$keyname}' introuvable");
        }

        $this->Add(new KeyValuePair($keyname, $value));
    }

    public function Set_GoogleAPI_GeoLoc(string $apikey)
    {
        $this[AppKeys::GoogleApi_Geoloc] = $apikey;
    }
}
