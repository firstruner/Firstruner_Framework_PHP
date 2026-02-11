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



/** 
 * -- File description --
 * @Type : MethodClass
 * @Mode : XP/BDD Creation
 * @Author : Christophe BOULAS
 * @Update on : 21/01/2026 by : Christophe BOULAS
 */


namespace System\Net\Session;

use System\AppStaticParams;
use System\Net\Templates\HeadersTemplates;
use System\_String;
use System\Net\Headers_Code;
use System\Net\Headers_Content;
use System\Net\Http\Response;
use System\Net\Keys\CookieKeys;
use System\Net\Session;

/**
 * Description of SessionManagment
 *
 * @author Christophe
 */

class SessionManagment
{
    public static function RenewCookie()
    {
        SessionManagment::SetCookie(
            $_COOKIE[CookieKeys::JSessionName]
        );
    }

    private static function Reconnect(string $jsession) {}

    public static function SetCookie(string $jsession)
    {
        setcookie(
            CookieKeys::JSessionName,
            $jsession,
            CookieKeys::ExpirationDelay
        );
    }

    public static function CheckConnected()
    {
        $currentUser = Session::Get(AppStaticParams::SessionKey_CurrentUser);

        if ($currentUser != null) {
            // Réactualise le temps du Cookie
            SessionManagment::RenewCookie();

            return new Response(
                _String::Generate(
                    "",
                    array("")
                ),
                Headers_Code::Success_Code,
                ['content-type' => Headers_Content::TXT_Code]
            );
        }

        // TODO : Si NOk Récupérer l'état du cookie
        if (isset($_COOKIE[CookieKeys::JSessionName])) {
            if ($_COOKIE[CookieKeys::JSessionName] != null) {
                // TODO : Si Cookie Ok => ReconnectAuto
                if (SessionManagment::Reconnect($_COOKIE[CookieKeys::JSessionName])) {
                    // Réactualise le temps du Cookie
                    SessionManagment::RenewCookie();

                    return new Response(
                        _String::Generate(
                            "",
                            array("")
                        ),
                        Headers_Code::Success_Code,
                        ['content-type' => Headers_Content::TXT_Code]
                    );
                }
            }
        }

        // TODO : Si NOk => Redirect ConnexionController
        return new Response(
            _String::Generate(
                HeadersTemplates::JS_Redirection,
                array(AppStaticParams::ServerKey_DefaultPath . AppStaticParams::ControllerKey_Connexion)
            ),
            Headers_Code::Success_Code,
            ['content-type' => Headers_Content::TXT_Code]
        );
    }
}
