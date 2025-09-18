<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    // Relaciones
    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    // Métodos útiles
    public function materiasPorCuatrimestre($cuatrimestre)
    {
        return $this->materias()
                    ->where('cuatrimestre_numero', $cuatrimestre)
                    ->activas();
    }
}