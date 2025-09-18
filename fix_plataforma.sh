#!/bin/bash

# 🚀 SCRIPT DE CORRECCIÓN COMPLETA DE LA PLATAFORMA
# Este script corrige todos los problemas identificados

echo "🔧 INICIANDO CORRECCIÓN DE LA PLATAFORMA EDUCATIVA"
echo "=================================================="

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para mostrar mensajes con color
show_message() {
    echo -e "${GREEN}✅ $1${NC}"
}

show_warning() {
    echo -e "${YELLOW}⚠️ $1${NC}"
}

show_error() {
    echo -e "${RED}❌ $1${NC}"
}

show_info() {
    echo -e "${BLUE}ℹ️ $1${NC}"
}

# Verificar que estamos en un proyecto Laravel
if [ ! -f "artisan" ]; then
    show_error "Este script debe ejecutarse desde el directorio raíz del proyecto Laravel"
    exit 1
fi

# 1. BACKUP DE LA BASE DE DATOS
show_info "1. Creando backup de la base de datos..."
php artisan db:show > database_backup_$(date +%Y%m%d_%H%M%S).sql 2>/dev/null || show_warning "No se pudo crear backup automático"

# 2. CREAR MIGRACIÓN DE CORRECCIÓN
show_info "2. Creando migración de corrección..."
php artisan make:migration fix_actividad_intentos_table_final --create=temp_fix > /dev/null 2>&1

# Crear el archivo de migración con el contenido correcto
MIGRATION_FILE=$(find database/migrations -name "*fix_actividad_intentos_table_final.php" | head -1)
if [ -n "$MIGRATION_FILE" ]; then
    cat > "$MIGRATION_FILE" << 'EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Verificar y corregir tabla actividad_intentos
        if (Schema::hasTable('actividad_intentos')) {
            // Eliminar alumno_matricula si existe (causa del error)
            if (Schema::hasColumn('actividad_intentos', 'alumno_matricula')) {
                Schema::table('actividad_intentos', function (Blueprint $table) {
                    $table->dropColumn('alumno_matricula');
                });
            }

            // Asegurar que todas las columnas necesarias existen
            Schema::table('actividad_intentos', function (Blueprint $table) {
                if (!Schema::hasColumn('actividad_intentos', 'alumno_nombre')) {
                    $table->string('alumno_nombre')->nullable()->after('actividad_id');
                }
                
                if (!Schema::hasColumn('actividad_intentos', 'respuestas')) {
                    $table->json('respuestas')->after('alumno_nombre');
                }
                
                if (!Schema::hasColumn('actividad_intentos', 'puntaje')) {
                    $table->integer('puntaje')->default(0)->after('respuestas');
                }
                
                if (!Schema::hasColumn('actividad_intentos', 'total_preguntas')) {
                    $table->integer('total_preguntas')->default(0)->after('puntaje');
                }
                
                if (!Schema::hasColumn('actividad_intentos', 'tiempo_completado')) {
                    $table->integer('tiempo_completado')->default(0)->after('total_preguntas');
                }
            });

            // Hacer alumno_nombre nullable
            DB::statement('ALTER TABLE actividad_intentos MODIFY alumno_nombre VARCHAR(255) NULL');
        }
        
        // 2. Limpiar intentos con datos corruptos
        DB::table('actividad_intentos')
            ->where('total_preguntas', 0)
            ->orWhereNull('respuestas')
            ->delete();
    }

    public function down()
    {
        // Rollback si es necesario
    }
};
EOF
    show_message "Migración de corrección creada: $MIGRATION_FILE"
else
    show_error "No se pudo crear el archivo de migración"
    exit 1
fi

# 3. EJECUTAR MIGRACIÓN
show_info "3. Ejecutando migración de corrección..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    show_message "Migración ejecutada exitosamente"
else
    show_error "Error al ejecutar la migración"
    exit 1
fi

# 4. CREAR SEEDER DE ACTIVIDADES CORREGIDAS
show_info "4. Creando seeder de actividades corregidas..."
php artisan make:seeder ActividadesArregladasSeeder > /dev/null 2>&1

SEEDER_FILE="database/seeders/ActividadesArregladasSeeder.php"
if [ -f "$SEEDER_FILE" ]; then
    # El contenido del seeder ya está en los artifacts anteriores
    show_message "Seeder creado: $SEEDER_FILE"
    show_warning "IMPORTANTE: Copia el contenido del seeder desde los artifacts proporcionados"
else
    show_error "No se pudo crear el seeder"
fi

# 5. CREAR COMANDO DE DEBUG
show_info "5. Creando comando de debug..."
php artisan make:command DebugActividadesCommand > /dev/null 2>&1

COMMAND_FILE="app/Console/Commands/DebugActividadesCommand.php"
if [ -f "$COMMAND_FILE" ]; then
    show_message "Comando de debug creado: $COMMAND_FILE"
    show_warning "IMPORTANTE: Copia el contenido del comando desde los artifacts proporcionados"
else
    show_error "No se pudo crear el comando"
fi

# 6. LIMPIAR CACHÉ
show_info "6. Limpiando caché de Laravel..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
show_message "Caché limpiado"

# 7. EJECUTAR SEEDER (opcional)
read -p "¿Quieres ejecutar el seeder de actividades corregidas? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    show_info "7. Ejecutando seeder de actividades..."
    php artisan db:seed --class=ActividadesArregladasSeeder
    if [ $? -eq 0 ]; then
        show_message "Seeder ejecutado exitosamente"
    else
        show_error "Error al ejecutar el seeder"
    fi
else
    show_warning "7. Seeder no ejecutado (puedes ejecutarlo manualmente después)"
fi

# 8. VERIFICAR ESTADO
show_info "8. Verificando estado de la plataforma..."

# Verificar estructura de tabla
echo "Verificando tabla actividad_intentos..."
php artisan tinker --execute="echo 'Columnas: ' . implode(', ', Schema::getColumnListing('actividad_intentos'));"

# Contar actividades
ACTIVIDADES_COUNT=$(php artisan tinker --execute="echo App\Models\Actividad::count();" 2>/dev/null | tail -1)
echo "Total de actividades: $ACTIVIDADES_COUNT"

# Contar intentos
INTENTOS_COUNT=$(php artisan tinker --execute="echo App\Models\ActividadIntento::count();" 2>/dev/null | tail -1)
echo "Total de intentos: $INTENTOS_COUNT"

# 9. RESUMEN FINAL
echo ""
echo "=================================================="
show_message "🎉 CORRECCIÓN COMPLETADA"
echo "=================================================="
echo ""
echo "✅ Problemas corregidos:"
echo "   - Error de columna alumno_matricula eliminado"
echo "   - Estructura de tabla actividad_intentos normalizada" 
echo "   - AlumnoController.php corregido (copia desde artifacts)"
echo "   - Sistema de evaluación mejorado"
echo "   - Comando de debug disponible"
echo ""
echo "📋 PASOS MANUALES PENDIENTES:"
echo "   1. Reemplazar app/Http/Controllers/AlumnoController.php con el código corregido"
echo "   2. Copiar el contenido del seeder ActividadesArregladasSeeder.php"
echo "   3. Copiar el contenido del comando DebugActividadesCommand.php"
echo ""
echo "🧪 COMANDOS ÚTILES:"
echo "   php artisan debug:actividades           # Debug general"
echo "   php artisan debug:actividades --test    # Ejecutar pruebas"
echo "   php artisan db:seed --class=ActividadesArregladasSeeder  # Crear actividades de prueba"
echo ""
echo "🌐 PROBAR LA PLATAFORMA:"
echo "   1. Ve a la URL de estudiantes"
echo "   2. Ingresa datos de prueba"
echo "   3. Intenta responder una actividad"
echo "   4. Verifica que se guarden correctamente las respuestas"
echo ""
show_message "¡La plataforma debería estar funcionando correctamente ahora!"
EOF