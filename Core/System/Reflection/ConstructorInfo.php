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

// use System\Diagnostics\Debug;
// use System\Globalization\CultureInfo;
// use System\Runtime\CompilerServices\MethodImplOptions;

abstract class ConstructorInfo extends MethodBase
{
    protected function __construct() {}

    public function getMemberType(): int
    {
        return MemberTypes::Constructor;
    }

    // #[Debug\DebuggerHidden]
    // #[Debug\DebuggerStepThrough]
    // public function invoke(?array $parameters = null): object
    // {
    //return $this->invokeWithFlags(BindingFlags::Default, null, $parameters, null);
    // }

    // abstract public function invokeWithFlags(int $invokeAttr, ?Binder $binder, ?array $parameters, ?CultureInfo $culture): object;

    public function equals($obj): bool
    {
        return parent::equals($obj);
    }

    public function getHashCode(): int
    {
        return parent::getHashCode();
    }

    // #[MethodImpl(MethodImplOptions::AggressiveInlining)]
    public static function op_Equality(?ConstructorInfo $left, ?ConstructorInfo $right): bool
    {
        if ($right === null) {
            return $left === null;
        }

        if ($left === $right) {
            return true;
        }

        return ($left === null) ? false : $left->equals($right);
    }

    public static function op_Inequality(?ConstructorInfo $left, ?ConstructorInfo $right): bool
    {
        return !self::op_Equality($left, $right);
    }

    public static string $ConstructorName = ".ctor";
    public static string $TypeConstructorName = ".cctor";
}
