<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadIntento extends Model
{
    use HasFactory;

    protected $table = 'actividad_intentos';

    protected $fillable = [
        'actividad_id',
        'alumno_id',
        'alumno_nombre',
        'alumno_matricula',
        'numero_intento',
        'respuestas',
        'puntaje',
        'total_preguntas',
        'porcentaje',
        'tiempo_completado'
    ];

    protected $casts = [
        'respuestas' => 'array',
        'puntaje' => 'integer',
        'total_preguntas' => 'integer',
        'porcentaje' => 'decimal:2',
        'tiempo_completado' => 'integer',
        'actividad_id' => 'integer',
        'alumno_id' => 'integer',
        'numero_intento' => 'integer'
    ];

    /**
     * Relación con actividad
     */
    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    /**
     * Relación con alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Calcular porcentaje automáticamente antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($intento) {
            // Calcular porcentaje automáticamente
            if ($intento->total_preguntas > 0) {
                $intento->porcentaje = round(($intento->puntaje / $intento->total_preguntas) * 100, 2);
            } else {
                $intento->porcentaje = 0;
            }
            
            // DEBUG TEMPORAL
            \Log::info('ActividadIntento saving:', [
                'puntaje' => $intento->puntaje,
                'total_preguntas' => $intento->total_preguntas,
                'porcentaje_calculado' => $intento->porcentaje
            ]);
        });
    }

    /**
     * Obtener el resultado como texto
     */
    public function getResultadoTextoAttribute()
    {
        $porcentaje = $this->porcentaje ?? 0;

        if ($porcentaje >= 70) {
            return 'Excelente';
        } elseif ($porcentaje >= 50) {
            return 'Satisfactorio';
        } else {
            return 'Necesita Mejorar';
        }
    }

    /**
     * Obtener color según el resultado
     */
    public function getColorResultadoAttribute()
    {
        $porcentaje = $this->porcentaje ?? 0;

        if ($porcentaje >= 70) {
            return '#28a745'; // Verde
        } elseif ($porcentaje >= 50) {
            return '#ffc107'; // Amarillo
        } else {
            return '#dc3545'; // Rojo
        }
    }

    /**
     * Scope para obtener el mejor intento por actividad
     */
    public function scopeMejorIntento($query)
    {
        return $query->orderBy('puntaje', 'desc')
                    ->orderBy('tiempo_completado', 'asc');
    }

    /**
     * Scope para intentos de un alumno específico
     */
    public function scopeDeAlumno($query, $alumnoNombre)
    {
        return $query->where('alumno_nombre', $alumnoNombre);
    }
}