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

namespace System\Collections;

class KeyValuePair
{
    public $Value = null;
    private ?string $p_key = null;

    public function GetKey()
    {
        return $this->p_key;
    }

    public function __construct1()
    {
        die("Constructeur interdit");
    }

    public function __construct(string $key, $value = null)
    {
        $this->p_key = $key;
        $this->Value = $value;
    }

    public static function Parse($value, string $spliChar = ":"): ?KeyValuePair
    {
        if (is_string($value)) {
            $kv = explode($spliChar, $value);

            if (count($kv) != 2) return null;

            return new KeyValuePair($kv[0], $kv[1]);
        } else {
            if ($value instanceof KeyValuePair)
                return new KeyValuePair($value->GetKey(), $value->Value);

            return null;
        }
    }
}
