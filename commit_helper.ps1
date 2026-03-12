Clear-Host

function Ask_YesNo {
  param(
    [Parameter(Mandatory=$true)][string]$Question,
    [bool]$DefaultYes = $true
  )

  $suffix = if ($DefaultYes) { "[Y/n]" } else { "[y/N]" }

  while ($true) {
    $ans = (Read-Host "$Question $suffix").Trim()
    if ($ans -eq "") { return $DefaultYes }

    switch -Regex ($ans.ToLower()) {
      '^(y|yes|o|oui)$' { return $true }
      '^(n|no|non)$'    { return $false }
      default           { Write-Host "Réponse invalide. Réponds par y/n." }
    }
  }
}

function Ask_PullLevel {
  $choices = @(
    @{ key="0"; value="";                     label="Rien (par défaut)" },
    @{ key="1"; value="recommended_pull";      label="Pull recommandé" },
    @{ key="2"; value="high_recommended_pull"; label="Pull fortement recommandé" },
    @{ key="3"; value="needed_pull";           label="Pull obligatoire" }
  )

  Write-Host ""
  Write-Host "Niveau de mise à jour demandé :"
  foreach ($c in $choices) {
    Write-Host ("  {0}) {1}" -f $c.key, $c.label)
  }

  while ($true) {
    $sel = (Read-Host "Choix (0-3) [0]").Trim()
    if ($sel -eq "") { $sel = "0" }

    $match = $choices | Where-Object { $_.key -eq $sel }
    if ($match) { return $match.value }

    Write-Host "Choix invalide."
  }
}

# ---------------- MAIN ----------------

$baseMessage = ""
while ([string]::IsNullOrWhiteSpace($baseMessage)) {
  $baseMessage = (Read-Host "Message de commit").Trim()
}

$doNotify   = Ask_YesNo "Notifier ?" $true
$doTests    = Ask_YesNo "Lancer les tests unitaires ?" $true
$doTransfer = Ask_YesNo "Transférer vers GitHub ?" $true
$pullLevel  = Ask_PullLevel

$tokens = @()
if (-not $doNotify)   { $tokens += "no_notify" }
if (-not $doTests)    { $tokens += "no_tests" }
if (-not $doTransfer) { $tokens += "no_transfer" }
if ($pullLevel -ne "") { $tokens += $pullLevel }

$finalMessage = $baseMessage
if ($tokens.Count -gt 0) {
  $finalMessage = "$baseMessage $($tokens -join ' ')"
}

Write-Host "`nMessage final :"
Write-Host "  $finalMessage`n"

# if (Ask_YesNo "Faire le commit maintenant ?" $false) {
git add .
git commit -m "$finalMessage"
if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
Write-Host "Commit effectué."
# }

# ---------------- TAG MANAGEMENT ----------------

$tagName = (Read-Host "Ajouter un tag (laisser vide pour aucun)").Trim()

Write-Host "Push en cours..."
git push origin $currentBranch
if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

if (-not [string]::IsNullOrWhiteSpace($tagName)) {

  # Vérifie si le tag existe localement
  $tagExistsLocal = git tag --list $tagName

  if ($tagExistsLocal) {
    Write-Host "Le tag existe localement → suppression."
    git tag -d $tagName
  }

  # Vérifie si le tag existe sur origin
  $tagExistsRemote = git ls-remote --tags origin $tagName

  if ($tagExistsRemote) {
    Write-Host "Le tag existe sur origin → suppression distante."
    git push --delete origin $tagName
  }

  # Création du nouveau tag
  git tag $tagName
  if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

  # Push du tag
  git push origin $tagName
  if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

  Write-Host "Tag '$tagName' créé et poussé."
} else {
  $currentBranch = git rev-parse --abbrev-ref HEAD

  git push origin $currentBranch
  if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

  Write-Host "Branche poussée vers origin."
}