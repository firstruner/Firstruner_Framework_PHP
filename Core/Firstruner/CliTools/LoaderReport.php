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

use System\Reflection\Dependencies\Loader;

final class LoaderReport
{
    /** @var string[] */
    public array $classes = [];
    /** @var string[] */
    public array $interfaces = [];
    /** @var string[] */
    public array $traits = [];
    /** @var string[] */
    public array $enums = [];
    /** @var string[] */
    public array $functions = [];

    public function printSummary(bool $details): void
    {
        echo PHP_EOL;
        echo "------------------------" . PHP_EOL;
        echo "Chargements détectés" . PHP_EOL;
        echo "------------------------" . PHP_EOL;
        echo "Classes    : " . count($this->classes) . PHP_EOL;
        echo "Interfaces : " . count($this->interfaces) . PHP_EOL;
        echo "Traits     : " . count($this->traits) . PHP_EOL;
        echo "Enums      : " . count($this->enums) . PHP_EOL;
        echo "Fonctions  : " . count($this->functions) . PHP_EOL;
        echo "------------------------" . PHP_EOL;
        echo "Propriétés du Loader" . PHP_EOL;
        echo "Debug      : " . (Loader::$debug ? "Activé" : "Désactivé") . PHP_EOL;
        echo "Erreurs    : " . (Loader::$passErrors ? "Non gérées" : "Gérées") . PHP_EOL;
        echo "------------------------" . PHP_EOL . " ";

        if (!$details) {
            return;
        }

        $this->printList("Classes", $this->classes);
        $this->printList("Interfaces", $this->interfaces);
        $this->printList("Traits", $this->traits);
        $this->printList("Enums", $this->enums);
        $this->printList("Fonctions", $this->functions);
    }

    /**
     * @param string[] $items
     */
    private function printList(string $title, array $items): void
    {
        echo PHP_EOL . $title . " (" . count($items) . ")\n";
        echo str_repeat("-", mb_strlen($title) + 6) . "\n";
        sort($items, SORT_STRING);
        foreach ($items as $name) {
            echo " - " . $name . PHP_EOL;
        }
    }
}
