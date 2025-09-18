@echo off
echo ================================================
echo    SCRIPT DE REPARACION DE RUTAS - LARAVEL
echo ================================================
echo.

echo 1. Limpiando cache de rutas...
php artisan route:clear
echo    ✅ Cache de rutas limpiado

echo.
echo 2. Limpiando cache de configuracion...
php artisan config:clear
echo    ✅ Cache de configuracion limpiado

echo.
echo 3. Limpiando cache de vistas...
php artisan view:clear
echo    ✅ Cache de vistas limpiado

echo.
echo 4. Optimizando aplicacion...
php artisan optimize:clear
echo    ✅ Optimizacion limpiada

echo.
echo 5. Listando rutas de alumnos...
echo    📋 Rutas disponibles:
php artisan route:list --name=alumnos

echo.
echo 6. Verificando rutas especificas...
echo    🔍 Verificando ruta alumnos.panel:
php artisan route:list --name=alumnos.panel

echo    🔍 Verificando ruta alumnos.procesar:
php artisan route:list --name=alumnos.procesar

echo.
echo ================================================
echo ✅ PROCESO COMPLETADO
echo ================================================
echo.
echo SIGUIENTES PASOS:
echo 1. Copia el archivo web.php corregido
echo 2. Copia las vistas panel.blade.php e ingresar.blade.php corregidas
echo 3. Ejecuta: php artisan serve
echo 4. Prueba ir a: http://localhost:8000/alumnos
echo.
pause
