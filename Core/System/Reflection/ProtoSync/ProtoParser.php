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

namespace System\Reflection\ProtoSync;

final class ProtoParser
{
    public static function parse(string $protoText): ProtoFile
    {
        $p = new ProtoFile();

        // strip // comments
        $protoText = preg_replace('/\/\/.*$/m', '', $protoText) ?? $protoText;

        // package
        if (preg_match('/\bpackage\s+([a-zA-Z0-9_.]+)\s*;/', $protoText, $m)) {
            $p->package = $m[1];
        }

        // csharp_namespace
        if (preg_match('/\boption\s+csharp_namespace\s*=\s*"([^"]+)"\s*;/', $protoText, $m)) {
            $p->csharpNamespace = $m[1];
        }

        // imports
        if (preg_match_all('/\bimport\s+"([^"]+)"\s*;/', $protoText, $mm)) {
            $p->imports = $mm[1];
        }

        // messages (naive block extraction)
        if (preg_match_all('/\bmessage\s+(\w+)\s*\{([^}]*)\}/s', $protoText, $mm, PREG_SET_ORDER)) {
            foreach ($mm as $msgMatch) {
                $msgName = $msgMatch[1];
                $body = $msgMatch[2];

                $fields = [];
                // field lines: [optional] [repeated] <type> <name> = <tag>;
                if (preg_match_all('/^\s*(optional\s+)?(repeated\s+)?([\.\w]+)\s+(\w+)\s*=\s*(\d+)\s*;\s*$/m', $body, $fm, PREG_SET_ORDER)) {
                    foreach ($fm as $f) {
                        $optional = trim((string)$f[1]) !== '';
                        $repeated = trim((string)$f[2]) !== '';
                        $type = $f[3];
                        $name = $f[4];
                        $tag = (int)$f[5];
                        $fields[] = new ProtoFieldDef($tag, $type, $name, $optional, $repeated);
                    }
                }

                // sort fields by tag
                usort($fields, fn($a, $b) => $a->tag <=> $b->tag);

                $p->messages[] = new ProtoMessageDef($msgName, $fields);
            }
        }

        return $p;
    }
}
