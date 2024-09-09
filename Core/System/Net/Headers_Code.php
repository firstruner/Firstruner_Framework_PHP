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

namespace System\Net;

/* PHP 8+
enum EApiHeaders_Code
{
    //case ...;
}
*/

/* PHP 7+*/
abstract class Headers_Code
{
    const Success = "HTTP/1.1 200 SUCCESS";
    const Unauthorized = "HTTP/1.1 401 UNAUTHORIZED";
    const BadRequest = "HTTP/1.1 400 BAD REQUEST";
    
    const Success_Code = 200;
    const Unauthorized_Code = 401;
    const BadRequest_Code = 400;

    public static function GetEnum(int $codeValue, bool $full = false)
    {
        switch ($codeValue)
        {
            case 200:
                return $full ? Headers_Code::Success : Headers_Code::Success_Code;
            case 401:
                return $full ? Headers_Code::Unauthorized : Headers_Code::Unauthorized_Code;
            default:
            case 400:
                return $full ? Headers_Code::BadRequest : Headers_Code::BadRequest_Code;
        }
    }
}
