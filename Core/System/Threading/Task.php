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

class Task
{
    private $callback;
    private $result = null;
    private $exception = null;
    private $status = 'Created'; // Created, Running, RanToCompletion, Faulted, Canceled
    private $isCanceled = false;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public static function Run(callable $callback)
    {
        $task = new self($callback);
        $task->Start();
        return $task;
    }

    public function Start()
    {
        if ($this->status !== 'Created') {
            throw new \Exception("Task already started.");
        }
        $this->status = 'Running';

        try {
            if (!$this->isCanceled) {
                $this->result = call_user_func($this->callback);
                $this->status = 'RanToCompletion';
            } else {
                $this->status = 'Canceled';
            }
        } catch (\Exception $e) {
            $this->exception = $e;
            $this->status = 'Faulted';
        }
    }

    public function Result()
    {
        if ($this->status === 'Faulted') {
            throw $this->exception;
        }
        if ($this->status === 'Canceled') {
            throw new \Exception("Task was canceled.");
        }
        return $this->result;
    }

    public function IsCompleted()
    {
        return $this->status === 'RanToCompletion';
    }

    public function IsFaulted()
    {
        return $this->status === 'Faulted';
    }

    public function IsCanceled()
    {
        return $this->status === 'Canceled';
    }

    public function Then(callable $next)
    {
        if ($this->IsCompleted()) {
            $next($this->result);
        }
    }

    public function Cancel()
    {
        if ($this->status === 'Running') {
            $this->isCanceled = true;
        }
    }

    public function Status()
    {
        return $this->status;
    }
}
