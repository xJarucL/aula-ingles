<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoEscolar extends Model
{
    use HasFactory;

    protected $table = 'periodos_escolares';

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public function scopeAcademicos($query)
    {
        return $query->where('tipo', 'academico');
    }

    // Métodos estáticos
    public static function actual()
    {
        return static::where('activo', true)->first();
    }

    // Métodos útiles
    public function estaEnCurso()
    {
        $hoy = now()->toDateString();
        return $hoy >= $this->fecha_inicio && $hoy <= $this->fecha_fin;
    }
}