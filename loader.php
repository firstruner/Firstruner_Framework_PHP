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

// Définitions
define("HOME_LOADER", __DIR__);

// Requires
foreach (glob(__DIR__ . '/Frameworks/LoadingLists/*.php') as $file)
    require_once $file;

require_once(__DIR__ . '/Core/Firstruner/CliTools/CliToolsLoader.php');

require_once(__DIR__ . '/Frameworks/Framework_Tools.php');
require_once(__DIR__ . '/Frameworks/Framework.php');
require_once(__DIR__ . '/Frameworks/Framework_Symfony.php');

// Using
use Firstruner\Frameworks\Framework;
use Firstruner\Frameworks\Framework_Symfony;

// Variables de CLI
$debug = false;
$details = false;
$includeFiles = false;
$passErrors = false;
$showStackTrace = false;

/**
 * Exemple: ta logique de chargement.
 * Remplace ce bloc par ton mécanisme réel (require vendor/autoload.php, scan, etc.)
 *
 * @return string[] liste des fichiers réellement inclus
 */
function do_loading(bool $details, bool $passErrors, bool $showStackTrace): array
{
    $includedBefore = get_included_files();

    Framework::$VendorLoading = false;
    Framework::Load(details: $details, passErrors: $passErrors, showStackTrace: $showStackTrace);

    Framework_Symfony::$VendorLoading = false;
    Framework_Symfony::Load();

    if (!Framework::IsLoaded())
        echo "/!\ FIRSTRUNER FRAMEWORK : LOADER FAILURE !";

    if (!Framework_Symfony::IsLoaded())
        echo "/!\ SYMFONY FRAMEWORK : LOADER FAILURE !";

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
    $summary = $flags->has('--summary');
    $showStackTrace = $flags->has('--stacktrace');

    // Réduire le détail des erreurs si pas en Debug
    if (!$showStackTrace) {
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        error_reporting(E_ALL);

        // Gestionnaire d'exceptions
        set_exception_handler(function ($e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo $e->getMessage();
        });
    }

    if ($flags->has('--help') || $flags->has('--h')) {
        echo
        "Arguments :" . PHP_EOL .
            "  --help / --h     Affiche l'aide" . PHP_EOL .
            "  --summary        Affiche un récapitulatif du chargement" . PHP_EOL .
            "  --debug          Affiche un diagnostique rapide" . PHP_EOL .
            "  --stacktrace     Affiche la Stack Trace en cas d'exception" . PHP_EOL .
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

$loadedFiles = do_loading($details, $passErrors, $showStackTrace);
$report = new LoaderReport();

if (isset($argv)) {
    $afterLoading = snapshot_declared();
    $report = diff_snapshot($beforeLoading, $afterLoading);

    if ($debug) {
        if ($includeFiles) {
            echo PHP_EOL . "[Debug] Fichiers inclus (" . count($loadedFiles) . ")\n";
            echo "-------------------------\n";
            sort($loadedFiles, SORT_STRING);

            foreach ($loadedFiles as $f)
                echo " - " . $f . PHP_EOL;

            echo PHP_EOL;
        }
    }

    if ($debug || $summary)
        $report->printSummary($debug);
}
