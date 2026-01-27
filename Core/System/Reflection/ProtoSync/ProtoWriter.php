<?php


namespace System\Reflection\ProtoSync;

final class ProtoWriter
{
    /**
     * @param array{package:string,csharp_namespace:string,messages:array<int,array{name:string,fields:array<int,array{tag:int,type:string,name:string,optional:bool,repeated:bool}>}>} $schema
     */
    public static function write(array $schema): string
    {
        $needsTimestamp = false;
        foreach ($schema['messages'] as $m) {
            foreach ($m['fields'] as $f) {
                if ($f['type'] === 'google.protobuf.Timestamp' || $f['type'] === 'timestamp') {
                    $needsTimestamp = true;
                }
            }
        }

        $lines = [];
        $lines[] = 'syntax = "proto3";';
        $lines[] = '';
        $lines[] = 'package ' . $schema['package'] . ';';
        $lines[] = '';
        if ($schema['csharp_namespace'] !== '') {
            $lines[] = 'option csharp_namespace = "' . $schema['csharp_namespace'] . '";';
            $lines[] = '';
        }
        if ($needsTimestamp) {
            $lines[] = 'import "google/protobuf/timestamp.proto";';
            $lines[] = '';
        }

        foreach ($schema['messages'] as $m) {
            $lines[] = 'message ' . $m['name'] . ' {';
            $fields = $m['fields'];
            usort($fields, fn($a, $b) => $a['tag'] <=> $b['tag']);
            foreach ($fields as $f) {
                $type = $f['type'] === 'timestamp' ? 'google.protobuf.Timestamp' : $f['type'];
                $prefix = '';
                if ($f['optional']) $prefix .= 'optional ';
                if ($f['repeated']) $prefix .= 'repeated ';
                $lines[] = '  ' . $prefix . $type . ' ' . $f['name'] . ' = ' . $f['tag'] . ';';
            }
            $lines[] = '}';
            $lines[] = '';
        }

        return implode(PHP_EOL, $lines);
    }
}
