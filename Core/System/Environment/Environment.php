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

namespace System\Environment;

use System\Environment;
use System\Net\Keys\AppKeys;
use System\Net\Keys\ServerKeys;

/*
 * -- File description --
 * @Type : Class
 * @Mode : XP/BDD Creation
 * @Author : Christophe
 * @Update on : 19/06/2024 by : Patience KORIBIRAM
 */

class Client
{
    public static function IsMobile()
    {
        return preg_match(
            Environment::MobileTags,
            $_SERVER[ServerKeys::UserAgent]
        );
    }

    public static function IsDebug()
    {
        return isset($_REQUEST[AppKeys::DebugMode]);
    }

    public static function Get_MemoryLimits(): int
    {
        return Client::Get_BytesSizeFromString(ini_get('memory_limit'));
    }

    public static function Get_BytesSizeFromString($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
                // The 'G' modifier is available
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    public static function GetOS()
    {
        return PHP_OS;
    }
}
