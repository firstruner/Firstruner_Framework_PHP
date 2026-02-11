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



/** 
 * -- File description --
 * @Type : MethodClass
 * @Mode : XP/BDD Creation
 * @Author : Christophe BOULAS
 * @Update on : 21/01/2026 by : Christophe BOULAS
 */

namespace System\Collections;

/**
 * @psalm-suppress MissingTemplateParam
 */
final class CaseInsensitiveArray implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var mixed[] Data storage with lowercase keys.
     *
     * @see offsetSet()
     * @see offsetExists()
     * @see offsetUnset()
     * @see offsetGet()
     * @see count()
     * @see current()
     * @see next()
     * @see key()
     */
    private $data = [];

    /**
     * @var string[] Case-sensitive keys.
     *
     * @see offsetSet()
     * @see offsetUnset()
     * @see key()
     */
    private $keys = [];

    /**
     * Construct
     *
     * Allow creating an empty array or converting an existing array to a
     * case-insensitive array. Caution: Data may be lost when converting
     * case-sensitive arrays to case-insensitive arrays.
     *
     * @param  mixed[]              $initial (optional) Existing array to convert.
     * @return CaseInsensitiveArray
     */
    public function __construct(?array $initial = null)
    {
        if ($initial !== null) {
            foreach ($initial as $key => $value) {
                $this->offsetSet($key, $value);
            }
        }
    }

    /**
     * Offset Set
     *
     * Set data at a specified offset. Converts the offset to lowercase, and
     * stores the case-sensitive offset and the data at the lowercase indexes in
     * $this->keys and @this->data.
     *
     * @param  string $offset The offset to store the data at (case-insensitive).
     * @param  mixed  $value  The data to store at the specified offset.
     * @return void
     * @see https://secure.php.net/manual/en/arrayaccess.offsetset.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function OffsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $offsetlower = strtolower($offset);
            $this->data[$offsetlower] = $value;
            $this->keys[$offsetlower] = $offset;
        }
    }

    /**
     * Offset Exists
     *
     * Checks if the offset exists in data storage. The index is looked up with
     * the lowercase version of the provided offset.
     *
     * @param  string $offset Offset to check
     * @return bool   If the offset exists.
     * @see https://secure.php.net/manual/en/arrayaccess.offsetexists.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function OffsetExists($offset)
    {
        return array_key_exists(strtolower($offset), $this->data);
    }

    /**
     * Offset Unset
     *
     * Unsets the specified offset. Converts the provided offset to lowercase,
     * and unsets the case-sensitive key, as well as the stored data.
     *
     * @param  string $offset The offset to unset.
     * @return void
     * @see https://secure.php.net/manual/en/arrayaccess.offsetunset.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function OffsetUnset($offset)
    {
        $offsetlower = strtolower($offset);
        unset($this->data[$offsetlower]);
        unset($this->keys[$offsetlower]);
    }

    /**
     * Offset Get
     *
     * Return the stored data at the provided offset. The offset is converted to
     * lowercase and the lookup is done on the data store directly.
     *
     * @param  string $offset Offset to lookup.
     * @return mixed  The data stored at the offset.
     * @see https://secure.php.net/manual/en/arrayaccess.offsetget.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function OffsetGet($offset)
    {
        $offsetlower = strtolower($offset);
        return $this->data[$offsetlower] ?? null;
    }

    /**
     * Count
     *
     * @return int The number of elements stored in the array.
     * @see https://secure.php.net/manual/en/countable.count.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function Count()
    {
        return count($this->data);
    }

    /**
     * Current
     *
     * @return mixed Data at the current position.
     * @see https://secure.php.net/manual/en/iterator.current.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function Current()
    {
        return current($this->data);
    }

    /**
     * Next
     *
     * @return void
     * @see https://secure.php.net/manual/en/iterator.next.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function Next()
    {
        next($this->data);
    }

    /**
     * Key
     *
     * @return mixed Case-sensitive key at current position.
     * @see https://secure.php.net/manual/en/iterator.key.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function Key()
    {
        $key = key($this->data);
        return $this->keys[$key] ?? $key;
    }

    /**
     * Valid
     *
     * @return bool If the current position is valid.
     * @see https://secure.php.net/manual/en/iterator.valid.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function Valid()
    {
        return (key($this->data) !== null);
    }

    /**
     * Rewind
     *
     * @return void
     * @see https://secure.php.net/manual/en/iterator.rewind.php
     */
    #[\Override]
    #[\ReturnTypeWillChange]
    public function Rewind()
    {
        reset($this->data);
    }
}
