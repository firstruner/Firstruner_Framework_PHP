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

abstract class PropertyInfo extends MemberInfo
{
    protected function __construct() {}

    public function getMemberType()
    {
        return MemberTypes::Property;
    }

    abstract public function getPropertyType();
    abstract public function getIndexParameters();

    abstract public function getAttributes();
    public function isSpecialName()
    {
        return ($this->getAttributes() & PropertyAttributes::SpecialName) !== 0;
    }

    abstract public function canRead();
    abstract public function canWrite();

    public function getAccessors()
    {
        return $this->getAccessors(false);
    }
    //abstract public function getAccessors($nonPublic);

    public function getMethod()
    {
        return $this->getGetMethod(true);
    }
    public function getGetMethod()
    {
        return $this->getGetMethod(false);
    }
    //abstract public function getGetMethod($nonPublic);

    public function setMethod()
    {
        return $this->getSetMethod(true);
    }
    public function getSetMethod()
    {
        return $this->getSetMethod(false);
    }
    //abstract public function getSetMethod($nonPublic);

    public function getModifiedPropertyType()
    {
        throw new \Exception("NotSupportedException");
    }
    public function getOptionalCustomModifiers()
    {
        return [];
    }
    public function getRequiredCustomModifiers()
    {
        return [];
    }

    public function getValue($obj = null)
    {
        return $this->getValue($obj, null);
    }
    public function getValueWithIndex($obj = null, $index = null)
    {
        return $this->getValue($obj, BindingFlags::Default, null, $index, null);
    }
    //abstract public function getValue($obj, $invokeAttr, $binder, $index, $culture);

    public function getConstantValue()
    {
        throw new \Exception("NotImplementedException");
    }
    public function getRawConstantValue()
    {
        throw new \Exception("NotImplementedException");
    }

    public function setValue($obj = null, $value = null)
    {
        $this->setValue($obj, $value, null);
    }
    public function setValueWithIndex($obj = null, $value = null, $index = null)
    {
        $this->setValue($obj, $value, BindingFlags::Default, null, $index, null);
    }
    //abstract public function setValue($obj, $value, $invokeAttr, $binder, $index, $culture);

    public function equals($obj)
    {
        return parent::equals($obj);
    }
    public function getHashCode()
    {
        return parent::getHashCode();
    }

    public static function equalsOperator($left, $right)
    {
        if ($right === null) {
            return $left === null;
        }

        if ($left === $right) {
            return true;
        }

        return ($left === null) ? false : $left->equals($right);
    }

    public static function notEqualsOperator($left, $right)
    {
        return !self::equalsOperator($left, $right);
    }
}
