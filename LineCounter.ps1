param(
  [string]$Path = (Get-Location).Path,
  [string]$OutputCsv = "$((Get-Location).Path)\LineCount_ByExtension.csv",
  [switch]$IncludeBinary
)

[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

$binaryExt = @(
  ".dll",".exe",".pdb",".png",".jpg",".jpeg",".gif",".ico",".tif",".tiff",
  ".gz",".zip",".nupkg",".7z",".rar",".chm",".sqlite",".db",".pri",".wav",".bin"
)

function Get-LineCount([string]$filePath) {
  $count = 0
  $reader = $null
  try {
    $reader = [System.IO.StreamReader]::new($filePath, $true)
    while ($null -ne $reader.ReadLine()) { $count++ }
    return $count
  }
  finally {
    if ($reader) { $reader.Dispose() }
  }
}

$files = Get-ChildItem -Path $Path -Recurse -File -ErrorAction SilentlyContinue

$rows = foreach ($f in $files) {
  $ext = if ($f.Extension) { $f.Extension.ToLower() } else { "[no_extension]" }

  if (-not $IncludeBinary -and $binaryExt -contains $ext) {
    continue
  }

  try {
    $lineCount = Get-LineCount -filePath $f.FullName

    [PSCustomObject]@{
      Extension  = $ext
      FileName   = $f.Name
      FullPath   = $f.FullName
      Lines      = $lineCount
      SizeKB     = [math]::Round($f.Length / 1KB, 2)
    }
  }
  catch {}
}

if (-not $rows) {
  Write-Host "Aucun fichier analysé."
  return
}

# Regroupement par extension
$summary = $rows |
  Group-Object Extension |
  ForEach-Object {
    $sum = ($_.Group | Measure-Object -Property Lines -Sum).Sum
    $max = ($_.Group | Measure-Object -Property Lines -Maximum).Maximum
    [PSCustomObject]@{
      Extension   = $_.Name
      TotalLines  = $sum
      FileCount   = $_.Count
      MaxInAFile  = $max
      AvgPerFile  = [math]::Round($sum / $_.Count, 2)
    }
  } |
  Sort-Object TotalLines -Descending

# Export CSV
$summary | Export-Csv -Path $OutputCsv -NoTypeInformation -Encoding UTF8

Write-Host "Export terminé : $OutputCsv"
