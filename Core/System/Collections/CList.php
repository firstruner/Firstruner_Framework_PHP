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
 * UT : Pass
 */

namespace System\Collections;

use OverflowException;
use System\Collections\Iterators\LinqIterator;
use System\Exceptions\ArgumentNullException;

/**
 * Class de collection
 */
class CList extends LinqIterator
{
      private int $max_capacity = 0;

      /**
       * Constructeur
       */
      function __construct(mixed $type = null, int $capacity = 0)
      {
            parent::__construct([], 0, $type);
            $this->max_capacity = $capacity;
      }

      function __destruct()
      {
            unset($this->defaultValue);
            unset($this->objectType);
      }

      /**
       * Ajoute un élément à la liste
       * @param type $item Élément à ajouter
       */
      public function Add(mixed $item): void
      {
            if ($this->count() == $this->max_capacity)
                  throw new OverflowException("Capacity exceed : object is full");

            parent::Add($item);
      }

      /**
       * Ajoute des éléments à la liste
       * @param type $item Élément à ajouter
       */
      public function AddRange(array $items): void
      {
            if (($this->count() == $this->max_capacity)
                  || (($this->count() + \count($items)) >= $this->max_capacity)
            )
                  throw new OverflowException("Capacity : object is full or full with items");

            parent::AddRange($items);
      }

      /**
       * Modifie la capacité de la liste
       * @param int $newCapacity Élément à ajouter
       */
      public function SetCapacity(int $newCapacity, bool $shrink = false): void
      {
            $this->Trim();

            if ($newCapacity < $this->count())
                  throw new OverflowException("Capacity : New capacity over the possible");

            $this->max_capacity = $newCapacity;
      }

      /**
       * Obtien la capacité de la liste
       */
      public function GetCapacity(): int
      {
            return $this->max_capacity;
      }
}
