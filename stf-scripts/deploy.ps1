#Requires -Version 5.1
param(
    [switch]$SkipBuild
)

$ErrorActionPreference = 'Stop'

$appRepo   = 'stafiocz/coretraining-web-wordpress'
$infraRepo = 'stafiocz/infrsastructure-ds'
$infraDir  = Resolve-Path (Join-Path $PSScriptRoot '..\..\infrsastructure-ds')
$tag       = Get-Date -Format 'yyMMdd'
$priYml    = Join-Path $infraDir 'cust\pri.yml'

function Wait-Run([string]$RunId, [string]$Repo) {
    gh run watch $RunId --repo $Repo
    $conclusion = (gh run view $RunId --repo $Repo --json conclusion | ConvertFrom-Json).conclusion
    if ($conclusion -ne 'success') { throw "Run $RunId selhal (conclusion: $conclusion)." }
}

# --- 1. Build ---
if (-not $SkipBuild) {
    Write-Host "`n=== Build (tag $tag) ===" -ForegroundColor Cyan
    $today = Get-Date -Format 'yyyy-MM-dd'
    $runs  = gh run list --repo $appRepo --workflow build-push.yml --limit 10 --json conclusion,createdAt,databaseId | ConvertFrom-Json
    $ok    = $runs | Where-Object { $_.createdAt.StartsWith($today) -and $_.conclusion -eq 'success' } | Select-Object -First 1

    if ($ok) {
        Write-Host "  Build OK dnes (ID $($ok.databaseId), tag $tag)" -ForegroundColor Green
    } else {
        Write-Host "  Spoustim build..."
        gh workflow run build-push.yml --repo $appRepo
        Start-Sleep 5
        $id = (gh run list --repo $appRepo --workflow build-push.yml --limit 1 --json databaseId | ConvertFrom-Json)[0].databaseId
        Wait-Run $id $appRepo
        Write-Host "  Build OK." -ForegroundColor Green
    }
} else {
    Write-Host "  Build preskocen (-SkipBuild)." -ForegroundColor Yellow
}

# --- 2. Update pri.yml ---
Write-Host "`n=== Update pri.yml (tag $tag) ===" -ForegroundColor Cyan

Push-Location $infraDir
try {
    $content = Get-Content $priYml -Raw
    $content = $content -replace 'image: stafio/coretraining-web:\S+', "image: stafio/coretraining-web:$tag"
    Set-Content $priYml $content -Encoding utf8 -NoNewline

    if (git status --porcelain) {
        git add 'cust\pri.yml'
        git commit -m "release: coretraining-web :$tag"
        git push origin main
        Write-Host "  Pushed." -ForegroundColor Green
    } else {
        Write-Host "  Zadne zmeny (pri.yml uz ma tag $tag)." -ForegroundColor Yellow
    }
} finally {
    Pop-Location
}

# --- 3. Deploy ---
Write-Host "`n=== Deploy (infrsastructure-ds) ===" -ForegroundColor Cyan
gh workflow run deploy.yml --repo $infraRepo
Start-Sleep 5
$deployId = (gh run list --repo $infraRepo --workflow deploy.yml --limit 1 --json databaseId | ConvertFrom-Json)[0].databaseId
Wait-Run $deployId $infraRepo

Write-Host "`n=== Done! coretraining-web nasazen s image :$tag ===" -ForegroundColor Green
