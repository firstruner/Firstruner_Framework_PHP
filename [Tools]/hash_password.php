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

use System\Net\Http\RequestForm;
use System\Net\Http\RequestMethod;
use System\Security\Cryptography\EncryptionMode;
use System\Security\Cryptography\Hashing as frstHashing;

require_once(__DIR__ . "/../loader.php");

define("LoginForm_Password", "pwd");

$pwd = RequestForm::Get(LoginForm_Password, method: RequestMethod::GET);
echo frstHashing::Hash(EncryptionMode::SHA_256, $pwd);
