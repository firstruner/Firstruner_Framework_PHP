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
 * UT : Pass
 */

//$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

/*if (isset($trace[1]))
{
      echo "### NEW TEST ###";
      echo PHP_EOL . "UT Helper depuis : " . $trace[1]['file'] . " ligne " . $trace[1]['line'] . PHP_EOL;
}*/

// Chargement du vendor
require_once(__DIR__ . '/../vendor/autoload.php');

// Chargement du framework
require_once(__DIR__ . '/../loader.php');
