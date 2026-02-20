<?php

use System\Net\Http\RequestForm;
use System\Net\Http\RequestMethod;
use System\Security\Cryptography\EncryptionMode;
use System\Security\Cryptography\Hashing as frstHashing;

require_once(__DIR__ . "/../loader.php");

define("LoginForm_Password", "pwd");

$pwd = RequestForm::Get(LoginForm_Password, method:RequestMethod::GET);
echo frstHashing::Hash(EncryptionMode::SHA_256, $pwd);