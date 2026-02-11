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

namespace System\Diagnostics;

use System\Collections\CCollection;
use System\Collections\KeyValuePair;
use System\Default\_string;
use System\Environment;

class Internal_Logger implements ILogger
{
    private CCollection $log;

    public function __construct()
    {
        $this->log = new CCollection(
            type: gettype(
                new KeyValuePair(
                    _string::EmptyString,
                    null
                )
            )
        );
    }

    public function Write(string $element, string $context = Environment::DebugTag)
    {
        $this->log->Add(new KeyValuePair(
            $context,
            $element
        ));
    }

    public function Load(string $path)
    {
        return $this->log->ToArray();
    }

    public function __toString()
    {
        $output = _string::EmptyString;

        foreach ($this->log as $k => $p)
            $output .= $k . _string::Tabulation . $p . _string::NewLine;

        return $output;
    }

    static function WriteInConsole(mixed $element, int $messageType) {}

    static function Clear() {}
}
