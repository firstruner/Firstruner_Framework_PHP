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

namespace System\Reflection;

use System\_Object;
use System\Type;

abstract class MemberInfo implements ICustomAttributeProvider
{
    protected function __construct() {}

    abstract public function getMemberType();
    abstract public function getName();
    abstract public function getDeclaringType();
    abstract public function getReflectedType();

    public function getModule()
    {
        // This check is necessary because for some reason, Type adds a new "Module" property that hides the inherited one instead
        // of overriding.

        if ($this instanceof Type) {
            if (method_exists($this, 'getModuleInternal')) {
                return $this->getModule();
            }

            throw new \Exception("Type does not implement getModuleInternal()");
        }

        throw new \Exception("NotImplemented.ByDesign");
    }

    public function hasSameMetadataDefinitionAs(MemberInfo $other)
    {
        throw new \Exception("NotImplemented.ByDesign");
    }

    abstract public function isDefined($attributeType, $inherit): bool;
    abstract public function getCustomAttributes($inherit): array;
    abstract public function getCustomAttributesByType($attributeType, $inherit): array;

    public function getCustomAttributesData()
    {
        throw new \Exception("NotImplemented.ByDesign");
    }

    public function isCollectible()
    {
        return true;
    }
    public function getMetadataToken()
    {
        throw new \Exception("InvalidOperationException");
    }

    public function equals($obj)
    {
        return $obj == $this;
    }
    public function getHashCode()
    {
        return _Object::getObjectHashCode($this);
    }

    public static function equalsOperator($left, $right)
    {
        // Test "right" first to allow branch elimination when inlined for null checks (== null)
        // so it can become a simple test
        if ($right === null) {
            return $left === null;
        }

        // Try fast reference equality and opposite null check prior to calling the slower virtual equals
        if ($left === $right) {
            return true;
        }

        return ($left === null) ? false : $left->equals($right);
    }

    public static function notEqualsOperator($left, $right)
    {
        return !(self::equalsOperator($left, $right));
    }
}
