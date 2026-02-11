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
declare(strict_types=1);

namespace System\Reflection\ProtoSync\Converters;

use System\Default\_string;
use System\Reflection\ProtoSync\ProtoConverter;
use System\Reflection\ProtoSync\Naming;
use System\Reflection\ProtoSync\ProtoWriter;

final class CS2Proto implements ProtoConverter
{
    public static function Convert(
        string $src,
        string $out,
        string $package = 'demo.contracts',
        string $csharpNamespace = _string::EmptyString
    ): void {
        $files = self::collectFiles($src, 'cs');

        $messages = [];
        $guessedCsharpNamespace = '';

        foreach ($files as $file) {
            $txt = file_get_contents($file);
            if ($txt === false) {
                continue;
            }

            // Strip line comments
            $txt = preg_replace('/\/\/.*$/m', _string::EmptyString, $txt) ?? $txt;

            // Namespace (first match)
            if ($guessedCsharpNamespace === '' && preg_match('/\bnamespace\s+([A-Za-z0-9_.]+)\s*[{;]/', $txt, $nm)) {
                $guessedCsharpNamespace = $nm[1];
            }

            // public class/record ... { ... }
            if (!preg_match_all(
                '/\bpublic\s+(?:sealed\s+|abstract\s+)?(?:partial\s+)?(?:class|record)\s+([A-Za-z_][A-Za-z0-9_]*)\b[^{]*\{(.*?)\}\s*/s',
                $txt,
                $cm,
                PREG_SET_ORDER
            )) {
                continue;
            }

            foreach ($cm as $c) {
                $className = $c[1];
                $body = $c[2];

                $fields = [];
                $tag = 1;

                // public <Type> <Name> { get; set; }
                if (preg_match_all(
                    '/\bpublic\s+([A-Za-z0-9_<>\.\?\s]+)\s+([A-Za-z_][A-Za-z0-9_]*)\s*\{\s*get\s*;\s*set\s*;\s*\}/',
                    $body,
                    $pm,
                    PREG_SET_ORDER
                )) {
                    foreach ($pm as $p) {
                        $csType = trim($p[1]);
                        $propName = trim($p[2]);

                        [$protoType, $repeated, $optional] = self::mapCsTypeToProto($csType);

                        $fields[] = [
                            'tag' => $tag++,
                            'type' => $protoType,
                            'name' => Naming::toSnake($propName),
                            'optional' => $optional,
                            'repeated' => $repeated,
                        ];
                    }
                }

                if ($fields) {
                    $messages[] = [
                        'name' => $className,
                        'fields' => $fields,
                    ];
                }
            }
        }

        if ($csharpNamespace === _string::EmptyString && $guessedCsharpNamespace !== _string::EmptyString) {
            $csharpNamespace = $guessedCsharpNamespace;
        }

        $schema = [
            'package' => $package,
            'csharp_namespace' => $csharpNamespace,
            'messages' => $messages,
        ];

        $proto = ProtoWriter::write($schema);

        @mkdir(dirname($out), 0777, true);
        file_put_contents($out, $proto);
    }

    /** @return string[] */
    private static function collectFiles(string $src, string $ext): array
    {
        $files = [];

        if (is_dir($src)) {
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($src));
            foreach ($rii as $f) {
                if ($f->isDir()) continue;
                if (strtolower($f->getExtension()) !== strtolower($ext)) continue;
                $files[] = $f->getPathname();
            }
            return $files;
        }

        if (is_file($src)) {
            return [$src];
        }

        throw new \RuntimeException("src not found: {$src}");
    }

    /** @return array{0:string,1:bool,2:bool} */
    private static function mapCsTypeToProto(string $csType): array
    {
        $csType = trim($csType);

        $repeated = false;
        $optional = false;

        // Nullable: int?, DateTime?, string?
        if (str_ends_with($csType, '?')) {
            $optional = true;
            $csType = trim(rtrim($csType, '?'));
        }

        // List<T>
        if (preg_match('/^(System\.)?(Collections\.)?(Generic\.)?List\s*<\s*([^>]+)\s*>$/', $csType, $m)) {
            $repeated = true;
            $csType = trim($m[4]);
            $csType = trim(rtrim($csType, '?'));
        }

        $key = strtolower($csType);

        $proto = match ($key) {
            'string' => 'string',
            'bool', 'boolean' => 'bool',
            'int', 'int32', 'system.int32' => 'int32',
            'long', 'int64', 'system.int64' => 'int64',
            'uint', 'uint32', 'system.uint32' => 'uint32',
            'ulong', 'uint64', 'system.uint64' => 'uint64',
            'float', 'single', 'system.single' => 'float',
            'double', 'system.double' => 'double',
            'datetime', 'system.datetime', 'datetimeoffset', 'system.datetimeoffset' => 'google.protobuf.Timestamp',
            default => Naming::toPascal(preg_replace('/^.*\./', _string::EmptyString, $csType) ?? $csType),
        };

        return [$proto, $repeated, $optional];
    }
}

