# Répertoire racine à analyser
$RootPath = (Get-Location).Parent.FullName

# Texte à insérer
$HeaderText = @'
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
'@

# Signature minimale pour détecter si le header est déjà présent
$HeaderSignature = "Firstruner and Contributors"

# Récupération de tous les fichiers PHP sauf index.php
$PhpFiles = Get-ChildItem -Path $RootPath -Recurse -Filter *.php |
            Where-Object { $_.Name -ne "index.php" }

foreach ($File in $PhpFiles) {

    $Content = Get-Content -Path $File.FullName -Raw -Encoding UTF8

    # Si le header est déjà présent, on ne touche pas
    if ($Content -match [regex]::Escape($HeaderSignature)) {
        continue
    }

    Write-Host "Ajout du header dans $($File.FullName)"

    if ($Content -match '^\s*<\?php') {
        # Le fichier commence par <?php → on insère juste après
        $NewContent = $Content -replace '^\s*<\?php\s*', "<?php`n`n$HeaderText`n"
    }
    else {
        # Fichier PHP sans balise <?php explicite
        $NewContent = "<?php`n`n$HeaderText`n`n$Content"
    }

    Set-Content -Path $File.FullName -Value $NewContent -Encoding UTF8
}
