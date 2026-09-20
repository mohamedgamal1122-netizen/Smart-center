# نسخ احتياطي PowerShell
$src = Join-Path $PSScriptRoot "database/database.sqlite"
$dstDir = Join-Path $PSScriptRoot "backups"
if (-not (Test-Path $dstDir)) { New-Item -ItemType Directory -Path $dstDir | Out-Null }
$dst = Join-Path $dstDir ("center_{0}.sqlite" -f (Get-Date -Format "yyyy-MM-dd_HHmm"))
Copy-Item $src $dst
Write-Host "تم النسخ الى $dst" -ForegroundColor Green

# للـ MySQL Docker (اختياري):
# docker compose exec db mysqldump -u center -psecret center > "$dstDir/center_$(Get-Date -Format yyyy-MM-dd).sql"
