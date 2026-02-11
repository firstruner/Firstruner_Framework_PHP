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

use \ArrayIterator;
use \Exception;
use System\Collections\CCollection_ErrorCodes;
use System\Exceptions\ArgumentException;
use System\Exceptions\ArgumentNullException;
use System\Linq\ILinq;
use System\Linq\ILinqTyped;
use System\Linq\ISorter;
use System\Collections\Iterators\Iterator_Extension;
use System\Collections\Iterators\IFilter;
use System\_String;
use System\Linq\Operators;

/**
 * Class d'itération prenant en charge des fonctions Linq
 */
class LinqIterator
extends ArrayIterator
implements
    Iterator_Extension,
    ISorter,
    ILinq,
    ILinqTyped,
    IFilter
{
    protected mixed $objectType = null;
    private mixed $defaultValue = null;

    /**
     * Class Part
     */

    /**
     * Constructeur
     */
    function __construct(array $initialArray = [], int $flags = 0, mixed $type = null)
    {
        parent::__construct($initialArray, $flags);

        $this->objectType = $type;
    }

    function __destruct()
    {
        unset($this->defaultValue);
        unset($this->objectType);
    }

    /**
     * Iterator_Extension Part
     */

    /**
     * Ajoute un élément à la liste
     * @param mixed $item Élément à ajouter
     */
    public function Add(mixed $item): void
    {
        if (!isset($item)) throw new ArgumentNullException("'item' is null", CCollection_ErrorCodes::Null);

        $this->CheckInstanceObject($item);

        if (is_array($item))
            throw new ArgumentException("'item' is an array, try use AddRange method", CCollection_ErrorCodes::IncorrectMethod);

        $this->append($item);
    }

    /**
     * Ajoute des éléments à la liste
     * @param mixed $item Élément à ajouter
     */
    public function AddRange(array $items): void
    {
        if (!isset($items)) throw new ArgumentNullException("'items' is null", CCollection_ErrorCodes::Null);

        if (isset($this->objectType))
            foreach ($items as $item)
                $this->CheckInstanceObject($item);

        $this->append($items);
    }

    /**
     * Retire un élément
     * @param mixed $item Index de l'élément à retirer
     */
    public function Remove(int $index): void
    {
        if (($this->IsEmpty())
            || (!$this->offsetExists($index))
        )
            throw new ArgumentException("Argument value does not belong to the collection", CCollection_ErrorCodes::IndexOversize);

        $this->offsetUnset($index);
    }

    /**
     * Retire des éléments
     * @param mixed $item Index de l'élément à retirer
     */
    public function RemoveItems(array $indexes): void
    {
        foreach ($indexes as $index)
            $this->Remove($index);
    }

    /**
     * Vide la liste des éléments
     */
    public function Clear(): void
    {
        for ($index = 0; $index < $this->count(); $index++)
            $this->offsetUnset($index);

        $this->rewind();
    }

    /**
     * Retourne un élément du tableau
     * @param mixed $index Index de l'élément à retourner
     */
    public function Get(int $index): mixed
    {
        if (($this->IsEmpty())
            || (!$this->offsetExists($index))
        )
            throw new ArgumentException("Argument value does not belong to the collection", CCollection_ErrorCodes::IndexOversize);

        return $this[$index];
    }

    /**
     * Retourne une nouvelle instance du tableau d'éléments
     * @return mixed Tableau d'éléments
     */
    public function CopyTo(): self
    {
        return clone $this;
    }

    /**
     * Converti vers un tableau
     */
    public function ToArray(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * Définie la position à la précédente
     */
    public function previous(): void
    {
        if ($this->IsEmpty())
            throw new ArgumentException("no item in the collection", CCollection_ErrorCodes::EmptyCollection);

        if ($this->key() == 0)
            throw new ArgumentException("This is the first item of the collection", CCollection_ErrorCodes::FirstIndex);

        $currentKey = $this->key();

        $this->rewind();
        for ($pos = 0; $pos < $currentKey; $pos++)
            $this->next();
    }

    /**
     * Définie la position du curseur
     */
    public function SetPosition(int $index): void
    {
        if ($this->IsEmpty())
            throw new ArgumentException("no item in the collection", CCollection_ErrorCodes::EmptyCollection);

        if (($this->IsEmpty())
            || (!$this->offsetExists($index))
        )
            throw new ArgumentException("Argument value does not belong to the collection", CCollection_ErrorCodes::IndexOversize);

        $this->rewind();
        for ($pos = 0; $pos <= $index; $pos++)
            $this->next();
    }

    /**
     * Vérifie la présence d'un élément dans la liste
     * @param mixed $item Élément dont la présence est à vérifier
     * @return mixed Retourne un boolean
     */
    public function Contains(mixed $item): bool
    {
        if (!isset($item)) throw new ArgumentNullException("argument is null", CCollection_ErrorCodes::Null);

        if (!$this->CheckInstanceObject($item))
            throw new ArgumentException("argument is not a {$this->objectType} type", CCollection_ErrorCodes::TypeDismatch);

        $currentArray = $this->ToArray();
        return in_array($item, $currentArray);
    }

    /**
     * Indique si la collection est vide
     */
    public function IsEmpty(): bool
    {
        return ($this->count() == 0);
    }

    /**
     * ISorter Part
     */

    /**
     * Classe les éléments par ascendant
     */
    public function OrderingAsc(): self
    {
        $newArray = $this->ToArray();
        sort($newArray);

        return new self($newArray);
    }

    /**
     * Classe les éléments par ordre descendant
     */
    public function OrderingDesc(): self
    {
        $newArray = $this->ToArray();
        rsort($newArray);

        return new self($newArray);
    }

    /**
     * Retourne une collection triée dans l'ordre désirée
     */
    public function OrderBy(string $predica, ?callable $closure = null): self
    {
        if (_String::IsNullOrEmpty($predica)) throw new ArgumentNullException("predica is null or empty", CCollection_ErrorCodes::NullOrEmpty);

        $internalFctCompare =
            function ($a, $b)
            use ($predica, $closure) {
                return strcmp(
                    ($closure == null
                        ? $a->$predica
                        : $closure($a->$predica)),
                    ($closure == null
                        ? $b->$predica
                        : $closure($b->$predica))
                );
            };

        $this->uksort($internalFctCompare);

        return $this;
    }

    /**
     * Mélange les éléments
     */
    public function Shuffle(): self
    {
        $newArray = $this->ToArray();
        shuffle($newArray);

        return new self($newArray);
    }


    /**
     * ILinq Part
     */


    /**
     * Retourne le 1er élément
     */
    /**
     * @suppress PHP6601
     */
    public function First(): mixed
    {
        if ($this->IsEmpty())
            /** @noinspection PhpFullyQualifiedNameUsageInspection */
            throw new \Exception("Collection do not contains valid elements");

        return $this[0];
    }

    /**
     * Retourne le 1er élément selon les conditions where définie
     */
    /**
     * @suppress PHP6601
     */
    public function FirstWhere(string $predica, mixed $value, int $searchMethod, ?callable $closure = null): mixed
    {
        if ($this->IsEmpty())
            /** @noinspection PhpFullyQualifiedNameUsageInspection */
            throw new \Exception("Collection do not contains valid elements");

        return $this->Where($predica, $value, $searchMethod, $closure)->First();
    }

    /**
     * Retourne le 1er élément selon les conditions where définie ou Null ou l'élément par défaut
     */
    /**
     * @suppress PHP6601
     */
    public function FirstWhereOrDefault(string $predica, mixed $value, int $searchMethod, ?callable $closure = null): mixed
    {
        if ($this->IsEmpty())
            /** @noinspection PhpFullyQualifiedNameUsageInspection */
            throw new \Exception("Collection do not contains valid elements");

        return $this->Where($predica, $value, $searchMethod, $closure)->FirstOrDefault();
    }

    /**
     * Retourne le 1er élément, Null ou la valeur par défaut définie
     */
    public function FirstOrDefault(): mixed
    {
        return isset($this[0])
            ? $this[0]
            : (($this->defaultValue == null)
                ? null
                : $this->defaultValue);
    }

    /**
     * Retourne le dernier élément
     */
    /**
     * @suppress PHP6601
     */
    public function Last(): mixed
    {
        if ($this->IsEmpty())
            /** @noinspection PhpFullyQualifiedNameUsageInspection */
            throw new \Exception("Collection do not contains valid elements");

        return $this[$this->count() - 1];
    }

    /**
     * Retourne le dernier élément selon les conditions where définie
     */
    /**
     * @suppress PHP6601
     */
    public function LastWhere(string $predica, mixed $value, int $searchMethod, ?callable $closure = null): mixed
    {
        if ($this->IsEmpty())
            /** @noinspection PhpFullyQualifiedNameUsageInspection */
            throw new \Exception("Collection do not contains valid elements");

        return $this->Where($predica, $value, $searchMethod, $closure)->Last();
    }

    /**
     * Retourne le dernier élément selon les conditions where définie ou Null ou l'élément par défaut
     */
    /**
     * @suppress PHP6601
     */
    public function LastWhereOrDefault(string $predica, mixed $value, int $searchMethod, ?callable $closure = null): mixed
    {
        if ($this->IsEmpty())
            /** @noinspection PhpFullyQualifiedNameUsageInspection */
            throw new \Exception("Collection do not contains valid elements");

        return $this->Where($predica, $value, $searchMethod, $closure)->LastOrDefault();
    }

    /**
     * Retourne le 1er élément, Null ou la valeur par défaut définie
     */
    public function LastOrDefault(): mixed
    {
        return isset($this[$this->count() - 1])
            ? $this[$this->count() - 1]
            : (($this->defaultValue == null)
                ? null
                : $this->defaultValue);
    }

    /**
     * Défini l'objet retourné par défaut en cas de null
     */
    public function SetDefault(mixed $value): self
    {
        $this->defaultValue = $value;
        return clone $this;
    }

    /**
     * Retourne une liste d'éléments en fonction de la valeur recherchée
     */
    public function Take(int $start, int $end): self
    {
        if (($end < $start)
            || ($this->offsetExists($end))
            || ($this->offsetExists($start))
        )
            throw new ArgumentException("Start or End index are incorrects", CCollection_ErrorCodes::IncorrectIndex);

        if ($this->IsEmpty())
            throw new ArgumentException("no item in the collection", CCollection_ErrorCodes::EmptyCollection);

        if (($start >= ($this->IsEmpty() - 1))
            || ($end >= ($this->IsEmpty() - 1))
        )
            throw new ArgumentException("Start or End index are greater than element count in the collection", CCollection_ErrorCodes::IndexOversize);

        $localArray = new self();

        $localArray->AddRange(
            array_slice($this->ToArray(), $start, $end - $start)
        );

        return $localArray;
    }

    /**
     * Join 2 collections
     */
    public function Join(LinqIterator $iteration): self
    {
        if (isset($collection)) throw new ArgumentNullException("argument is null", CCollection_ErrorCodes::Null);

        $newCollection = clone $this;
        $newCollection->AddRange($iteration->ToArray());

        return $newCollection;
    }

    /**
     * Récupère un élément parmis les éléments
     */
    private function getValue(mixed $element, string $src)
    {
        $elements = explode("->", $src);
        $output = null;

        if (count($elements) > 1) {
            $elementName = $elements[0];
            return $this->getValue(
                $element->$elementName,
                implode("->", array_slice($elements, 1))
            );
        }

        $output = $element->$src;
        return $output;
    }

    /**
     * Vérifie la validité du prédica
     */
    private function checkPredica(
        string $predica,
        mixed $value,
        int $searchMethod
    ): bool {
        $minSrcMethodValue = Operators::Not;
        $maxSrcMethodValue = Operators::And | Operators::Equals | Operators::GreaterThan
            | Operators::Like | Operators::Or | Operators::SmallerThan;

        if (
            isset($predica)
            || isset($value)
            || ($searchMethod >= $minSrcMethodValue)
            || ($searchMethod <= $maxSrcMethodValue)
        ) throw new ArgumentNullException("One or more arguments is null or overflow", CCollection_ErrorCodes::Null);

        return true;
    }

    /**
     * Retourne une collection d'élément selon le predica et éventuellement la closure
     */
    public function Where(
        string $predica,
        mixed $value,
        int $searchMethod,
        ?callable $closure = null
    ): self {
        $this->checkPredica($predica, $value, $searchMethod);

        if ($this->IsEmpty())
            throw new ArgumentException("no item in the collection", CCollection_ErrorCodes::EmptyCollection);

        $items = array();

        foreach ($this as $item) {
            $currentValue = ($closure == null
                ? $this->getValue($item, $predica)
                : $closure($this->getValue($item, $predica)));

            switch ($searchMethod) {
                default:
                case Operators::Exist:
                    if ($currentValue != null)
                        array_push($items, $item);
                    break;
                case Operators::Not | Operators::Exist:
                    if ($currentValue == null)
                        array_push($items, $item);
                    break;
                case Operators::Equals:
                    if ($currentValue == $value)
                        array_push($items, $item);
                    break;
                case Operators::Not | Operators::Equals:
                    if ($currentValue != $value)
                        array_push($items, $item);
                    break;
                case Operators::GreaterThan:
                    if ($currentValue > $value)
                        array_push($items, $item);
                    break;
                case Operators::GreaterThan | Operators::Equals:
                    if ($currentValue >= $value)
                        array_push($items, $item);
                    break;
                case Operators::SmallerThan:
                    if ($currentValue < $value)
                        array_push($items, $item);
                    break;
                case Operators::SmallerThan | Operators::Equals:
                    if ($currentValue <= $value)
                        array_push($items, $item);
                    break;
                case Operators::Like:
                    if ($currentValue == $value)
                        array_push($items, $item);
                    break;
                case Operators::Not | Operators::Like:
                    if ($currentValue != $value)
                        array_push($items, $item);
                    break;
                case Operators::And:
                    if (($currentValue && $value) != null)
                        array_push($items, $item);
                    break;
                case Operators::Or:
                    if (($currentValue || $value) != null)
                        array_push($items, $item);
                    break;
            }
        }

        return new LinqIterator($items);
    }

    /**
     * Retourne une collection d'élément sauf ceux défini dans les conditions
     */
    public function Except(
        string $predica,
        mixed $value,
        int $searchMethod,
        ?callable $closure = null
    ): self {
        $this->checkPredica($predica, $value, $searchMethod);

        if ($this->IsEmpty())
            throw new ArgumentException("no item in the collection", CCollection_ErrorCodes::EmptyCollection);

        $items = array();

        foreach ($this as $item) {
            $currentValue = ($closure == null
                ? $this->getValue($item, $predica)
                : $closure($this->getValue($item, $predica)));

            switch ($searchMethod) {
                default:
                case Operators::Exist:
                    if ($currentValue == null)
                        array_push($items, $item);
                    break;
                case Operators::Not | Operators::Exist:
                    if ($currentValue != null)
                        array_push($items, $item);
                    break;
                case Operators::Equals:
                    if ($currentValue != $value)
                        array_push($items, $item);
                    break;
                case Operators::Not | Operators::Equals:
                    if ($currentValue == $value)
                        array_push($items, $item);
                    break;
                case Operators::GreaterThan:
                    if ($currentValue <= $value)
                        array_push($items, $item);
                    break;
                case Operators::GreaterThan | Operators::Equals:
                    if ($currentValue < $value)
                        array_push($items, $item);
                    break;
                case Operators::SmallerThan:
                    if ($currentValue >= $value)
                        array_push($items, $item);
                    break;
                case Operators::SmallerThan | Operators::Equals:
                    if ($currentValue > $value)
                        array_push($items, $item);
                    break;
                case Operators::Like:
                    if ($currentValue != $value)
                        array_push($items, $item);
                    break;
                case Operators::Not | Operators::Like:
                    if ($currentValue == $value)
                        array_push($items, $item);
                    break;
                case Operators::And:
                    if (($currentValue || $value) != null)
                        array_push($items, $item);
                    break;
                case Operators::Or:
                    if (($currentValue && $value) != null)
                        array_push($items, $item);
                    break;
            }
        }

        return new LinqIterator($items);
    }

    /**
     * Retourne un booléen indiquant s'il existe des éléments non null selon la sélection
     */
    function Any(string $predica): bool
    {
        if ($predica == null) throw new ArgumentNullException("Argument is null", CCollection_ErrorCodes::Null);
        if ($this->IsEmpty()) return false;

        foreach ($this as $item) {
            if ($this->getValue($item, $predica) != null)
                return true;
        }

        return false;
    }

    /**
     * Retourne des collections groupée par le prédica
     */
    function GroupBy(string $predica, ?callable $closure = null): self
    {
        $arrayGroup = array();
        $fctAddCollection = function ($index) use (&$arrayGroup) {
            $nCollection = new self(
                [$this[$index]],
                $this->getFlags(),
                $this->objectType
            );

            array_push($arrayGroup, $nCollection);
        };

        for ($index = 0; $index < $this->count(); $index++) {
            if (count($arrayGroup) > 0) {
                foreach ($arrayGroup as $collection) {
                    $sourceValue = ($closure == null
                        ? $collection->First()->$predica
                        : $closure($collection->First()->$predica));

                    $targetValue = ($closure == null
                        ? $this[$index]->$predica
                        : $closure($this[$index]->$predica));

                    if ($sourceValue == $targetValue) {
                        array_push($collection, $this[$index]);
                    } else {
                        $fctAddCollection($index);
                    }
                }
            } else {
                $fctAddCollection($index);
            }
        }

        return new self($arrayGroup);
    }

    /**
     * Retourne la plus petite valeur selon le prédica
     */
    function Min(string $predica, ?callable $closure = null): mixed
    {
        if ($predica == null) throw new ArgumentNullException("Argument is null", CCollection_ErrorCodes::Null);
        if ($this->IsEmpty()) return 0;

        $finalValue = ($closure == null
            ? $this->getValue($this[0], $predica)
            : $closure($this->getValue($this[0], $predica)));

        if (!is_numeric($finalValue))
            throw new Exception("Value is not a numeric value", CCollection_ErrorCodes::TypeDismatch);

        for ($index = 1; $index < $this->count(); $index++) {
            $currentValue = ($closure == null
                ? $this->getValue($this[$index], $predica)
                : $closure($this->getValue($this[$index], $predica)));

            if (!is_numeric($currentValue))
                throw new Exception("Value is not a numeric value", CCollection_ErrorCodes::TypeDismatch);

            if ($currentValue < $finalValue)
                $finalValue = $currentValue;
        }

        return $finalValue;
    }

    /**
     * Retourne la plus grande valeur selon le prédica
     */
    function Max(string $predica, ?callable $closure = null): mixed
    {
        if ($predica == null) throw new ArgumentNullException("Argument is null", CCollection_ErrorCodes::Null);
        if ($this->IsEmpty()) return 0;

        $finalValue = ($closure == null
            ? $this->getValue($this[0], $predica)
            : $closure($this->getValue($this[0], $predica)));

        if (!is_numeric($finalValue))
            throw new Exception("Value is not a numeric value", CCollection_ErrorCodes::TypeDismatch);

        for ($index = 1; $index < $this->count(); $index++) {
            $currentValue = ($closure == null
                ? $this->getValue($this[$index], $predica)
                : $closure($this->getValue($this[$index], $predica)));

            if (!is_numeric($currentValue))
                throw new Exception("Value is not a numeric value", CCollection_ErrorCodes::TypeDismatch);

            if ($currentValue > $finalValue)
                $finalValue = $currentValue;
        }

        return $finalValue;
    }

    /**
     * Retourne la somme des valeurs selon le prédica
     */
    function Sum(string $predica, ?callable $closure = null): mixed
    {
        if ($predica == null) throw new ArgumentNullException("Argument is null", CCollection_ErrorCodes::Null);
        if ($this->IsEmpty()) return 0;

        $finalValue = ($closure == null
            ? $this->getValue($this[0], $predica)
            : $closure($this->getValue($this[0], $predica)));

        if (!is_numeric($finalValue))
            throw new Exception("Value is not a numeric value", CCollection_ErrorCodes::TypeDismatch);

        for ($index = 1; $index < $this->count(); $index++) {
            $currentValue = ($closure == null
                ? $this->getValue($this[$index], $predica)
                : $closure($this->getValue($this[$index], $predica)));

            if (!is_numeric($currentValue))
                throw new Exception("Value is not a numeric value", CCollection_ErrorCodes::TypeDismatch);

            $finalValue += $currentValue;
        }

        return $finalValue;
    }

    /**
     * Retourne la moyenne des valeurs selon le prédica
     */
    function Avg(string $predica, ?callable $closure = null): mixed
    {
        if ($predica == null) throw new ArgumentNullException("Argument is null", CCollection_ErrorCodes::Null);
        if ($this->IsEmpty()) return 0;

        $arrayValues = array();

        for ($index = 1; $index < $this->count(); $index++) {
            $currentValue = ($closure == null
                ? $this->getValue($this[$index], $predica)
                : $closure($this->getValue($this[$index], $predica)));

            if (!is_numeric($currentValue))
                throw new Exception("Value is not a numeric value", CCollection_ErrorCodes::TypeDismatch);

            array_push($arrayValues, $currentValue);
        }

        return array_sum($arrayValues) / count($arrayValues);
    }

    /**
     * ILinqTyped Part
     */

    /**
     * Retourne le type d'instance attendu
     */
    public function GetInstanceType(): mixed
    {
        return $this->objectType;
    }

    /**
     * Vérifie que le paramètre est du même type que le type attendu
     * Retourne True si le type attendu n'est pas défini
     */
    private function CheckInstanceObject(mixed $obj): bool
    {
        if (!isset($this->objectType))
            return true;

        if (!($obj instanceof $this->objectType))
            throw new ArgumentException("object is not a {$this->objectType} type", CCollection_ErrorCodes::TypeDismatch);

        return true;
    }

    /**
     * IFilter Part
     */

    /**
     * Filtre les éléments par un prédica et une closure soit par clé, soit par valeur
     */
    public function FilterBy(string $predica, callable $closure, int $mode = ARRAY_FILTER_USE_KEY): LinqIterator
    {
        $values = $this->ToArray();

        array_filter(
            $values,
            $closure
        );

        return new self($values);
    }

    /**
     * Supprime les null de la liste
     */
    public function Trim(bool $through = false): void
    {
        $arrTemp = null;

        if ($through) {
            $arrTemp = array_filter(
                $this->ToArray(),
                function ($a) {
                    return $a != null;
                }
            );
        } else {
            $arrTemp = $this->ToArray();
            while (
                !empty($arrTemp)
                && end($arrTemp) === null
            )
                array_pop($arrTemp);
        }

        $this->Clear();
        $this->AddRange($arrTemp);
        unset($arrTemp);
    }
}
