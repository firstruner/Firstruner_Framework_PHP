# Répertoire racine à analyser (à adapter)
$RootPath = "C:\chemin\vers\votre\projet"

# Contenu du fichier index.php
$IndexContent = @'
<?php

/**
 * Copyright since 2024 Firstruner and Contributors
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
 * @copyright Since 2024 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Location: ../");
exit;
'@

# Récupération de tous les dossiers (récursivement, y compris la racine)
$Directories = Get-ChildItem -Path $RootPath -Directory -Recurse
$Directories += Get-Item -Path $RootPath

foreach ($Dir in $Directories) {
    $IndexPath = Join-Path $Dir.FullName "index.php"

    if (-not (Test-Path $IndexPath)) {
        Write-Host "Création de index.php dans $($Dir.FullName)"
        Set-Content -Path $IndexPath -Value $IndexContent -Encoding UTF8
    }
}
