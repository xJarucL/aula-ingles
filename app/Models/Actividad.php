<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grupo;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'parcial_id',
        'contenido',
        'activa'
    ];

    protected $guarded = [];


    protected $casts = [
        'contenido' => 'array',
        'activa' => 'boolean'
    ];

    // RELACIONES

    public function parcial()
    {
        return $this->belongsTo(Parcial::class);
    }

    // === SCOPES NUEVOS ===

    public function scopeParcial($query, $parcialId)
    {
        return $query->where('parcial_id', $parcialId);
    }

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    // === ACCESSORS CORREGIDOS ===

    /**
     * Obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            $rutaCompleta = storage_path('app/public/' . $this->imagen);
            if (file_exists($rutaCompleta)) {
                return asset('storage/' . $this->imagen);
            }
        }
        return null;
    }

    /**
     * Obtener el contenido formateado
     */
    public function getContenidoFormateadoAttribute()
{
    // Si ya es array (por el cast)
    if (is_array($this->contenido)) {
        return $this->contenido;
    }

    // Si es string, intentar decodificar JSON (asegurar que no pasamos un array a json_decode)
    if (!is_array($this->contenido) && !empty($this->contenido)) {
        $asString = (string) $this->contenido;
        $decoded = @json_decode($asString, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
    }

    // Si nada es válido, devolver estructura segura
    return [
        'tipo' => 'manual',
        'preguntas' => [],
    ];
}


    /**
     * Obtener nombre legible del cuatrimestre (vía relación)
     */
    public function getNombreCuatrimestreAttribute()
    {
        return $this->parcial && $this->parcial->cuatrimestre
            ? $this->parcial->cuatrimestre->nombre
            : 'Desconocido';
    }

    /**
     * Obtener nombre legible del parcial (vía relación)
     */
    public function getNombreParcialAttribute()
    {
        return $this->parcial
            ? $this->parcial->nombre
            : 'Desconocido';
    }
}
