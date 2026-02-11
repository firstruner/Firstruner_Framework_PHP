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

final class Decoder
{
    /**
     * Decode JSON
     *
     * @param $json
     * @param $assoc
     * @param $depth
     * @param $options
     */
    public static function DecodeJson()
    {
        $args = func_get_args();
        $response = call_user_func_array('json_decode', $args);
        if ($response === null && isset($args['0'])) {
            $response = $args['0'];
        }
        return $response;
    }

    /**
     * Decode XML
     *
     * @param $data
     * @param $class_name
     * @param $options
     * @param $ns
     * @param $is_prefix
     */
    public static function DecodeXml()
    {
        $args = func_get_args();
        $response = @call_user_func_array('simplexml_load_string', $args);
        if ($response === false && array_key_exists('0', $args)) {
            $response = $args['0'];
        }
        return $response;
    }
}
