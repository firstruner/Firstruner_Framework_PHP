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

namespace System\Threading;

class Process
{
    private $command;
    private $descriptorSpec;
    private $process;
    private $pipes;
    private $status;
    private $output = [];
    private $errorOutput = [];
    private $exitCode;
    private $timeout;

    public function __construct(string $command)
    {
        $this->command = $command;
        $this->descriptorSpec = [
            0 => ["pipe", "r"],  // stdin
            1 => ["pipe", "w"],  // stdout
            2 => ["pipe", "w"]   // stderr
        ];
        $this->timeout = 60; // default timeout
    }

    public function Start(): bool
    {
        $this->process = proc_open($this->command, $this->descriptorSpec, $this->pipes);

        if (is_resource($this->process)) {
            $this->status = 'Running';
            return true;
        }

        return false;
    }

    public function WriteInput(string $input)
    {
        if ($this->status !== 'Running') {
            throw new \Exception("Process is not running.");
        }

        fwrite($this->pipes[0], $input);
    }

    public function ReadOutput(): string
    {
        if ($this->status !== 'Running') {
            throw new \Exception("Process is not running.");
        }

        $this->output[] = fgets($this->pipes[1]);
        return implode("\n", $this->output);
    }

    public function ReadError(): string
    {
        if ($this->status !== 'Running') {
            throw new \Exception("Process is not running.");
        }

        $this->errorOutput[] = fgets($this->pipes[2]);
        return implode("\n", $this->errorOutput);
    }

    public function Wait(): void
    {
        if ($this->status !== 'Running') {
            throw new \Exception("Process is not running.");
        }

        $this->status = 'Completed';
        $this->exitCode = proc_close($this->process);
    }

    public function ExitCode(): int
    {
        return $this->exitCode ?? -1;
    }

    public function IsRunning(): bool
    {
        return $this->status === 'Running';
    }

    public function SetTimeout(int $seconds): void
    {
        $this->timeout = $seconds;
    }

    public function GetTimeout(): int
    {
        return $this->timeout;
    }

    public function Status(): string
    {
        return $this->status;
    }

    public function Terminate()
    {
        if ($this->status === 'Running') {
            proc_terminate($this->process);
            $this->status = 'Terminated';
        }
    }

    public function __destruct()
    {
        if ($this->status === 'Running') {
            $this->Terminate();
        }
    }
}
  
  // Exemple d'utilisation
//   try {
//       $process = new Process("php -r 'echo \"Hello from PHP Process!\"; sleep(2);'");
//       $process->start();
//       echo "Process started...\n";
  
//       // Lire la sortie du processus
//       sleep(1); // Attente pour simuler un délai avant de lire
//       echo "Output: " . $process->readOutput() . "\n";
  
//       // Attendre que le processus se termine
//       $process->wait();
//       echo "Process completed with exit code: " . $process->getExitCode() . "\n";
  
//       // Lire les erreurs éventuelles
//       $error = $process->readError();
//       if ($error) {
//           echo "Error: $error\n";
//       }
  
//   } catch (Exception $e) {
//       echo "Error: " . $e->getMessage();
//   }
