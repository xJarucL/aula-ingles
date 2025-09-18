<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'carrera_id',
        'cuatrimestre_numero',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    // Relaciones
    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopePorCarrera($query, $carreraId)
    {
        return $query->where('carrera_id', $carreraId);
    }

    public function scopePorCuatrimestre($query, $cuatrimestre)
    {
        return $query->where('cuatrimestre_numero', $cuatrimestre);
    }
}
