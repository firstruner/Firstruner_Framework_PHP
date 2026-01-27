<?php


namespace System\Reflection\ProtoSync\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ProtoField
{
    public function __construct(
        public int $tag,
        public string $type,           // proto scalar type or 'timestamp'
        public bool $optional = false,
        public bool $repeated = false,
        public string $name = ''       // override field name in proto
    ) {}
}
