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

use MethodBase;
use System\Annotations\NotImplemented;
use System\Diagnostics\CodeAnalysis\RequiresDynamicCodeAttribute;
use System\Diagnostics\CodeAnalysis\RequiresUnreferencedCodeAttribute;
use System\Exceptions\NotSupportedException;
use System\Runtime\CompilerServices\MethodImplAttribute;
use System\Runtime\CompilerServices\MethodImplOptions;

abstract class MethodInfo extends MethodBase
{
    protected function __construct() {}

    public function getMemberType(): int
    {
        return MemberTypes::Method;
    }

    public function getReturnParameter(): ParameterInfo
    {
        throw new \Exception(NotImplemented::ByDesign());
    }

    public function getReturnType(): \ReflectionType
    {
        throw new \Exception(NotImplemented::ByDesign());
    }

    // public function getGenericArguments(): array
    // {
    //     throw new NotSupportedException(SR::NotSupported_SubclassOverride);
    // }

    // public function getGenericMethodDefinition(): MethodInfo
    // {
    //     throw new NotSupportedException(SR::NotSupported_SubclassOverride);
    // }

    // #[RequiresDynamicCode("The native code for this instantiation might not be available at runtime.")]
    // #[RequiresUnreferencedCode("If some of the generic arguments are annotated (either with DynamicallyAccessedMembersAttribute, or generic constraints), trimming can't validate that the requirements of those annotations are met.")]
    // public function makeGenericMethod(Type ...$typeArguments): MethodInfo
    // {
    //     throw new \NotSupportedException(SR::NotSupported_SubclassOverride);
    // }

    abstract public function getBaseDefinition(): MethodInfo;

    abstract public function getReturnTypeCustomAttributes(): ICustomAttributeProvider;

    // public function createDelegate(string $delegateType)
    // {
    //     throw new \NotSupportedException(SR::NotSupported_SubclassOverride);
    // }

    // public function createDelegateWithTarget(string $delegateType, ?object $target)
    // {
    //     throw new \NotSupportedException(SR::NotSupported_SubclassOverride);
    // }

    // public function createDelegateGeneric(string $delegateType)
    // {
    //     return $this->createDelegate($delegateType);
    // }

    // public function createDelegateGenericWithTarget(string $delegateType, ?object $target)
    // {
    //     return $this->createDelegateWithTarget($delegateType, $target);
    // }

    public function equals($obj): bool
    {
        return parent::equals($obj);
    }

    public function getHashCode(): int
    {
        return parent::getHashCode();
    }

    // #[MethodImpl(MethodImplOptions::AggressiveInlining)]
    // public static function op_Equality(?MethodInfo $left, ?MethodInfo $right): bool
    // {
    //     if ($right === null) {
    //         return $left === null;
    //     }

    //     if ($left === $right) {
    //         return true;
    //     }

    //     return $left === null ? false : $left->equals($right);
    // }

    // public static function op_Inequality(?MethodInfo $left, ?MethodInfo $right): bool
    // {
    //     return !self::op_Equality($left, $right);
    // }
}
