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

namespace System\Reflection\ProtoSync\Converters;

use System\Reflection\ProtoSync\ProtoConverter;
use System\Reflection\ProtoSync\ProtoParser;
use System\Reflection\ProtoSync\Naming;
//use System\Reflection\ProtoSync\Cli;
use System\Default\_string;

final class Proto2CS implements ProtoConverter
{
    public static function Convert(
        string $src,
        string $out,
        string $package = 'demo.contracts',
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

        function protoTypeToCs(string $t): string
        {
            return match ($t) {
                'string' => 'string',
                'bool' => 'bool',
                'int32', 'sint32', 'sfixed32', 'uint32', 'fixed32' => 'int',
                'int64', 'sint64', 'sfixed64', 'uint64', 'fixed64' => 'long',
                'float' => 'float',
                'double' => 'double',
                'google.protobuf.Timestamp' => 'Google.Protobuf.WellKnownTypes.Timestamp',
                default => $t, // message types -> assume same type name exists
            };
        }

        foreach ($pf->messages as $m) {
            $className = $m->name;
            $path = rtrim($outDir, '/\\') . DIRECTORY_SEPARATOR . $className . '.cs';

            $lines = [];
            $lines[] = '// AUTO-GENERATED from ' . basename($protoFile);
            $lines[] = '// Do not edit by hand. Regenerate instead.';
            $lines[] = '';
            if ($namespace !== '') {
                $lines[] = 'namespace ' . $namespace;
                $lines[] = '{';
            }

            $indent = $namespace !== '' ? '    ' : '';

            // usings only if needed
            $needsTimestamp = false;
            foreach ($m->fields as $f) {
                if ($f->type === 'google.protobuf.Timestamp') $needsTimestamp = true;
            }
            if ($needsTimestamp) {
                $lines = array_merge(['using Google.Protobuf.WellKnownTypes;', ''], $lines);
            }

            $lines[] = $indent . 'public sealed class ' . $className;
            $lines[] = $indent . '{';

            foreach ($m->fields as $f) {
                $csType = protoTypeToCs($f->type);
                $propName = Naming::toPascal($f->name);

                if ($f->repeated) {
                    $lines[] = $indent . '    public System.Collections.Generic.List<' . $csType . '> ' . $propName . ' { get; set; } = new();';
                } else {
                    $nullable = $f->optional && $csType !== 'string' && $csType !== 'Google.Protobuf.WellKnownTypes.Timestamp' ? '?' : '';
                    // keep string nullable annotation optional; we keep it simple
                    $lines[] = $indent . '    public ' . $csType . $nullable . ' ' . $propName . ' { get; set; }';
                }
            }

            $lines[] = $indent . '}';

            if ($namespace !== '') {
                $lines[] = '}';
            }
            $lines[] = '';

            file_put_contents($path, implode(PHP_EOL, $lines));
            fwrite(STDOUT, "Wrote C# stub: {$path}\n");
        }
    }
}
