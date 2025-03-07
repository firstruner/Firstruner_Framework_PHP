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


       
/** 
* -- File description --
* @Type : MethodClass
* @Mode : XP/BDD Creation
* @Author : Christophe BOULAS
* @Update on : 20/06/2024 by : Patience KORIBIRAM
*/

namespace System\Net\Http\Curl;

final class ArrayUtil
{
    /**
     * Is Array Assoc
     *
     * @param       $array
     * @return bool
     */
    public static function IsArrayAssoc($array)
    {
        return (
            $array instanceof CaseInsensitiveArray ||
            (bool)count(array_filter(array_keys($array), 'is_string'))
        );
    }

    /**
     * Is Array Assoc
     *
     * @deprecated Use ArrayUtil::isArrayAssoc().
     * @param       $array
     * @return bool
     */
    public static function Is_array_assoc($array)
    {
        return static::isArrayAssoc($array);
    }

    /**
     * Is Array Multidim
     *
     * @param       $array
     * @return bool
     */
    public static function IsArrayMultidim($array)
    {
        if (!is_array($array)) {
            return false;
        }

        return (bool)count(array_filter($array, 'is_array'));
    }

    /**
     * Is Array Multidim
     *
     * @deprecated Use ArrayUtil::isArrayMultidim().
     * @param       $array
     * @return bool
     */
    public static function Is_array_multidim($array)
    {
        return static::isArrayMultidim($array);
    }

    /**
     * Array Flatten Multidim
     *
     * @param        $array
     * @param        $prefix
     * @return array
     */
    public static function ArrayFlattenMultidim($array, $prefix = false)
    {
        $return = [];
        if (is_array($array) || is_object($array)) {
            if (empty($array)) {
                $return[$prefix] = '';
            } else {
                $arrays_to_merge = [];

                foreach ($array as $key => $value) {
                    if (is_scalar($value)) {
                        if ($prefix) {
                            $arrays_to_merge[] = [
                                $prefix . '[' . $key . ']' => $value,
                            ];
                        } else {
                            $arrays_to_merge[] = [
                                $key => $value,
                            ];
                        }
                    } elseif ($value instanceof \CURLFile) {
                        $arrays_to_merge[] = [
                            $key => $value,
                        ];
                    } elseif ($value instanceof \CURLStringFile) {
                        $arrays_to_merge[] = [
                            $key => $value,
                        ];
                    } else {
                        $arrays_to_merge[] = self::arrayFlattenMultidim(
                            $value,
                            $prefix ? $prefix . '[' . $key . ']' : $key
                        );
                    }
                }

                $return = array_merge($return, ...$arrays_to_merge);
            }
        } elseif ($array === null) {
            $return[$prefix] = $array;
        }
        return $return;
    }

    /**
     * Array Flatten Multidim
     *
     * @deprecated Use ArrayUtil::arrayFlattenMultidim().
     * @param        $array
     * @param        $prefix
     * @return array
     */
    public static function Array_flatten_multidim($array, $prefix = false)
    {
        return static::arrayFlattenMultidim($array, $prefix);
    }

    /**
     * Array Random
     *
     * @param        $array
     * @return mixed
     */
    public static function ArrayRandom($array)
    {
        return $array[static::arrayRandomIndex($array)];
    }

    /**
     * Array Random Index
     *
     * @param      $array
     * @return int
     */
    public static function ArrayRandomIndex($array)
    {
        return mt_rand(0, count($array) - 1);
    }

    /**
     * Array Random
     *
     * @deprecated Use ArrayUtil::arrayRandom().
     * @param        $array
     * @return mixed
     */
    public static function Array_random($array)
    {
        return static::arrayRandom($array);
    }
}
