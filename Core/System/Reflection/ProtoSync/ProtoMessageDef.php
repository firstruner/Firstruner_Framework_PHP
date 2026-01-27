<?php

namespace System\Reflection\ProtoSync;

final class ProtoMessageDef
{
    public function __construct(
        public string $name,
        /** @var ProtoFieldDef[] */
        public array $fields = []
    ) {}
}
