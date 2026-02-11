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

final class Thread
{
    private $command;
    private $process;
    private $pipes;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function Start()
    {
        $descriptorSpec = [
            0 => ["pipe", "r"],  // stdin
            1 => ["pipe", "w"],  // stdout
            2 => ["pipe", "w"]   // stderr
        ];
        $this->process = proc_open($this->command, $descriptorSpec, $this->pipes);

        if (is_resource($this->process)) {
            return true; // Processus démarré
        }
        return false; // Échec
    }

    public function Output()
    {
        return stream_get_contents($this->pipes[1]); // Lire stdout
    }

    public function Error()
    {
        return stream_get_contents($this->pipes[2]); // Lire stderr
    }

    public function Wait()
    {
        return proc_close($this->process); // Attendre la fin du processus
    }

    public function Terminate()
    {
        if (is_resource($this->process)) {
            proc_terminate($this->process); // Arrêter le processus
        }
    }
}
