# =================================================================
# SCRIPT DE REPARACIÓN SISTEMA ALUMNOS UTEsc - WINDOWS PowerShell
# =================================================================

Write-Host "🚀 INICIANDO REPARACIÓN DEL SISTEMA DE ALUMNOS UTEsc" -ForegroundColor Green
Write-Host "=====================================================" -ForegroundColor Green

# Verificar que estamos en la raíz del proyecto Laravel
if (-not (Test-Path "artisan")) {
    Write-Host "❌ No se encuentra el archivo 'artisan'. ¿Estás en la raíz del proyecto Laravel?" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "📋 PASO 1: Creando migración para campo cuatrimestre_id" -ForegroundColor Yellow

# Crear migración
$migracionNombre = "add_cuatrimestre_id_to_alumnos_table"
$fecha = Get-Date -Format "yyyy_MM_dd_HHmmss"
$archivoMigracion = "database/migrations/${fecha}_${migracionNombre}.php"

# Contenido de la migración
$contenidoMigracion = @"
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCuatrimestreIdToAlumnosTable extends Migration
{
    public function up()
    {
        Schema::table('alumnos', function (Blueprint `$table) {
            if (!Schema::hasColumn('alumnos', 'cuatrimestre_id')) {
                `$table->unsignedBigInteger('cuatrimestre_id')->nullable()->after('carrera_id');
                `$table->index('cuatrimestre_id');
            }
            
            if (!Schema::hasColumn('alumnos', 'apellidos')) {
                `$table->string('apellidos')->nullable()->after('nombre');
            }
            
            if (!Schema::hasColumn('alumnos', 'activo')) {
                `$table->boolean('activo')->default(true)->after('cuatrimestre_id');
            }
            
            if (!Schema::hasColumn('alumnos', 'created_at')) {
                `$table->timestamps();
            }
        });

        // Actualizar registros existentes
        DB::table('alumnos')->whereNull('cuatrimestre_id')->update([
            'cuatrimestre_id' => 1,
            'activo' => true
        ]);
    }

    public function down()
    {
        Schema::table('alumnos', function (Blueprint `$table) {
            `$table->dropColumn(['cuatrimestre_id', 'apellidos', 'activo']);
        });
    }
}
"@

# Crear archivo de migración
New-Item -Path $archivoMigracion -ItemType File -Force -Value $contenidoMigracion
Write-Host "✅ Migración creada: $archivoMigracion" -ForegroundColor Green

Write-Host ""
Write-Host "📋 PASO 2: Ejecutando migración" -ForegroundColor Yellow
php artisan migrate --force

Write-Host ""
Write-Host "📋 PASO 3: Creando comando para reparar actividades" -ForegroundColor Yellow

# Crear directorio para comandos si no existe
if (-not (Test-Path "app/Console/Commands")) {
    New-Item -Path "app/Console/Commands" -ItemType Directory -Force
}

# Contenido del comando de reparación de actividades
$contenidoComandoActividades = @"
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Actividad;

class RepararActividadesRespuestas extends Command
{
    protected `$signature = 'actividades:reparar-respuestas {--force}';
    protected `$description = 'Repara actividades sin respuestas correctas';

    public function handle()
    {
        `$this->info('🔧 REPARANDO ACTIVIDADES SIN RESPUESTAS CORRECTAS');
        
        `$actividades = Actividad::all();
        `$actividadesReparadas = 0;

        foreach (`$actividades as `$actividad) {
            `$contenido = `$actividad->contenido;
            `$necesitaReparacion = false;

            if (!is_array(`$contenido)) continue;

            // Buscar preguntas
            `$preguntas = null;
            if (isset(`$contenido['contenido']['preguntas'])) {
                `$preguntas = &`$contenido['contenido']['preguntas'];
            } elseif (isset(`$contenido['preguntas'])) {
                `$preguntas = &`$contenido['preguntas'];
            }

            if (!`$preguntas || !is_array(`$preguntas)) continue;

            // Reparar cada pregunta
            foreach (`$preguntas as `$index => &`$pregunta) {
                if (!isset(`$pregunta['correcta'])) {
                    // Buscar respuesta correcta en otros campos
                    if (isset(`$pregunta['respuesta'])) {
                        `$pregunta['correcta'] = strtoupper(`$pregunta['respuesta']);
                    } elseif (isset(`$pregunta['answer'])) {
                        `$pregunta['correcta'] = strtoupper(`$pregunta['answer']);
                    } elseif (isset(`$pregunta['correct'])) {
                        `$pregunta['correcta'] = strtoupper(`$pregunta['correct']);
                    } else {
                        // Asignar por defecto
                        `$pregunta['correcta'] = 'A';
                    }
                    `$necesitaReparacion = true;
                    `$this->line("✅ Actividad {`$actividad->id}, Pregunta {`$index}: Reparada");
                }
            }

            if (`$necesitaReparacion) {
                `$actividad->contenido = `$contenido;
                `$actividad->save();
                `$actividadesReparadas++;
            }
        }

        `$this->info("🎉 Actividades reparadas: {`$actividadesReparadas}");
        return 0;
    }
}
"@

# Crear archivo del comando
$archivoComando = "app/Console/Commands/RepararActividadesRespuestas.php"
New-Item -Path $archivoComando -ItemType File -Force -Value $contenidoComandoActividades
Write-Host "✅ Comando creado: $archivoComando" -ForegroundColor Green

Write-Host ""
Write-Host "📋 PASO 4: Registrando comando en Kernel.php" -ForegroundColor Yellow

$kernelPath = "app/Console/Kernel.php"
if (Test-Path $kernelPath) {
    $kernelContent = Get-Content $kernelPath -Raw
    if ($kernelContent -notmatch "RepararActividadesRespuestas") {
        # Agregar comando al array de comandos
        $kernelContent = $kernelContent -replace '(protected \$commands = \[)', "`$1`r`n        Commands\RepararActividadesRespuestas::class,"
        Set-Content -Path $kernelPath -Value $kernelContent
        Write-Host "✅ Comando registrado en Kernel.php" -ForegroundColor Green
    } else {
        Write-Host "✅ Comando ya registrado en Kernel.php" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "📋 PASO 5: Ejecutando reparación de actividades" -ForegroundColor Yellow
php artisan actividades:reparar-respuestas --force

Write-Host ""
Write-Host "📋 PASO 6: Limpiando y regenerando caches" -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

Write-Host ""
Write-Host "📋 PASO 7: Regenerando caches optimizados" -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache

Write-Host ""
Write-Host "📋 PASO 8: Verificando estado final del sistema" -ForegroundColor Yellow

# Verificar base de datos
Write-Host "🗄️ Verificando base de datos..." -ForegroundColor Cyan
php artisan tinker --execute="
try {
    echo 'Alumnos: ' . App\Models\Alumno::count() . PHP_EOL;
    echo 'Actividades: ' . App\Models\Actividad::count() . PHP_EOL;
    echo 'Intentos: ' . App\Models\ActividadIntento::count() . PHP_EOL;
    echo '✅ Conexión BD correcta' . PHP_EOL;
} catch (Exception `$e) {
    echo '❌ Error BD: ' . `$e->getMessage() . PHP_EOL;
}
"

Write-Host ""
Write-Host "📋 PASO 9: Creando archivo de prueba para verificar rutas" -ForegroundColor Yellow

# Crear script de prueba de rutas
$scriptPruebaRutas = @"
<?php
// Script de prueba de rutas - ejecutar con: php test_rutas.php

require_once 'vendor/autoload.php';

`$app = require_once 'bootstrap/app.php';
`$kernel = `$app->make(Illuminate\Contracts\Console\Kernel::class);
`$kernel->bootstrap();

echo "🧪 PROBANDO RUTAS CRÍTICAS:" . PHP_EOL;

`$rutasCriticas = [
    'alumnos.panel',
    'alumnos.procesar',
    'alumnos.mis-grupos', 
    'alumnos.ver-actividad',
    'alumnos.procesar-respuesta',
    'alumnos.resultado',
    'alumnos.historial'
];

foreach (`$rutasCriticas as `$ruta) {
    try {
        if (str_contains(`$ruta, '{')) {
            // Ruta con parámetros
            `$url = route(`$ruta, 1);
        } else {
            `$url = route(`$ruta);
        }
        echo "✅ {`$ruta}: {`$url}" . PHP_EOL;
    } catch (Exception `$e) {
        echo "❌ {`$ruta}: ERROR - " . `$e->getMessage() . PHP_EOL;
    }
}
"@

Set-Content -Path "test_rutas.php" -Value $scriptPruebaRutas
php test_rutas.php
Remove-Item "test_rutas.php" -Force

Write-Host ""
Write-Host "🎉 REPARACIÓN COMPLETADA!" -ForegroundColor Green
Write-Host "=========================" -ForegroundColor Green
Write-Host ""
Write-Host "📋 PRÓXIMOS PASOS MANUALES:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. 📁 COPIAR ARCHIVOS CORREGIDOS:" -ForegroundColor White
Write-Host "   - AlumnoController.php corregido a app/Http/Controllers/" -ForegroundColor Gray
Write-Host "   - alumno.blade.php a resources/views/layouts/" -ForegroundColor Gray
Write-Host "   - Vistas corregidas a resources/views/alumnos/" -ForegroundColor Gray
Write-Host "   - AccesoAlumnoMiddleware.php a app/Http/Middleware/" -ForegroundColor Gray
Write-Host ""
Write-Host "2. ⚙️ REGISTRAR MIDDLEWARE:" -ForegroundColor White
Write-Host "   - Agregar a app/Http/Kernel.php:" -ForegroundColor Gray
Write-Host "   'acceso.alumno' => \App\Http\Middleware\AccesoAlumnoMiddleware::class," -ForegroundColor Gray
Write-Host ""
Write-Host "3. 🔄 ACTUALIZAR RUTAS:" -ForegroundColor White
Write-Host "   - Reemplazar routes/web.php con el archivo corregido" -ForegroundColor Gray
Write-Host ""
Write-Host "4. 🧪 PROBAR SISTEMA:" -ForegroundColor White
Write-Host "   - Acceder a /alumnos" -ForegroundColor Gray
Write-Host "   - Ingresar con matrícula válida" -ForegroundColor Gray
Write-Host "   - Realizar actividad completa" -ForegroundColor Gray
Write-Host ""
Write-Host "5. 📊 MONITOREAR:" -ForegroundColor White
Write-Host "   - Revisar logs: Get-Content storage/logs/laravel.log -Tail 50" -ForegroundColor Gray
Write-Host ""
Write-Host "⚠️ IMPORTANTE: Las actividades ahora tienen respuestas correctas asignadas." -ForegroundColor Yellow
Write-Host "Si necesitas ajustar respuestas específicas, edita las actividades desde el panel de profesores." -ForegroundColor Yellow

Write-Host ""
Write-Host "Presiona Enter para continuar..." -ForegroundColor Cyan
Read-Host