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

use System\Default\_string;

interface IOAuth
{
    // public
    function checkToken($value);
    function timeConnected();
    function getParameters();
    function generateJSon($datasAppended);
    function setState($_statusCode, $_params = _string::EmptyString, $renewTokens = _string::EmptyString);
    function getTimeLeft();
    function getTimeLeft_Renew();

    // private
    //function extract_props($object);

    // ctor
    function __construct();
    function __construct3($_params, $_statusCode, $_type);
    function __construct1(string $jsonString);
}
