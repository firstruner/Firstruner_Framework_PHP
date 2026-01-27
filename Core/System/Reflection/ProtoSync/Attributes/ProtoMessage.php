<?php


namespace System\Reflection\ProtoSync\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class ProtoMessage
{
    public function __construct(
        public string $name = ''
    ) {}
}
