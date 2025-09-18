<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'materia_id',
        'periodo_escolar_id',
        'profesor_id',
        'capacidad_maxima',
        'activo',
        'descripcion'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relaciones
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function periodoEscolar()
    {
        return $this->belongsTo(PeriodoEscolar::class);
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'inscripciones')
                    ->withPivot('estado', 'calificacion_final', 'fecha_inscripcion')
                    ->wherePivot('estado', 'inscrito');
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorProfesor($query, $profesorId)
    {
        return $query->where('profesor_id', $profesorId);
    }

    // Métodos útiles
    public function getAlumnosInscritosCountAttribute()
    {
        return $this->inscripciones()->where('estado', 'inscrito')->count();
    }

    public function getLugaresDisponiblesAttribute()
    {
        return $this->capacidad_maxima - $this->alumnos_inscritos_count;
    }
}
