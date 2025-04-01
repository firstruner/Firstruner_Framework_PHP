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

namespace System\Forms;

/* PHP 8+
enum EMessageType
{
    //case ...;
}
*/

/* PHP 7+*/

abstract class MessageType
{
    const None = 0;
    const Error = 16;
    const Hand = 16;
    const Stop = 16;
    const Question = 32;
    const Exclamation = 48;
    const Warning = 48;
    const Asterisk = 64;
    const Information = 64;
    const Debug = 1024;
    const Log = 2048;
}
