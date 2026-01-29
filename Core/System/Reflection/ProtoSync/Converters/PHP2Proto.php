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

use System\Reflection\ProtoSync\Attributes\ProtoMessage;
use System\Reflection\ProtoSync\Attributes\ProtoField;
use System\Reflection\ProtoSync\Naming;
use System\Reflection\ProtoSync\ProtoWriter;
//use System\Reflection\ProtoSync\Cli;
use System\Default\_string;
use System\Reflection\ProtoSync\ProtoConverter;

final class PHP2Proto implements ProtoConverter
{
    public static function Convert(
        string $src,
        string $out,
        string $package = 'demo.contracts',
        string $namespace = _string::EmptyString
    ) {
        // $args = Cli::parseArgs($argv);
        // $src = Cli::must($args, 'src');
        // $outFile = Cli::must($args, 'out');
        // $package = $args['package'] ?? 'demo.contracts';
        // $namespace = $args['csharp_namespace'] ?? '';

        if (!is_dir($src)) {
            fwrite(STDERR, "src folder not found: {$src}\n");
            exit(2);
        }

        // Load all PHP files in src
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($src));
        foreach ($rii as $file) {
            if ($file->isDir()) continue;
            if (strtolower($file->getExtension()) !== 'php') continue;
            require_once $file->getPathname();
        }

        // Collect declared classes newly loaded is hard; we just scan all declared classes and filter by file path.
        $classes = get_declared_classes();
        $messages = [];

        foreach ($classes as $cls) {
            $rc = new \ReflectionClass($cls);
            $fn = $rc->getFileName();
            if ($fn === false) continue;
            if (strpos(realpath($fn) ?: $fn, realpath($src) ?: $src) !== 0) continue;

            $msgAttr = $rc->getAttributes(ProtoMessage::class);
            if (!$msgAttr) continue;

            /** @var ProtoMessage $msgMeta */
            $msgMeta = $msgAttr[0]->newInstance();
            $msgName = $msgMeta->name !== '' ? $msgMeta->name : $rc->getShortName();

            $fields = [];
            foreach ($rc->getProperties() as $prop) {
                $attrs = $prop->getAttributes(ProtoField::class);
                if (!$attrs) continue;

                /** @var ProtoField $meta */
                $meta = $attrs[0]->newInstance();

                $protoName = $meta->name !== '' ? $meta->name : Naming::toSnake($prop->getName());
                $type = $meta->type;

                // Common convenience: allow php DateTimeInterface -> timestamp if type is "datetime"
                if ($type === 'datetime' || $type === 'DateTime' || $type === '\\DateTime' || $type === '\\DateTimeInterface') {
                    $type = 'timestamp';
                }

                $fields[] = [
                    'tag' => $meta->tag,
                    'type' => $type,
                    'name' => $protoName,
                    'optional' => $meta->optional,
                    'repeated' => $meta->repeated,
                ];
            }

            $messages[] = [
                'name' => $msgName,
                'fields' => $fields,
            ];
        }

        $schema = [
            'package' => $package,
            'csharp_namespace' => $namespace,
            'messages' => $messages,
        ];

        $proto = ProtoWriter::write($schema);

        @mkdir(dirname($outFile), 0777, true);
        file_put_contents($outFile, $proto);

        fwrite(STDOUT, "Wrote proto: {$outFile}\n");
    }
}
