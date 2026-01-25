<?php
final class CliFlags
{
    /** @var array<string,bool> */
    private array $flags = [];

    public function __construct(array $argv)
    {
        // On ignore $argv[0] (script)
        $args = array_slice($argv, 1);

        // Normalisation: minuscules, trim
        foreach ($args as $arg) {
            $key = strtolower(trim((string)$arg));
            if ($key !== '') {
                $this->flags[$key] = true;
            }
        }
    }

    public function has(string $flag): bool
    {
        return isset($this->flags[strtolower($flag)]);
    }
}