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

namespace System\Net;
/* PHP 8+
enum EApiHeaders_Content
{
    //case ...;
}
*/

/* PHP 7+*/

abstract class Headers_Content
{
    const HTML = "Content-Type: text/html";
    const TXT = "Content-Type: text/plain";
    const XML = "Content-Type: text/xml";
    const JSON = "Content-Type: application/json";

    const HTML_Code = "text/html";
    const TXT_Code = "text/plain";
    const XML_Code = "text/xml";
    const JSON_Code = "application/json";
}
