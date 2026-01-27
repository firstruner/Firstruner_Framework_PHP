<?php

namespace System\Reflection\ProtoSync;

use System\Default\_string;

interface ProtoConverter
{
      public static function Convert(
            string $src,
            string $out,
            string $package = 'demo.contracts',
            string $namespace = _string::EmptyString);
}