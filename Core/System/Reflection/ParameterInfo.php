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

// use System\Collections\Generic\IEnumerable;
// use System\Collections\Generic\IList;
//use System\ComponentModel\EditorBrowsableAttribute;

use Obselete;
use System\ComponentModel\EditorBrowsableState;
use System\Exceptions\ArgumentNullException;
use System\Exceptions\NotSupportedException;
use System\Exceptions\SerializationException;
//use System\Runtime\Serialization\ISerializable;
use System\Type;

class ParameterInfo implements ICustomAttributeProvider //, ISerializable
{
    protected function __construct() {}

    public function getAttributes(): int
    {
        return $this->AttrsImpl;
    }

    public function getMember(): MemberInfo
    {
        return $this->MemberImpl;
    }

    public function getName(): ?string
    {
        return $this->NameImpl;
    }

    public function getParameterType(): Type
    {
        return $this->ClassImpl;
    }

    public function getPosition(): int
    {
        return $this->PositionImpl;
    }

    public function isIn(): bool
    {
        return ($this->getAttributes() & ParameterAttributes::In) !== 0;
    }

    public function isLcid(): bool
    {
        return ($this->getAttributes() & ParameterAttributes::Lcid) !== 0;
    }

    public function isOptional(): bool
    {
        return ($this->getAttributes() & ParameterAttributes::Optional) !== 0;
    }

    public function isOut(): bool
    {
        return ($this->getAttributes() & ParameterAttributes::Out) !== 0;
    }

    public function isRetval(): bool
    {
        return ($this->getAttributes() & ParameterAttributes::Retval) !== 0;
    }

    public function getDefaultValue()
    {
        throw new \Exception("Not implemented by design");
    }

    public function getRawDefaultValue()
    {
        throw new \Exception("Not implemented by design");
    }

    public function hasDefaultValue(): bool
    {
        throw new \Exception("Not implemented by design");
    }

    public function isDefined(string $attributeType, bool $inherit): bool
    {
        if ($attributeType === null) throw new ArgumentNullException("attributeType");

        return false;
    }

    //     public function getCustomAttributes(): IEnumerable
    //     {
    //         return $this->getCustomAttributesData();
    //     }

    public function getCustomAttributesData(): array
    {
        throw new \Exception("Not implemented by design");
    }

    public function getCustomAttributes(bool $inherit): array
    {
        return [];
    }

    public function getCustomAttributesByType(string $attributeType, bool $inherit): array
    {
        if ($attributeType === null) {
            throw new ArgumentNullException("attributeType");
        }
        return [];
    }

    public function getModifiedParameterType(): Type
    {
        throw new NotSupportedException();
    }

    public function getOptionalCustomModifiers(): array
    {
        return Type::EmptyTypes;
    }

    public function getRequiredCustomModifiers(): array
    {
        return Type::EmptyTypes;
    }

    public function getMetadataToken(): int
    {
        return self::MetadataToken_ParamDef;
    }

    #[Obselete("Legacy formatter implementation is obsolete")]
    // #[EditorBrowsable(EditorBrowsableState::Never)]
    public function getRealObject($context)
    {
        if ($this->MemberImpl === null) throw new SerializationException("Serialization_InsufficientState");

        $args = [];
        switch ($this->MemberImpl->getMemberType()) {
            case MemberTypes::Constructor:
            case MemberTypes::Method:
                if ($this->PositionImpl === -1) {
                    if ($this->MemberImpl->getMemberType() === MemberTypes::Method) {
                        return $this->MemberImpl->getReturnParameter();
                    } else {
                        throw new SerializationException("Serialization_BadParameterInfo");
                    }
                } else {
                    $args = $this->MemberImpl->getParametersNoCopy();

                    if ($args !== null && $this->PositionImpl < count($args)) {
                        return $args[$this->PositionImpl];
                    } else {
                        throw new SerializationException("Serialization_BadParameterInfo");
                    }
                }

            case MemberTypes::Property:
                $args = $this->MemberImpl->getIndexParameters();

                if ($args !== null && $this->PositionImpl > -1 && $this->PositionImpl < count($args)) {
                    return $args[$this->PositionImpl];
                } else {
                    throw new SerializationException("Serialization_BadParameterInfo");
                }

            default:
                throw new SerializationException("Serialization_NoParameterInfo");
        }
    }

    public function __toString(): string
    {
        $typeName = gettype($this->getParameterType());
        $name = $this->getName();
        return $name === null ? $typeName : $typeName . " " . $name;
    }

    protected $AttrsImpl;
    protected $ClassImpl;
    protected $DefaultValueImpl;
    protected $MemberImpl;
    protected $NameImpl;
    protected $PositionImpl;

    private const MetadataToken_ParamDef = 0x08000000;
}
