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

use Firstruner\Framework;
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

    private const LoaderReportVersion = 1.3;
    private string $empty_space = "                    ";

    private function getGitBranch(): ?string
    {
        $gitDir = HOME_LOADER . '/.git';

        if (!is_dir($gitDir))
            return null;

        $headFile = $gitDir . '/HEAD';

        if (!file_exists($headFile))
            return null;

        $headContent = trim(file_get_contents($headFile));

        // 3. Cas normal : HEAD pointe vers une branche
        if (preg_match('#^ref:\s+refs/heads/(.+)$#', $headContent, $matches))
            return $matches[1];

        // 4. Cas detached HEAD (commit direct)
        if (preg_match('/^[a-f0-9]{40}$/', $headContent))
            return 'detached (' . substr($headContent, 0, 7) . ')';

        return null;
    }

    private function mb_str_pad(string $text, int $pad_length, string $pad_string = ' ', int $pad_type = STR_PAD_RIGHT, string $encoding = 'UTF-8'): string
    {
        $text_len = mb_strlen($text, $encoding);
        $pad_needed = $pad_length - $text_len;
        if ($pad_needed <= 0) return $text;

        switch ($pad_type) {
            case STR_PAD_LEFT:
                return str_repeat($pad_string, $pad_needed) . $text;
            case STR_PAD_BOTH:
                $left = intdiv($pad_needed, 2);
                $right = $pad_needed - $left;
                return str_repeat($pad_string, $left) . $text . str_repeat($pad_string, $right);
            case STR_PAD_RIGHT:
            default:
                return $text . str_repeat($pad_string, $pad_needed);
        }
    }

    private function padText(string $texte) : string
    {
        $width = 36; // largeur interne EXACTE entre ║ et ║ (d'après ton cadre)
        return $this->empty_space . "║" . $this->mb_str_pad($texte, $width) . "║";
    }

    private function printLogo() : string
    {
        return "                                      
                                                                                
                    ███████████████████████████████████████████                 
                    ███████████████████████████████████████████                 
                             ██████████████████████████████████                 
                             ██████████████████████████████████                 
                 █████████   ██████████████████████████████████                 
                 █████████   ██████████████████████████████████                 
                 █████████   ██████████████████████████████████                 
                 █████████   █████                                              
                 █████████   █████                                              
                 █████████   █████                                              
                 █████████   █████                                              
                 █████████   █████                                              
                 █████████   █████                                              
                 █████████   █████                                              
                 █████████                                                      
                 █████████                                                      
                 █████████████████████████████████████                          
                 ████████████████████████████████████                           
                 ███████████████████████████████████                            
                 ██████████████████████████████████                             
                 ██████████████████████████████████                             
                 █████████████████████████████████                              
                 ████████████████████████████████                               
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  
                 █████████████                                                  ";
    }

    public function printSummary(bool $debug): void
    {
        if ($debug)
        {
            $this->printList("Classes", $this->classes);
            $this->printList("Interfaces", $this->interfaces);
            $this->printList("Traits", $this->traits);
            $this->printList("Enums", $this->enums);
            $this->printList("Fonctions", $this->functions);
        }

        echo $this->printLogo();

        echo PHP_EOL . PHP_EOL . PHP_EOL . 
             $this->empty_space . "╔════════════════════════════════════╗" . PHP_EOL .
             $this->empty_space . "║              SUMMARY               ║" . PHP_EOL .
             $this->empty_space . "╚════════════════════════════════════╝" . PHP_EOL . PHP_EOL;

        echo $this->empty_space . "╔════════════════════════════════════╗" . PHP_EOL;
        echo $this->empty_space . "║ System informations                ║" . PHP_EOL;
        echo $this->empty_space . "╟────────────────────────────────────╢" . PHP_EOL;
        echo $this->padText("Loader Report Version : " . LoaderReport::LoaderReportVersion) . PHP_EOL;
        echo $this->padText("Loader Version        : " . Loader::LoaderVersion) . PHP_EOL;
        echo $this->padText("PHP Version           : " . phpversion()) . PHP_EOL;
        echo $this->padText("Branch name           : " . $this->getGitBranch()) . PHP_EOL;
        echo $this->padText("Framework version     : " . Framework::FrameworkVersion) . PHP_EOL;
        echo $this->empty_space . "╚════════════════════════════════════╝" . PHP_EOL . PHP_EOL;

        echo $this->empty_space . "╔════════════════════════════════════╗" . PHP_EOL;
        echo $this->empty_space . "║ Chargements détectés               ║" . PHP_EOL;
        echo $this->empty_space . "╟────────────────────────────────────╢" . PHP_EOL;
        echo $this->padText("Classes    : " . count($this->classes)) . PHP_EOL;
        echo $this->padText("Interfaces : " . count($this->interfaces)) . PHP_EOL;
        echo $this->padText("Traits     : " . count($this->traits)) . PHP_EOL;
        echo $this->padText("Enums      : " . count($this->enums)) . PHP_EOL;
        echo $this->padText("Fonctions  : " . count($this->functions)) . PHP_EOL;
        echo $this->empty_space . "╚════════════════════════════════════╝" . PHP_EOL . PHP_EOL;

        echo $this->empty_space . "╔════════════════════════════════════╗" . PHP_EOL;
        echo $this->empty_space . "║ Propriétés du Loader               ║" . PHP_EOL;
        echo $this->empty_space . "╟────────────────────────────────────╢" . PHP_EOL;
        echo $this->padText("Debug      : " . (Loader::$debug ? "Activé" : "Désactivé")) . PHP_EOL;
        echo $this->padText("Erreurs    : " . (Loader::$passErrors ? "Non gérées" : "Gérées")) . PHP_EOL;
        echo $this->empty_space . "╚════════════════════════════════════╝" . PHP_EOL . " ";
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
            echo $this->empty_space . " - " . $name . PHP_EOL;
        }
    }
}
