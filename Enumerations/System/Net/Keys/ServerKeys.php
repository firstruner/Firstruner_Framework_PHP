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

namespace System\Net\Keys;

/* PHP 8+
 enum EAppParams
 {
     //case ...;
 }
 */

/* PHP 7+*/

abstract class ServerKeys
{
    public const UserAgent = "HTTP_USER_AGENT";
    public const IPClient = "REMOTE_ADDR";
    public const IPClient_Forwarded = "HTTP_X_FORWARDED_FOR";
    public const IPClient_HttpClient = "HTTP_CLIENT_IP";
    public const IPClient_Localhost = "127.0.0.1";
    public const IPClient_Localhost_name = "localhost";
    public const IPClient_Localhost_Short = "::1";
}
