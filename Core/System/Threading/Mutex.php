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

use System\Exceptions\TimeoutException;

final class Mutex
{
    private $mutex;
    private $lockFile;
    private $locked = false;
    private $isOwner = false;
    private $timeout;
    private $maxWaitTime;
    private $ownerPid;

    public function __construct($lockFile = null, $maxWaitTime = 10)
    {
        $this->lockFile = $lockFile ?: sys_get_temp_dir() . '/mutex.lock';
        $this->maxWaitTime = $maxWaitTime; // Temps d'attente maximal pour obtenir le verrou (en secondes)
        $this->mutex = fopen($this->lockFile, 'w');
        $this->timeout = time();
    }

    // Acquérir le verrou
    public function Lock()
    {
        if ($this->locked) {
            return false; // Si déjà verrouillé
        }

        $startTime = time();

        // Attente pour obtenir le verrou
        while (!flock($this->mutex, LOCK_EX | LOCK_NB)) {
            if ((time() - $startTime) >= $this->maxWaitTime) {
                throw new \Exception("Timeout lors de l'acquisition du verrou.");
            }
            usleep(100000); // Attendre un peu avant de réessayer
        }

        $this->locked = true;
        $this->isOwner = true;
        $this->ownerPid = getmypid();
        return true;
    }

    // Libérer le verrou
    public function Unlock()
    {
        if ($this->locked && $this->isOwner) {
            flock($this->mutex, LOCK_UN); // Libérer le verrou
            $this->locked = false;
            $this->isOwner = false;
            $this->ownerPid = null;
        } else {
            throw new \Exception("Impossible de libérer un verrou que l'on ne possède pas.");
        }
    }

    // Vérifier si le verrou est acquis
    public function IsLocked(): bool
    {
        return $this->locked;
    }

    // Vérifier si l'appelant est le propriétaire du verrou
    public function IsOwner(): bool
    {
        return $this->isOwner;
    }

    // Obtenir le PID du propriétaire actuel du verrou
    public function OwnerPid()
    {
        return $this->ownerPid;
    }

    // Temps d'attente maximal pour obtenir le verrou
    public function SetMaxWaitTime($seconds)
    {
        $this->maxWaitTime = $seconds;
    }

    public function GetMaxWaitTime()
    {
        return $this->maxWaitTime;
    }

    // Retourner si le verrou est disponible
    public function TryLock(): bool
    {
        if ($this->locked) {
            return false; // Si déjà verrouillé
        }

        return flock($this->mutex, LOCK_EX | LOCK_NB);
    }

    // Attendre et obtenir le verrou pendant un certain temps
    public function WaitForLock()
    {
        $startTime = time();
        while (!$this->lock()) {
            if ((time() - $startTime) >= $this->maxWaitTime) {
                throw new \Exception("Timeout lors de l'attente pour obtenir le verrou.");
            }
            usleep(100000); // Attendre avant de réessayer
        }
    }

    public function WaitOne()
    {
        $startTime = time();

        // Attente active jusqu'à ce que le verrou soit acquis ou que le temps dépasse la limite
        while (!$this->IsLocked()) {
            if ((time() - $startTime) >= $this->maxWaitTime) {
                throw new \Exception("Timeout lors de l'attente du verrou.");
            }
            usleep(100000); // Attendre avant de réessayer
        }
    }

    // Déstructeur
    public function __destruct()
    {
        if ($this->locked) {
            $this->unlock();
        }
        fclose($this->mutex);
    }

    public function ReleaseMutex()
    {
        if ($this->locked) {
            $this->unlock();
        }

        fclose($this->mutex);
    }
}
