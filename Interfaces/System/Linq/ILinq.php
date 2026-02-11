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

namespace System\Linq;

use System\Collections\Iterators\LinqIterator;

/**
 * Interface de gestion des collections d'objets
 */
interface ILinq
{
      function First(): mixed; // Ok
      function FirstWhere(string $predica, mixed $value, int $searchMethod, callable $closure = null): mixed; // Ok
      function FirstOrDefault(): mixed; // Ok
      function FirstWhereOrDefault(string $predica, mixed $value, int $searchMethod, callable $closure = null): mixed; // Ok
      function Last(): mixed; // Ok
      function LastWhere(string $predica, mixed $value, int $searchMethod, callable $closure = null): mixed; // Ok
      function LastOrDefault(): mixed; // Ok
      function LastWhereOrDefault(string $predica, mixed $value, int $searchMethod, callable $closure = null): mixed; // Ok

      function SetDefault(mixed $value): LinqIterator; // Ok
      function Take(int $start, int $end): LinqIterator; // Ok      
      function Join(LinqIterator $iteration): LinqIterator; // Ok

      function Where(string $predica, mixed $value, int $searchMethod, callable $closure = null): LinqIterator; // Ok
      function Except(string $predica, mixed $value, int $searchMethod, callable $closure = null): LinqIterator; // Ok

      function Any(string $predica): bool; // Ok
      function GroupBy(string $predica, callable $closure = null): LinqIterator; // Ok
      function Min(string $predica, callable $closure = null): mixed; // Ok
      function Max(string $predica, callable $closure = null): mixed; // Ok
      function Sum(string $predica, callable $closure = null): mixed; // Ok
      function Avg(string $predica, callable $closure = null): mixed; // Ok
}
