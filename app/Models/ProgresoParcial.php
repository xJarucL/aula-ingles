<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresoParcial extends Model
{
    use HasFactory;

    protected $table = 'progreso_parciales';

    protected $fillable = [
        'alumno_id',
        'cuatrimestre_id',
        'parcial_id',
        'actividades_completadas',
        'total_actividades',
        'promedio_calificaciones',
        'fecha_inicio',
        'fecha_completado',
        'activo'
    ];

    protected $casts = [
        'promedio_calificaciones' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_completado' => 'date',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function cuatrimestre(): BelongsTo
    {
        return $this->belongsTo(Cuatrimestre::class);
    }

    public function parcial(): BelongsTo
    {
        return $this->belongsTo(Parcial::class);
    }

    // Accessors
    public function getPorcentajeCompletoAttribute()
    {
        if ($this->total_actividades == 0) {
            return 0;
        }
        
        return round(($this->actividades_completadas / $this->total_actividades) * 100, 2);
    }

    public function getEstaCompletoAttribute()
    {
        return $this->actividades_completadas >= $this->total_actividades && $this->total_actividades > 0;
    }

    public function getCalificacionFinalAttribute()
    {
        return $this->promedio_calificaciones ?? 0;
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorAlumno($query, $alumnoId)
    {
        return $query->where('alumno_id', $alumnoId);
    }

    public function scopePorCuatrimestre($query, $cuatrimestreId)
    {
        return $query->where('cuatrimestre_id', $cuatrimestreId);
    }

    public function scopePorParcial($query, $parcialId)
    {
        return $query->where('parcial_id', $parcialId);
    }

    // Métodos útiles
    public function marcarComoCompletado()
    {
        $this->update([
            'fecha_completado' => now(),
            'activo' => false
        ]);
    }

    public function actualizarProgreso($actividadesCompletadas = null, $totalActividades = null, $promedio = null)
    {
        $datos = [];
        
        if ($actividadesCompletadas !== null) {
            $datos['actividades_completadas'] = $actividadesCompletadas;
        }
        
        if ($totalActividades !== null) {
            $datos['total_actividades'] = $totalActividades;
        }
        
        if ($promedio !== null) {
            $datos['promedio_calificaciones'] = $promedio;
        }

        // Si se completaron todas las actividades, marcar como completado
        if (isset($datos['actividades_completadas']) && isset($datos['total_actividades'])) {
            if ($datos['actividades_completadas'] >= $datos['total_actividades'] && $datos['total_actividades'] > 0) {
                $datos['fecha_completado'] = now();
            }
        }

        $this->update($datos);
    }
}