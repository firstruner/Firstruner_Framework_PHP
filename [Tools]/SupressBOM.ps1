# Racine = dossier courant remonté d'un niveau (supporte [ ])
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
