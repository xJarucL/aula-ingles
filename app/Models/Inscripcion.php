<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'alumno_id',
        'grupo_id',
        'estado',
        'calificacion_final',
        'fecha_inscripcion',
        'fecha_retiro'
    ];

    protected $casts = [
        'calificacion_final' => 'decimal:2',
        'fecha_inscripcion' => 'datetime',
        'fecha_retiro' => 'datetime',
        'alumno_id' => 'integer',
        'grupo_id' => 'integer'
    ];

    /**
     * Relación con alumno
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Relación con grupo
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Scope para inscripciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'inscrito');
    }

    /**
     * Scope para inscripciones completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completado');
    }

    /**
     * Scope para inscripciones retiradas
     */
    public function scopeRetiradas($query)
    {
        return $query->where('estado', 'retirado');
    }

    /**
     * Verificar si la inscripción está activa
     */
    public function estaActiva()
    {
        return $this->estado === 'inscrito';
    }

    /**
     * Marcar como completada
     */
    public function completar($calificacion = null)
    {
        $this->update([
            'estado' => 'completado',
            'calificacion_final' => $calificacion
        ]);
    }

    /**
     * Marcar como retirada
     */
    public function retirar()
    {
        $this->update([
            'estado' => 'retirado',
            'fecha_retiro' => now()
        ]);
    }
}