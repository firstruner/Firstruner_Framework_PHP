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
use System\Default\_string;

class TextFile_Logger implements ILogger
{
    private CCollection $log;
    private string $logPath;
    public bool $AutoSave = false;

    private function initiateLog()
    {
        $this->log = new CCollection(
            type: gettype(_string::EmptyString)
        );
    }

    public function __construct()
    {
        $this->initiateLog();
    }

    function Write(string $element, string $context) {}

    function Load(string $path)
    {
        $this->logPath = $path;
        $this->initiateLog();

        $content = file_get_contents($path);
        $arContent = explode(_string::NewLine, $content);

        foreach ($arContent as $content)
            $this->log->Add($arContent);
    }

    function Save(string $path)
    {
        $this->logPath = $path;
        file_put_contents($path, $this);
    }

    function __toString()
    {
        $output = _string::EmptyString;

        foreach ($this->log as $k => $p)
            $output .= $k . _string::Tabulation . $p . _string::NewLine;

        return $output;
    }

    static function WriteInConsole(mixed $element, int $messageType) {}

    static function Clear() {}
}
