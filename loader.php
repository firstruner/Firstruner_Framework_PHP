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

require_once(__DIR__ . '/Core/Firstruner/CliTools/CliToolsLoader.php');

require_once(__DIR__ . '/Framework.php');
require_once(__DIR__ . '/Framework_Symfony.php');

use Firstruner\Framework;
use Firstruner\Framework_Symfony;

$debug = false;
$details = false;
$includeFiles = false;
$passErrors = false;

/**
 * Exemple: ta logique de chargement.
 * Remplace ce bloc par ton mécanisme réel (require vendor/autoload.php, scan, etc.)
 *
 * @return string[] liste des fichiers réellement inclus
 */
function do_loading(bool $debug, bool $passErrors): array
{
    $includedBefore = get_included_files();

    Framework::$VendorLoading = false;
    Framework::Load(debug: $debug, passErrors: $passErrors);

    Framework_Symfony::$VendorLoading = false;
    Framework_Symfony::Load();

    if (!Framework::IsLoaded()) {
        echo "FIRSTRUNER FRAMEWORK : LOADER FAILURE !";
    }
    if (!Framework_Symfony::IsLoaded()) {
        echo "SYMFONY FRAMEWORK : LOADER FAILURE !";
    }


    $includedAfter = get_included_files();

    // fichiers apparus
    $diff = array_values(array_diff($includedAfter, $includedBefore));
    return $diff;
}

if (isset($argv)) {
    $flags = new CliFlags($argv);
    $debug = $flags->has('--debug');
    $details = $flags->has('--details');
    $includeFiles = $flags->has('--includeFiles');
    $passErrors = $flags->has('--passErrors');

    if ($flags->has('--help') || $flags->has('--h')) {
        echo
        "Arguments :" . PHP_EOL .
            "  --help / --h     Affiche l'aide" . PHP_EOL .
            "  --debug          Affiche un diagnostique rapide" . PHP_EOL .
            "  --details        Affiche le détails des chargements" . PHP_EOL .
            "  --includeFiles   Affiche la liste des fichiers chargés" . PHP_EOL .
            "  --passErrors     Outrepasse les erreurs de chargement" . PHP_EOL .
            PHP_EOL . PHP_EOL .
            "Informations :" . PHP_EOL .
            "--details         nécessite --debug" . PHP_EOL .
            "--includeFiles    nécessite --details" . PHP_EOL . " ";

        return;
    }

    $beforeLoading = snapshot_declared();
}

$loadedFiles = do_loading($debug, $passErrors);

if (isset($argv)) {
    $afterLoading = snapshot_declared();
    $report = diff_snapshot($beforeLoading, $afterLoading);

    if ($debug) {
        echo PHP_EOL . "--- PHP Version ---" . PHP_EOL . phpversion() . PHP_EOL;
        $report->printSummary($details);

        if ($includeFiles) {
            echo PHP_EOL . "[Debug] Fichiers inclus (" . count($loadedFiles) . ")\n";
            echo "-------------------------\n";
            sort($loadedFiles, SORT_STRING);

            foreach ($loadedFiles as $f)
                echo " - " . $f . PHP_EOL;

            echo PHP_EOL;
        }
    }
}
