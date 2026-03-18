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
 * @version 3.3.0
 */

require_once('./loader.php');

use System\Reflection\DelegateValidator;
use System\Attributes\Delegate;

interface OwnDelegates
{
       public function MyFunction(string $a, string $b): void;
       public function FunctionWithType(int $x): string;
       public function FunctionWithReference(array &$data): void;
       public function VariadicFunction(string ...$args): void;
}

class Test
{
       // --- 1. VALID CASES ---

       #[Delegate(OwnDelegates::class, 'MyFunction')]
       public function testOk(string $a, string $b): void
       {
              // Good signature
       }

       #[Delegate(OwnDelegates::class, 'FunctionWithReference')]
       public function testRefOk(array &$data): void
       {
              // Good signature with reference
       }


       // --- 2. INVALID CASES ---

       #[Delegate(OwnDelegates::class, 'MyFunction')]
       public function errParameterCount(string $a): void
       {
              // Error: 1 parameter instead of 2
       }

       #[Delegate(OwnDelegates::class, 'FunctionWithType')]
       public function errParameterType(string $x): string
       {
              // Error: expected parameter "int", "string" provided
       }

       #[Delegate(OwnDelegates::class, 'FunctionWithType')]
       public function errReturnType(int $x): int
       {
              // Error: expected return "string", "int" provided
       }

       #[Delegate(OwnDelegates::class, 'FunctionWithReference')]
       public function errMissingReference(array $data): void
       {
              // Error: missing pass by reference (&$data)
       }

       #[Delegate(OwnDelegates::class, 'VariadicFunction')]
       public function errMissingVariadic(string $args): void
       {
              // Error: missing variadic (...$args)
       }

       #[Delegate('UnknownInterface', 'UnknownMethod')]
       public function errUnknownInterface(): void
       {
              // Error: the interface does not exist
       }
}


// We need to use reflection to test each method or it will stop at the first error.

$class = new \ReflectionClass(Test::class);
$hasErrors = false;

echo "--- START OF DELEGATES TESTS ---\n\n";

foreach ($class->getMethods() as $method) {
    $attributes = $method->getAttributes(Delegate::class);
    
    foreach ($attributes as $attribute) {
        $delegate = $attribute->newInstance();
        
        try {
            $validatorClass = new \ReflectionClass(DelegateValidator::class);
            $validateMethod = $validatorClass->getMethod('validateMethodAgainstDelegate');
            
            $validateMethod->invoke(null, $method, $delegate);
            
            echo "[OK] " . $method->getName() . "\n";
        } catch (\Exception $e) {
            echo "[ERROR] " . $method->getName() . " : " . $e->getMessage() . "\n";
            $hasErrors = true;
        }
    }
}

echo "\n--- END OF TESTS ---\n";

if (!$hasErrors) {
    echo "\nGlobal validation OK\n";
}
