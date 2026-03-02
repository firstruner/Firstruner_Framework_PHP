#
# Copyright 2024-2026 Firstruner and Contributors
# Firstruner is an Registered Trademark & Property of Christophe BOULAS
#
# NOTICE OF LICENSE
#
# This source file is subject to the Freemium License
# If you did not receive a copy of the license and are unable to
# obtain it through the world-wide-web, please send an email
# to contact@firstruner.fr so we can send you a copy immediately.
#
# DISCLAIMER
#
# Do not edit, reproduce ou modify this file.
# Please refer to https://firstruner.fr/ or contact Firstruner for more information.
#
# @author    Firstruner and Contributors <contact@firstruner.fr>
# @copyright 2024-2026 Firstruner and Contributors
# @license   Proprietary
# @version 2.0.0
#

$RootPath = (Get-Item -LiteralPath (Get-Location).Path).Parent.FullName

if (-not (Test-Path -LiteralPath $RootPath)) {
    throw "RootPath invalide: '$RootPath'"
}

# Cible: tous les .php (adapte si tu veux inclure .phtml, .inc.php, etc.)
$Files = Get-ChildItem -LiteralPath $RootPath -Recurse -File -Force -Filter *.php

# UTF-8 SANS BOM
$Utf8NoBom = New-Object System.Text.UTF8Encoding($false)

foreach ($File in $Files) {

    $Bytes = [System.IO.File]::ReadAllBytes($File.FullName)
    $HadBinaryBom = $false

    # BOM UTF-8 binaire: EF BB BF
    if ($Bytes.Length -ge 3 -and $Bytes[0] -eq 0xEF -and $Bytes[1] -eq 0xBB -and $Bytes[2] -eq 0xBF) {
        $HadBinaryBom = $true
        # Décodage sans inclure le BOM
        $Text = $Utf8NoBom.GetString($Bytes, 3, $Bytes.Length - 3)
    }
    else {
        # Décodage normal (on ne “rajoute” pas de BOM)
        $Text = $Utf8NoBom.GetString($Bytes)
    }

    # BOM Unicode invisible U+FEFF (parfois injecté dans les entêtes copiés/collés)
    $Text2 = $Text -replace "^\uFEFF+", ""

    # Si quelque chose a changé => réécriture sans BOM
    if ($HadBinaryBom -or ($Text2 -ne $Text)) {
        Write-Host "BOM supprimé: $($File.FullName)"
        [System.IO.File]::WriteAllText($File.FullName, $Text2, $Utf8NoBom)
    }
}
