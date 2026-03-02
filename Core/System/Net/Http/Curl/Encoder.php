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

namespace System\Net\Http\Curl;

final class Encoder
{
    /**
     * Encode JSON
     *
     * Wrap json_encode() to throw error when the value being encoded fails.
     *
     * @param                  $value
     * @param                  $options
     * @param                  $depth
     * @return string
     * @throws \ErrorException
     */
    public static function EncodeJson()
    {
        $args = func_get_args();
        $value = call_user_func_array('json_encode', $args);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message = 'json_encode error: ' . json_last_error_msg();
            throw new \ErrorException($error_message);
        }
        return $value;
    }
}
