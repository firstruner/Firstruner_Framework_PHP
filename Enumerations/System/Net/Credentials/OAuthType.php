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

namespace System\Net\Credentials;

/* PHP 8+
enum EAppParams
{
    //case ...;
}
*/

/* PHP 7+*/

abstract class OAuthType
{
    const Undefined = array("Code" => 0, "Text" => "Undefined");
    const JIT_Application = array("Code" => 1, "Text" => "JIT_Application");
    const Framework = array("Code" => 2, "Text" => "Firstruner_Framework");

    const Text_Connected = "connected";
    const Text_Disconnected = "disconnected";

    public static function GetEnum(int $codeValue)
    {
        switch ($codeValue) {
            case 1:
                return OAuthType::JIT_Application;
            case 2:
                return OAuthType::Framework;
            default:
                return OAuthType::Undefined;
        }
    }
}
