Write-Host "=== UTB HR Central Project Size Analysis ===" -ForegroundColor Cyan
Write-Host ""

# Project files (excluding node_modules, vendor, storage, .git)
Write-Host "Calculating project size (excluding dependencies)..." -ForegroundColor Yellow
$projectSize = 0
Get-ChildItem -Path . -Recurse -File -ErrorAction SilentlyContinue | Where-Object { 
    $_.FullName -notmatch 'node_modules|vendor|storage\\logs|\.git' 
} | ForEach-Object { 
    $projectSize += $_.Length 
}
Write-Host "Project Code & Files: $([math]::Round($projectSize/1MB,2)) MB" -ForegroundColor Green
Write-Host ""

# node_modules
if (Test-Path 'node_modules') {
    $nodeSize = (Get-ChildItem 'node_modules' -Recurse -File -ErrorAction SilentlyContinue | Measure-Object -Property Length -Sum).Sum
    Write-Host "node_modules: $([math]::Round($nodeSize/1MB,2)) MB" -ForegroundColor Yellow
} else {
    Write-Host "node_modules: Not found (can be regenerated with 'npm install')" -ForegroundColor Gray
}
Write-Host ""

# vendor
if (Test-Path 'vendor') {
    $vendorSize = (Get-ChildItem 'vendor' -Recurse -File -ErrorAction SilentlyContinue | Measure-Object -Property Length -Sum).Sum
    Write-Host "vendor: $([math]::Round($vendorSize/1MB,2)) MB" -ForegroundColor Yellow
} else {
    Write-Host "vendor: Not found (can be regenerated with 'composer install')" -ForegroundColor Gray
}
Write-Host ""

# Total size
$totalSize = 0
Get-ChildItem -Path . -Recurse -File -ErrorAction SilentlyContinue | ForEach-Object { 
    $totalSize += $_.Length 
}
Write-Host "Total Project Size (including all): $([math]::Round($totalSize/1MB,2)) MB" -ForegroundColor Cyan
Write-Host ""

# Database
if (Test-Path 'database\database.sqlite') {
    $dbSize = (Get-Item 'database\database.sqlite').Length
    Write-Host "SQLite Database: $([math]::Round($dbSize/1MB,2)) MB" -ForegroundColor Magenta
} else {
    Write-Host "SQLite Database: Not found (using MySQL)" -ForegroundColor Gray
}
Write-Host ""

# Public images
if (Test-Path 'public\images') {
    $imgSize = (Get-ChildItem 'public\images' -Recurse -File -ErrorAction SilentlyContinue | Measure-Object -Property Length -Sum).Sum
    Write-Host "Public Images: $([math]::Round($imgSize/1MB,2)) MB" -ForegroundColor Magenta
}
Write-Host ""

Write-Host "=== Summary ===" -ForegroundColor Cyan
Write-Host "Code & Files (for Git): $([math]::Round($projectSize/1MB,2)) MB" -ForegroundColor Green
Write-Host "Dependencies (regeneratable): $([math]::Round(($nodeSize + $vendorSize)/1MB,2)) MB" -ForegroundColor Yellow
Write-Host "Total on disk: $([math]::Round($totalSize/1MB,2)) MB" -ForegroundColor Cyan

