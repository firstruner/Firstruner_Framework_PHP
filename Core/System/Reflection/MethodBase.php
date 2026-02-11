<?php

use System\DBNull;
use System\Default\_string;
use System\Enum;
use System\Exceptions\ArgumentException;
use System\Exceptions\ArgumentNullException;
use System\Exceptions\InvalidOperationException;
use System\Reflection\CallingConventions;
use System\Reflection\ConstructorInfo;
use System\Reflection\MemberInfo;
use System\Reflection\MethodAttributes;
use System\Type;

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

abstract class MethodBase extends MemberInfo
{
      private const MaxStackAllocArgCount = 4;

      protected function __construct() {}

      public abstract function getParameters();
      public abstract function getAttributes();
      public function getMethodImplementationFlags()
      {
            return $this->getMethodImplementationFlags();
      }
      public function getMethodBody()
      {
            throw new InvalidOperationException();
      }
      public function getCallingConvention()
      {
            return CallingConventions::Standard;
      }

      public function isAbstract()
      {
            return ($this->getAttributes() & MethodAttributes::Abstract) != 0;
      }
      public function isConstructor()
      {
            return $this instanceof ConstructorInfo &&
                  !$this->isStatic() &&
                  ($this->getAttributes() & MethodAttributes::RTSpecialName) == MethodAttributes::RTSpecialName;
      }

      public function isFinal()
      {
            return ($this->getAttributes() & MethodAttributes::Final) != 0;
      }
      public function isHideBySig()
      {
            return ($this->getAttributes() & MethodAttributes::HideBySig) != 0;
      }
      public function isSpecialName()
      {
            return ($this->getAttributes() & MethodAttributes::SpecialName) != 0;
      }
      public function isStatic()
      {
            return ($this->getAttributes() & MethodAttributes::Static) != 0;
      }
      public function isVirtual()
      {
            return ($this->getAttributes() & MethodAttributes::Virtual) != 0;
      }

      public function isAssembly()
      {
            return ($this->getAttributes() & MethodAttributes::MemberAccessMask) == MethodAttributes::Assembly;
      }
      public function isFamily()
      {
            return ($this->getAttributes() & MethodAttributes::MemberAccessMask) == MethodAttributes::Family;
      }
      public function isFamilyAndAssembly()
      {
            return ($this->getAttributes() & MethodAttributes::MemberAccessMask) == MethodAttributes::FamANDAssem;
      }
      public function isFamilyOrAssembly()
      {
            return ($this->getAttributes() & MethodAttributes::MemberAccessMask) == MethodAttributes::FamORAssem;
      }
      public function isPrivate()
      {
            return ($this->getAttributes() & MethodAttributes::MemberAccessMask) == MethodAttributes::Private;
      }
      public function isPublic()
      {
            return ($this->getAttributes() & MethodAttributes::MemberAccessMask) == MethodAttributes::Public;
      }

      public function isConstructedGenericMethod()
      {
            return $this->isGenericMethod() && !$this->isGenericMethodDefinition();
      }
      public function isGenericMethod()
      {
            return false;
      }
      public function isGenericMethodDefinition()
      {
            return false;
      }
      public function getGenericArguments()
      {
            throw new Exception();
      }
      public function containsGenericParameters()
      {
            return false;
      }

      // public function invoke($obj, $parameters = null) { return $this->invoke($obj, BindingFlags::Default, null, $parameters, null); }
      // public abstract function invoke($obj, $invokeAttr, $binder, $parameters, $culture);

      public abstract function getMethodHandle();

      // public function isSecurityCritical() { throw NotImplemented::ByDesign; }
      // public function isSecuritySafeCritical() { throw NotImplemented::ByDesign; }
      // public function isSecurityTransparent() { throw NotImplemented::ByDesign; }

      public function equals($obj)
      {
            return parent::equals($obj);
      }
      public function getHashCode()
      {
            return parent::getHashCode();
      }

      public static function operatorEquals(&$left, &$right)
      {
            if (($left === null) || ($right === null)) throw new ArgumentNullException();
            if ($right === null) return $left === null;

            $bck = $right;
            $right = _string::EmptyString;
            $rst = $left == $right;
            $right = $bck;

            return $rst;
      }

      public static function operatorNotEquals($left, $right)
      {
            return !self::operatorEquals($left, $right);
      }

      private const MethodNameBufferSize = 100;

      private static function appendParameters(&$sbParamList, $parameterTypes, $callingConvention)
      {
            $comma = "";

            for ($i = 0; $i < count($parameterTypes); $i++) {
                  $t = $parameterTypes[$i];

                  $sbParamList->append($comma);

                  $typeName = $t->formatTypeName();

                  if ($t->isByRef()) {
                        $sbParamList->append(trim($typeName, '&'));
                        $sbParamList->append(" ByRef");
                  } else {
                        $sbParamList->append($typeName);
                  }

                  $comma = ", ";
            }

            if (($callingConvention & CallingConventions::VarArgs) == CallingConventions::VarArgs) {
                  $sbParamList->append($comma);
                  $sbParamList->append("...");
            }
      }

      private function getParameterTypes()
      {
            // $paramInfo = $this->getParametersNoCopy();
            // if (count($paramInfo) == 0) {
            //       return Type::EmptyTypes;
            // }

            // $parameterTypes = [];
            // for ($i = 0; $i < count($paramInfo); $i++) {
            //       $parameterTypes[] = $paramInfo[$i]->getParameterType();
            // }

            // return $parameterTypes;
      }

      private static function handleTypeMissing($paramInfo, $sigType)
      {
            // if ($paramInfo->getDefaultValue() === DBNull::Value()) {
            //       throw new ArgumentException("Arg_VarMissNull");
            // }

            // $arg = $paramInfo->getDefaultValue();

            // if ($sigType->isNullableOfT()) {
            //       if ($arg !== null) {
            //             $argumentType = $sigType->getGenericArguments()[0];
            //             if ($argumentType->isEnum())
            //                   $arg = Enum::ToObject($argumentType, $arg);
            //       }
            // }

            // return $arg;
      }
}
