Write-Host "=== UTB HR Central - RAM Usage Analysis ===" -ForegroundColor Cyan
Write-Host ""

# PHP Memory Limit
Write-Host "PHP Configuration:" -ForegroundColor Yellow
$phpMemory = php -r "echo ini_get('memory_limit');"
Write-Host "  PHP Memory Limit: $phpMemory" -ForegroundColor Green
Write-Host ""

# Check running processes
Write-Host "Currently Running Processes:" -ForegroundColor Yellow
$processes = Get-Process | Where-Object { $_.ProcessName -match 'php|mysql|node|herd' } -ErrorAction SilentlyContinue

if ($processes) {
    $totalMemory = 0
    foreach ($proc in $processes) {
        $memMB = [math]::Round($proc.WorkingSet64 / 1MB, 2)
        $totalMemory += $memMB
        Write-Host "  $($proc.ProcessName): $memMB MB" -ForegroundColor Green
    }
    Write-Host ""
    Write-Host "  Total (Running Processes): $([math]::Round($totalMemory, 2)) MB" -ForegroundColor Cyan
} else {
    Write-Host "  No related processes currently running" -ForegroundColor Gray
}
Write-Host ""

# System Requirements
Write-Host "=== System Requirements ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Development Environment:" -ForegroundColor Yellow
Write-Host "  Minimum RAM: 2 GB" -ForegroundColor Green
Write-Host "  Recommended RAM: 4 GB" -ForegroundColor Green
Write-Host "  PHP Memory Limit: 128-256 MB per request" -ForegroundColor Green
Write-Host "  MySQL: 100-200 MB" -ForegroundColor Green
Write-Host "  Node.js (for build): 50-100 MB" -ForegroundColor Green
Write-Host ""

Write-Host "Production Server:" -ForegroundColor Yellow
Write-Host "  Minimum RAM: 512 MB" -ForegroundColor Green
Write-Host "  Recommended RAM: 1-2 GB" -ForegroundColor Green
Write-Host "  PHP-FPM Workers: 50-100 MB each" -ForegroundColor Green
Write-Host "  MySQL: 200-500 MB" -ForegroundColor Green
Write-Host "  Web Server (Nginx/Apache): 50-100 MB" -ForegroundColor Green
Write-Host ""

Write-Host "=== Typical RAM Usage Breakdown ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Per Request (PHP):" -ForegroundColor Yellow
Write-Host "  Simple page load: 10-30 MB" -ForegroundColor Green
Write-Host "  Complex page (with queries): 20-50 MB" -ForegroundColor Green
Write-Host "  PDF generation: 50-100 MB" -ForegroundColor Green
Write-Host "  File uploads: 30-80 MB" -ForegroundColor Green
Write-Host ""

Write-Host "Background Services:" -ForegroundColor Yellow
Write-Host "  MySQL: 100-500 MB (depends on data)" -ForegroundColor Green
Write-Host "  PHP-FPM Pool: 200-500 MB (multiple workers)" -ForegroundColor Green
Write-Host "  Web Server: 50-100 MB" -ForegroundColor Green
Write-Host ""

Write-Host "Total Typical Usage:" -ForegroundColor Yellow
Write-Host "  Development: 500 MB - 1 GB" -ForegroundColor Cyan
Write-Host "  Production (small): 512 MB - 1 GB" -ForegroundColor Cyan
Write-Host "  Production (medium): 1-2 GB" -ForegroundColor Cyan
Write-Host "  Production (large): 2-4 GB" -ForegroundColor Cyan
Write-Host ""

