<?php

namespace System\Reflection\ProtoSync;

final class ProtoFile
{
    public string $package = '';
    public string $csharpNamespace = '';
    /** @var string[] */
    public array $imports = [];
    /** @var ProtoMessageDef[] */
    public array $messages = [];
}
