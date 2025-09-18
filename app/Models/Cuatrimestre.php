<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuatrimestre extends Model
{
    use HasFactory;

    protected $table = 'cuatrimestres';

    protected $fillable = [
        'nombre',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELACIONES
    public function parciales()
    {
        return $this->hasMany(Parcial::class)->orderBy('numero');
    }

    // SCOPES
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }
}