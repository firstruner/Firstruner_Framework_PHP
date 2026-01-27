<?php

namespace System\Reflection\ProtoSync\Converters;

use System\Reflection\ProtoSync\ProtoParser;
use System\Reflection\ProtoSync\Naming;
//use System\Reflection\ProtoSync\Cli;
use System\Default\_string;
use System\Reflection\ProtoSync\ProtoConverter;

final class Proto2PHP implements ProtoConverter
{
    public static function Convert(
        string $src,
        string $out,
        ?string $package = null,
        string $namespace = _string::EmptyString
    ) {
        // $args = Cli::parseArgs($argv);
        // $protoFile = Cli::must($args, 'proto');
        // $outDir = Cli::must($args, 'out');
        // $namespace = $args['namespace'] ?? '';
        $protoFile = $src;
        $outDir = $out;

        if (!file_exists($protoFile)) {
            fwrite(STDERR, "proto not found: {$protoFile}\n");
            exit(2);
        }

        $txt = file_get_contents($protoFile);
        $pf = ProtoParser::parse($txt ?: '');

        @mkdir($outDir, 0777, true);

        function protoTypeToPhp(string $t): string
        {
            return match ($t) {
                'string' => 'string',
                'bool' => 'bool',
                'int32', 'sint32', 'sfixed32', 'uint32', 'fixed32' => 'int',
                'int64', 'sint64', 'sfixed64', 'uint64', 'fixed64' => 'int',
                'float', 'double' => 'float',
                'google.protobuf.Timestamp' => '\\DateTimeImmutable',
                default => 'mixed', // message types not expanded by this simple stub generator
            };
        }

        foreach ($pf->messages as $m) {
            $className = $m->name;
            $path = rtrim($outDir, '/\\') . DIRECTORY_SEPARATOR . $className . '.php';

            $lines = [];
            $lines[] = '<?php';
            $lines[] = '';
            $lines[] = '';
            if ($namespace !== '') {
                $lines[] = 'namespace ' . $namespace . ';';
                $lines[] = '';
            }
            $lines[] = '/**';
            $lines[] = ' * AUTO-GENERATED from ' . basename($protoFile);
            $lines[] = ' * Do not edit by hand. Regenerate instead.';
            $lines[] = ' */';
            $lines[] = 'final class ' . $className;
            $lines[] = '{';

            foreach ($m->fields as $f) {
                $phpType = protoTypeToPhp($f->type);
                $propName = Naming::toPascal($f->name);
                $nullable = $f->optional ? '?' : '';
                if ($f->repeated) {
                    $lines[] = '    /** @var ' . ($phpType === 'mixed' ? 'array' : ($phpType . '[]')) . ' */';
                    $lines[] = '    public array $' . $propName . ' = [];';
                    $lines[] = '';
                } else {
                    if ($phpType === '\\DateTimeImmutable') {
                        $lines[] = '    public ' . $nullable . '\\DateTimeImmutable $' . $propName . ';';
                    } else {
                        $lines[] = '    public ' . $nullable . $phpType . ' $' . $propName . ';';
                    }
                    $lines[] = '';
                }
            }

            $lines[] = '}';
            $lines[] = '';

            file_put_contents($path, implode(PHP_EOL, $lines));
            fwrite(STDOUT, "Wrote PHP stub: {$path}\n");
        }
    }
}