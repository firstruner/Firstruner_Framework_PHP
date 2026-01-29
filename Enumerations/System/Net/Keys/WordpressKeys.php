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

define("WP_Initialize", "init");
define("WP_FrontScripts", "wp_enqueue_scripts");
define("WP_BackScripts", "admin_enqueue_scripts");
define("WP_ControlsScripts", "customize_controls_enqueue_scripts");
define("WP_LoginScripts", "login_enqueue_scripts");
