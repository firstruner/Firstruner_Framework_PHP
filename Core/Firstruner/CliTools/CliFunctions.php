<?php
/**
 * Capture un "snapshot" des symboles déclarés.
 * @return array{classes:string[], interfaces:string[], traits:string[], enums:string[], functions:string[]}
 */
function snapshot_declared(): array
{
    // get_declared_classes inclut aussi internes (SplFileInfo, etc.)
    // On filtrera après via diff.
    $enums = function_exists('get_declared_enums') ? get_declared_enums() : [];

    // get_defined_functions() renvoie ['internal'=>[], 'user'=>[]]
    $funcs = get_defined_functions();
    $userFunctions = $funcs['user'] ?? [];

    return [
        'classes'    => get_declared_classes(),
        'interfaces' => get_declared_interfaces(),
        'traits'     => get_declared_traits(),
        'enums'      => $enums,
        'functions'  => $userFunctions,
    ];
}

/**
 * Diff entre deux snapshots (retourne uniquement ce qui est apparu).
 * @param array{classes:string[], interfaces:string[], traits:string[], enums:string[], functions:string[]} $before
 * @param array{classes:string[], interfaces:string[], traits:string[], enums:string[], functions:string[]} $after
 */
function diff_snapshot(array $before, array $after): LoaderReport
{
    $r = new LoaderReport();

    $r->classes = array_values(array_diff($after['classes'], $before['classes']));
    $r->interfaces = array_values(array_diff($after['interfaces'], $before['interfaces']));
    $r->traits = array_values(array_diff($after['traits'], $before['traits']));
    $r->enums = array_values(array_diff($after['enums'], $before['enums']));
    $r->functions = array_values(array_diff($after['functions'], $before['functions']));

    return $r;
}