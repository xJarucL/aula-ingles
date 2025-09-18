<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcial extends Model
{
    use HasFactory;

    protected $table = 'parciales';

    protected $fillable = [
        'cuatrimestre_id',
        'nombre',
        'numero',
    ];

    public function cuatrimestre()
    {
        return $this->belongsTo(Cuatrimestre::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
}
