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

namespace System;

use System\Forms\Messages;
use System\Forms\MessageType;

class DateTime
{
        public static function CheckDateIntervals(
                string $From,
                string $To,
                string $Value,
                bool $IncludePrecision = true
        ) {
                // TODO : Valider la méthode !

                $dateStart = strtotime($From);
                $dateEnd = strtotime($To);
                $currentValue = strtotime($Value); //EWebParams::_DateTimeFormat);

                Messages::ShowMessage($From, MessageType::Debug);
                Messages::ShowMessage($To, MessageType::Debug);
                Messages::ShowMessage($Value, MessageType::Debug);
                Messages::ShowMessage(strtotime($From), MessageType::Debug);
                Messages::ShowMessage(strtotime($To), MessageType::Debug);
                Messages::ShowMessage(strtotime($Value), MessageType::Debug);

                Messages::ShowMessage(
                        "DateStart={$dateStart} // DateEnd={$dateEnd} // " .
                                "currentValue={$currentValue} | Result=" .
                                "{(($IncludePrecision && ($currentValue >= $dateStart) && ($currentValue <= $dateEnd)) ||
                (!$IncludePrecision && ($currentValue > $dateStart) && ($currentValue < $dateEnd)))}",
                        MessageType::Debug
                );

                return (($IncludePrecision && ($currentValue >= $dateStart) && ($currentValue <= $dateEnd)) ||
                        (!$IncludePrecision && ($currentValue > $dateStart) && ($currentValue < $dateEnd)));
        }
}
