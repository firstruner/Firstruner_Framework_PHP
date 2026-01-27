<?php

namespace System\Reflection\ProtoSync;

final class ProtoFieldDef
{
    public function __construct(
        public int $tag,
        public string $type,
        public string $name,
        public bool $optional = false,
        public bool $repeated = false
    ) {}
}
