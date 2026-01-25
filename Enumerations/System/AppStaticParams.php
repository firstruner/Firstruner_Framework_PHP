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


/* 
 * -- File description --
 * @Type : Enumerate
 * @Mode : XP/BDD Creation
 * @Author : Christophe
 * @Update on : 19/06/2024 by : Patience KORIBIRAM
 */

namespace System;

use System\Default\_string;

/* PHP 8+
enum EAppParams
{
    //case ...;
}
*/

/* PHP 7+*/

abstract class AppStaticParams
{
    const ToolCode_EventAdd = "addevent";
    const ToolCode_UserManagement = "usermanagement";

    const ServerKey_DefaultPath = _string::EmptyString;

    const SessionKey_DynamicsParams = "DynamicsParams";
    const SessionKey_CurrentUser = "CurrentUser";
    const SessionKey_UsersList = "UsersList";
    const SessionKey_DepartmentsList = "DepartmentsList";
    const SessionKey_ClientsList = "ClientsList";
    const SessionKey_OAuth = "OAuth_Token";

    const CookieKey_TokensObjects = "Convergence_Token";

    const RequestKey_Username = "uname";
    const RequestKey_HashPassword = "pass";
    const RequestKey_Password = "password";
    const RequestKey_Debug = "debug";
    const RequestKey_CurrentToolCode = "CurrentToolCode";
    const RequestKey_DatasReceived = "datas";

    const RequestKey_VATNumber = "VAT";
    const RequestKey_CodePays = "Pays";

    const ControllerKey_Home = "home";
    const ControllerKey_Connexion = "connexion";
    const ControllerKey_UserProfile = "userProfile";
    const ControllerKey_AreaManagement = "areaMan";

    const LinkKey_VisitImage_Prospection = _string::EmptyString;
    const LinkKey_VisitImage_RDV = _string::EmptyString;
    const LinkKey_VisitImage_Tel = _string::EmptyString;
    const LinkKey_VisitImage_Note = _string::EmptyString;
    const LinkKey_VisitImage_EMail = _string::EmptyString;
    const LinkKey_VisitImage_SuiviClient = _string::EmptyString;

    const CommonValues_DateTimeFormat = "d/m/Y h:i:s";
}
