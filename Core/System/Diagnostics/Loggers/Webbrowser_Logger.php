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

use System\Exceptions\NotImplementedException;
use System\Default\_string;
use System\Enumerations;
use System\Forms\MessageType;

class Webbrowser_Logger implements ILogger
{
    public static function WriteInConsole(mixed $element, int $messageType = MessageType::Information)
    {
        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output = _string::EmptyString;

        if (Enumerations::HasFlag($messageType, MessageType::Error))
            $output .=  'console.error(\'' . json_encode($element) . ':\');';

        if (Enumerations::HasFlag($messageType, MessageType::Question))
            $output .=  'console.info(\'' . json_encode($element) . ':\');';

        if (Enumerations::HasFlag($messageType, MessageType::Warning))
            $output .=  'console.warn(\'' . json_encode($element) . ':\');';

        if (Enumerations::HasFlag($messageType, MessageType::Information))
            $output .=  'console.info(\'' . json_encode($element) . ':\');';

        if (Enumerations::HasFlag($messageType, MessageType::Debug))
            $output .=  'console.debug(\'' . json_encode($element) . ':\');';

        if (Enumerations::HasFlag($messageType, MessageType::Log))
            $output .=  'console.log(\'' . json_encode($element) . ':\');';

        echo "<script>" . $output . "</script>";

        ob_end_flush();
    }

    public static function Clear()
    {
        echo "<script>console.clear();</script>";
    }

    public function Write(string $element, string $context = 'Default context')
    {
        Webbrowser_Logger::WriteInConsole($context . " : " . $element);
    }

    public function Load(string $path)
    {
        throw new NotImplementedException("Fonction non disponible pour ce logger");
    }

    public function __toString()
    {
        throw new NotImplementedException("Fonction non disponible pour ce logger");
    }
}
