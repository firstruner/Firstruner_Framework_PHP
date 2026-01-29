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

namespace fpdf;

$type = 'Core';
$name = 'Courier';
$up = -100;
$ut = 50;
for ($i = 0; $i <= 255; $i++)
	$cw[chr($i)] = 600;
$enc = 'cp1252';
$uv = array(0 => array(0, 128), 128 => 8364, 130 => 8218, 131 => 402, 132 => 8222, 133 => 8230, 134 => array(8224, 2), 136 => 710, 137 => 8240, 138 => 352, 139 => 8249, 140 => 338, 142 => 381, 145 => array(8216, 2), 147 => array(8220, 2), 149 => 8226, 150 => array(8211, 2), 152 => 732, 153 => 8482, 154 => 353, 155 => 8250, 156 => 339, 158 => 382, 159 => 376, 160 => array(160, 96));
