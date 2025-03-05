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

 namespace System\Net\Keys;

 /* PHP 8+
 enum EAppParams
 {
     //case ...;
 }
 */
 
 /* PHP 7+*/
 abstract class AppKeys
 {
    public const DebugMode = "debugmode";
    public const SimsMode = "SimsMode";
    public const DateFormat = "DateFormat";
    public const DateTimeFormat = "DateTimeFormat";    
    public const DateFormatAffichage = "DateFormatAffichage";
    public const DefaultHashProtocol = "sha256";
    public const CurrentUser = "CurrentUser";
    public const DoubleTab = "DoubleTab";
    public const TripleTab = "TripleTab";
    public const ApiMode = "ApiMode";
    public const SlideURL = "SlideFile";
    public const GoogleApi_Geoloc = "GoogleApi_Geoloc";
    public const AccesTimeLimitation = "AccesTimeLimitation";
    public const AccesDateTimeLimitation_LevelByPass = "AccesDateTimeLimitation_LevelByPass";
    public const LevelManagerMenu = "LevelManagerMenu";
    public const NightMode = "NightMode";
    public const AccesDayLimitation = "AccesDayLimitation";
    public const MaxCourtoisieVisit = "MaxCourtoisieVisit";
    public const LevelAdmin = "LevelAdmin";
 }