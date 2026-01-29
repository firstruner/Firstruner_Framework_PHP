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

$HeaderText = @'
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
'@

$HeaderSignature = "Firstruner and Contributors"

if ([string]::IsNullOrWhiteSpace($RootPath) -or -not (Test-Path -LiteralPath $RootPath)) {
    throw "RootPath invalide: '$RootPath'"
}

# NOTE: -Filter avec -LiteralPath est OK, le chemin reste littéral.
$PhpFiles = Get-ChildItem -LiteralPath $RootPath -Recurse -Force -Filter *.php |
Where-Object { $_.Name -ne "index.php" }

foreach ($File in $PhpFiles) {

    $Content = Get-Content -LiteralPath $File.FullName -Raw -Encoding UTF8

    if ($Content -match [regex]::Escape($HeaderSignature)) {
        continue
    }

    Write-Host "Ajout du header dans $($File.FullName)"

    if ($Content -match '^\s*<\?php') {
        $NewContent = $Content -replace '^\s*<\?php\s*', "<?php`n`n$HeaderText`n"
    }
    else {
        $NewContent = "<?php`n`n$HeaderText`n`n$Content"
    }

    Set-Content -LiteralPath $File.FullName -Value $NewContent -Encoding UTF8
}
