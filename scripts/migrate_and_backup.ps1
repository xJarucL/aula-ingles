Param(
    [string]$BackupDir = "./db_backups",
    [string]$EnvFile = ".env"
)

Write-Host "== Backup DB y migraciones =="

if (-not (Test-Path $BackupDir)) {
    New-Item -ItemType Directory -Path $BackupDir | Out-Null
}

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$dumpFile = Join-Path $BackupDir "dump_$timestamp.sql"

Write-Host "Creando volcado de base de datos en $dumpFile ..."

# Intenta usar mysqldump si está disponible. Ajusta las credenciales en .env si es necesario.
$envContent = Get-Content $EnvFile -ErrorAction SilentlyContinue
$dbHost = ($envContent | Select-String "DB_HOST=.+" | ForEach-Object { $_.ToString().Split('=')[1].Trim() }) -or '127.0.0.1'
$dbName = ($envContent | Select-String "DB_DATABASE=.+" | ForEach-Object { $_.ToString().Split('=')[1].Trim() }) -or 'laravel'
$dbUser = ($envContent | Select-String "DB_USERNAME=.+" | ForEach-Object { $_.ToString().Split('=')[1].Trim() }) -or 'root'
$dbPass = ($envContent | Select-String "DB_PASSWORD=.+" | ForEach-Object { $_.ToString().Split('=')[1].Trim() }) -or ''

if (Get-Command mysqldump -ErrorAction SilentlyContinue) {
    $cmd = "mysqldump --user=$dbUser --password=$dbPass --host=$dbHost $dbName > `"$dumpFile`""
    Write-Host "Ejecutando: $cmd"
    iex $cmd
    Write-Host "Volcado creado"
} else {
    Write-Host "mysqldump no encontrado. Saltando volcado. Asegúrate de hacer backup manualmente antes de migrar."
}

Write-Host "Ejecutando migraciones (php artisan migrate)..."
php artisan migrate --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "Migraciones aplicadas correctamente"
} else {
    Write-Host "Error al aplicar migraciones. Revisa la salida y el volcado si lo creaste."
}

Write-Host "Fin."
