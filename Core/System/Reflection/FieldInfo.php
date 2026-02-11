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

use System\Diagnostics\Debug;
use System\Exceptions\NotImplementedException;
use System\Exceptions\NotSupportedException;
use System\Globalization\CultureInfo;
use System\Runtime\CompilerServices\MethodImplOptions;
use System\Type;

abstract class FieldInfo extends MemberInfo
{
    public $value;

    protected function __construct() {}

    public function getMemberType()
    {
        return MemberTypes::Field;
    }

    abstract public function getAttributes();
    abstract public function getFieldType();

    public function isInitOnly()
    {
        return ($this->getAttributes() & FieldAttributes::InitOnly) != 0;
    }

    public function isLiteral()
    {
        return ($this->getAttributes() & FieldAttributes::Literal) != 0;
    }

    public function isPinvokeImpl()
    {
        return ($this->getAttributes() & FieldAttributes::PinvokeImpl) != 0;
    }

    public function isSpecialName()
    {
        return ($this->getAttributes() & FieldAttributes::SpecialName) != 0;
    }

    public function isStatic()
    {
        return ($this->getAttributes() & FieldAttributes::Static) != 0;
    }

    public function isAssembly()
    {
        return ($this->getAttributes() & FieldAttributes::FieldAccessMask) == FieldAttributes::Assembly;
    }

    public function isFamily()
    {
        return ($this->getAttributes() & FieldAttributes::FieldAccessMask) == FieldAttributes::Family;
    }

    public function isFamilyAndAssembly()
    {
        return ($this->getAttributes() & FieldAttributes::FieldAccessMask) == FieldAttributes::FamANDAssem;
    }

    public function isFamilyOrAssembly()
    {
        return ($this->getAttributes() & FieldAttributes::FieldAccessMask) == FieldAttributes::FamORAssem;
    }

    public function isPrivate()
    {
        return ($this->getAttributes() & FieldAttributes::FieldAccessMask) == FieldAttributes::Private;
    }

    public function isPublic()
    {
        return ($this->getAttributes() & FieldAttributes::FieldAccessMask) == FieldAttributes::Public;
    }

    public function isSecurityCritical()
    {
        return true;
    }

    public function isSecuritySafeCritical()
    {
        return false;
    }

    public function isSecurityTransparent()
    {
        return false;
    }

    abstract public function getFieldHandle();

    public function equals($obj)
    {
        return parent::equals($obj);
    }

    public function getHashCode()
    {
        return parent::getHashCode();
    }

    public static function op_Equality($left, $right)
    {
        if ($right === null) {
            return $left === null;
        }

        if ($left === $right) {
            return true;
        }

        return ($left === null) ? false : $left->equals($right);
    }

    public static function op_Inequality($left, $right)
    {
        return !self::op_Equality($left, $right);
    }

    abstract public function getValue($obj);

    public function setValue($obj, $value)
    {
        $this->value = $value;
    }

    //abstract public function setValue($obj, $value, $invokeAttr, $binder, $culture);

    public function setValueDirect($obj, $value)
    {
        throw new NotSupportedException("NotSupported_AbstractNonCLS");
    }

    public function getValueDirect($obj)
    {
        throw new NotSupportedException("NotSupported_AbstractNonCLS");
    }

    public function getRawConstantValue()
    {
        throw new NotSupportedException("NotSupported_AbstractNonCLS");
    }

    public function getModifiedFieldType()
    {
        throw new NotSupportedException();
    }

    public function getOptionalCustomModifiers()
    {
        throw new NotImplementedException("ByDesign");
    }

    public function getRequiredCustomModifiers()
    {
        throw new NotImplementedException("ByDesign");
    }
}
