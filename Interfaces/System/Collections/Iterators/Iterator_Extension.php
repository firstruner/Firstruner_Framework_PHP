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

namespace System\Collections\Iterators;

/**
 * Interface de gestion des collections d'objets
 */
interface Iterator_Extension
{
    function Add(mixed $item): void; // Ok
    function AddRange(array $items): void; // Ok

    function Remove(int $index): void; // Ok
    function RemoveItems(array $indexes): void; // Ok
    function Clear(): void; // Ok

    function Get(int $index): mixed; // Ok
    function CopyTo(): LinqIterator; // Ok
    function ToArray(): array; // Ok
    function previous(): void; // Ok
    function SetPosition(int $index): void; // Ok

    function Contains(mixed $item): bool; // Ok
    function IsEmpty(): bool; // Ok
}
