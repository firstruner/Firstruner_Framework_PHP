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

namespace System\Net\Credentials;

use Convergence\Framework\Enumerations\System\AppStaticParams;
use System\Net\Cookies;
use System\Net\Session;
use System\Default\_string;

abstract class OAuthManager implements IOAuthManager
{
    public function Set(IOauth $oauth, $datasAppended = _string::EmptyString)
    {
        // Enregistrement côté server
        Session::Set(AppStaticParams::SessionKey_OAuth, $oauth);

        // Enregistrement côté client
        setcookie(
            AppStaticParams::CookieKey_TokensObjects,
            $oauth->generateJSon($datasAppended),
            time() + $oauth->getTimeLeft()
        );
    }

    public function Get(bool $cookieGetting = false)
    {
        return
            $cookieGetting
            ? Cookies::Get(AppStaticParams::CookieKey_TokensObjects)
            : Session::Get(AppStaticParams::SessionKey_OAuth);
    }
}
