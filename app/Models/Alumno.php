<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alumno extends Model
{
    use HasFactory;
    protected $table = 'alumnos';


    protected $fillable = [
        'nombre',
        'apellidos',
        'matricula',
        'email',
    'password',
        'carrera_id',
        'cuatrimestre_actual',
        'parcial_actual',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_acceso' => 'datetime',
        'cuatrimestre_actual' => 'integer',
        'parcial_actual' => 'integer'
    ];



    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'inscripciones')
                    ->withPivot('estado', 'calificacion_final', 'fecha_inscripcion', 'cuatrimestre_inscripcion', 'parcial_inscripcion')
                    ->wherePivot('estado', 'inscrito');
    }

    public function intentos(): HasMany
    {
        return $this->hasMany(ActividadIntento::class);
    }

    public function progresosParciales(): HasMany
    {
        return $this->hasMany(ProgresoParcial::class);
    }

    // ===============================
    // ACCESSORS
    // ===============================

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellidos}");
    }

    public function getMatriculaFormateadaAttribute(): string
    {
        // Formato: YYYY-######
        return substr($this->matricula, 0, 4) . '-' . substr($this->matricula, 4);
    }

    public function getEmailGeneradoAttribute(): string
    {
        if ($this->email) {
            return $this->email;
        }
        return strtolower($this->matricula) . '@alumno.utesc.edu.mx';
    }

    public function getAñoIngresoAttribute(): int
    {
        return (int) substr($this->matricula, 0, 4);
    }

    public function getNumeroMatriculaAttribute(): string
    {
        return substr($this->matricula, 4);
    }

    // ===============================
    // SCOPES
    // ===============================

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorCarrera($query, $carreraId)
    {
        return $query->where('carrera_id', $carreraId);
    }

    public function scopePorCuatrimestre($query, $cuatrimestre)
    {
        return $query->where('cuatrimestre_actual', $cuatrimestre);
    }

    public function scopePorParcial($query, $parcial)
    {
        return $query->where('parcial_actual', $parcial);
    }

    public function scopePorMatricula($query, $matricula)
    {
        return $query->where('matricula', strtoupper(trim($matricula)));
    }

    public function scopeConAccesoReciente($query, $dias = 30)
    {
        return $query->where('ultimo_acceso', '>=', now()->subDays($dias));
    }

    // Scope para obtener alumnos con carreras activas (CORREGIDO)
    public function scopeConCarreraActiva($query)
    {
        return $query->whereHas('carrera', function($q) {
            $q->where('activa', true); // Usar 'activa' en lugar de 'activo'
        });
    }

    // ===============================
    // MÉTODOS DE CONTROL DE ACCESO
    // ===============================

    /**
     * Verificar si el alumno puede acceder a un cuatrimestre específico
     */
    public function puedeAccederACuatrimestre(int $cuatrimestre): bool
    {
        // Solo puede acceder a su cuatrimestre actual y anteriores
        return $cuatrimestre <= $this->cuatrimestre_actual;
    }

    /**
     * Verificar si el alumno puede acceder a un parcial específico
     */
    public function puedeAccederAParcial(int $cuatrimestre, int $parcial): bool
    {
        // Si es un cuatrimestre anterior, puede acceder a todos los parciales
        if ($cuatrimestre < $this->cuatrimestre_actual) {
            return true;
        }

        // Si es su cuatrimestre actual, solo hasta su parcial actual
        if ($cuatrimestre == $this->cuatrimestre_actual) {
            return $parcial <= $this->parcial_actual;
        }

        // No puede acceder a cuatrimestres futuros
        return false;
    }

    /**
     * Verificar si puede acceder a una actividad específica
     */
    public function puedeAccederAActividad(Actividad $actividad): bool
    {
        // Verificar que la actividad esté activa
        if (!$actividad->activa) {
            return false;
        }

        // Obtener información del parcial y cuatrimestre de la actividad
        $parcial = $actividad->parcial;
        $cuatrimestre = $parcial->cuatrimestre;

        // Verificar acceso al cuatrimestre y parcial
        if (!$this->puedeAccederAParcial($cuatrimestre->orden, $parcial->numero)) {
            return false;
        }

        // Verificar fechas de disponibilidad
        if ($actividad->fecha_disponible && $actividad->fecha_disponible > now()) {
            return false;
        }

        if ($actividad->fecha_limite && $actividad->fecha_limite < now()) {
            return false;
        }

        return true;
    }

    // ===============================
    // MÉTODOS DE PROGRESO ACADÉMICO
    // ===============================

    /**
     * Obtener grupos activos del alumno en su cuatrimestre actual
     */
    public function gruposActuales()
    {
        return $this->grupos()
                    ->whereHas('materia', function($query) {
                        $query->where('cuatrimestre_numero', $this->cuatrimestre_actual);
                    })
                    ->whereHas('periodoEscolar', function($query) {
                        $query->where('activo', true);
                    });
    }

    /**
     * Obtener el progreso en el parcial actual
     */
    public function progresoParacialActual()
    {
        $cuatrimestre = Cuatrimestre::where('orden', $this->cuatrimestre_actual)
                                  ->where('activo', true)
                                  ->first();

        if (!$cuatrimestre) {
            return null;
        }

        $parcial = Parcial::where('cuatrimestre_id', $cuatrimestre->id)
                         ->where('numero', $this->parcial_actual)
                         ->first();

        if (!$parcial) {
            return null;
        }

        return $this->progresosParciales()
                   ->where('parcial_id', $parcial->id)
                   ->first();
    }

    /**
     * Obtener estadísticas generales del alumno
     */
    public function obtenerEstadisticas(): array
    {
        $totalIntentos = $this->intentos()->count();
        $promedioGeneral = $this->intentos()->avg('porcentaje') ?? 0;
        $actividadesCompletadas = $this->intentos()->distinct('actividad_id')->count();

        $mejorCalificacion = $this->intentos()->max('porcentaje') ?? 0;
        $ultimaActividad = $this->intentos()->latest()->first();

        return [
            'total_intentos' => $totalIntentos,
            'promedio_general' => round($promedioGeneral, 2),
            'actividades_completadas' => $actividadesCompletadas,
            'mejor_calificacion' => $mejorCalificacion,
            'ultima_actividad' => $ultimaActividad ? $ultimaActividad->created_at : null,
            'parciales_completados' => $this->progresosParciales()->whereNotNull('fecha_completado')->count()
        ];
    }

    /**
     * Avanzar al siguiente parcial
     */
    public function avanzarParcial(): bool
    {
        $cuatrimestreActual = Cuatrimestre::where('orden', $this->cuatrimestre_actual)
                                        ->where('activo', true)
                                        ->first();

        if (!$cuatrimestreActual) {
            return false;
        }

        $siguienteParcial = Parcial::where('cuatrimestre_id', $cuatrimestreActual->id)
                                  ->where('numero', $this->parcial_actual + 1)
                                  ->first();

        if ($siguienteParcial) {
            $this->update(['parcial_actual' => $siguienteParcial->numero]);
            return true;
        }

        return false;
    }

    /**
     * Avanzar al siguiente cuatrimestre
     */
    public function avanzarCuatrimestre(): bool
    {
        $siguienteCuatrimestre = Cuatrimestre::where('orden', $this->cuatrimestre_actual + 1)
                                           ->where('activo', true)
                                           ->first();

        if ($siguienteCuatrimestre) {
            $this->update([
                'cuatrimestre_actual' => $siguienteCuatrimestre->orden,
                'parcial_actual' => 1 // Reiniciar al primer parcial
            ]);
            return true;
        }

        return false;
    }

    // ===============================
    // MÉTODOS ÚTILES
    // ===============================

    /**
     * Actualizar último acceso del alumno
     */
    public function actualizarUltimoAcceso(): void
    {
        $this->update(['ultimo_acceso' => now()]);
    }

    /**
     * Marcar como inactivo
     */
    public function desactivar(): void
    {
        $this->update(['activo' => false]);
    }

    /**
     * Reactivar alumno
     */
    public function reactivar(): void
    {
        $this->update(['activo' => true]);
    }

    /**
     * Obtener actividades disponibles para el alumno
     */
    public function actividadesDisponibles()
    {
        $cuatrimestre = Cuatrimestre::where('orden', $this->cuatrimestre_actual)
                                  ->where('activo', true)
                                  ->first();

        if (!$cuatrimestre) {
            return collect();
        }

        return Actividad::whereHas('parcial', function($query) use ($cuatrimestre) {
                    $query->where('cuatrimestre_id', $cuatrimestre->id)
                          ->where('numero', '<=', $this->parcial_actual);
                })
                ->where('activa', true)
                ->where(function($query) {
                    $query->whereNull('fecha_disponible')
                          ->orWhere('fecha_disponible', '<=', now());
                })
                ->where(function($query) {
                    $query->whereNull('fecha_limite')
                          ->orWhere('fecha_limite', '>=', now());
                })
                ->with(['parcial.cuatrimestre'])
                ->orderBy('created_at', 'desc')
                ->get();
    }

    /**
     * Verificar si la matrícula tiene formato válido
     */
    public static function matriculaValida(string $matricula): bool
    {
        // Formato esperado: YYYY###### (4 dígitos año + hasta 6 dígitos número)
        return (bool) preg_match('/^[0-9]{4}[0-9]{1,6}$/', $matricula);
    }

    /**
     * Generar matrícula automática para un año específico
     */
    public static function generarMatricula(int $año = null): string
    {
        $año = $año ?? date('Y');

        // Buscar la última matrícula del año
        $ultimaMatricula = self::where('matricula', 'like', $año . '%')
                              ->orderBy('matricula', 'desc')
                              ->first();

        if ($ultimaMatricula) {
            $ultimoNumero = (int) substr($ultimaMatricula->matricula, 4);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return $año . sprintf('%06d', $nuevoNumero);
    }
}
