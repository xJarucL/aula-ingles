<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Grupo;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'materia',
        'cuatrimestre',
        'alumno_nombre',
        'alumno_matricula',
        'alumno_carrera',
        'archivo_original',
        'archivo_guardado',
        'archivo_ruta',
        'archivo_size',
        'archivo_tipo',
        'calificacion',
        'comentarios',
        'estado',
        'fecha_entrega',
        'fecha_calificacion',
    'grupo_id',
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
        'fecha_calificacion' => 'datetime',
        'calificacion' => 'decimal:2',
        'archivo_size' => 'integer',
        'cuatrimestre' => 'integer',
    ];

    protected $dates = [
        'fecha_entrega',
        'fecha_calificacion',
        'created_at',
        'updated_at'
    ];

    /**
     * Estados disponibles para las tareas
     */
    const ESTADOS = [
        'pendiente' => 'Pendiente de revisión',
        'revisada' => 'Revisada por el profesor',
        'calificada' => 'Calificada',
        'rechazada' => 'Rechazada'
    ];

    /**
     * Tipos de archivo permitidos
     */
    const TIPOS_ARCHIVO_PERMITIDOS = [
        'application/pdf' => 'PDF',
        'application/msword' => 'DOC',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'DOCX',
        'text/plain' => 'TXT',
        'image/jpeg' => 'JPEG',
        'image/jpg' => 'JPG',
        'image/png' => 'PNG'
    ];

    // ============== ACCESSORS ==============

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        return self::ESTADOS[$this->estado] ?? 'No definido';
    }

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getTamañoFormateadoAttribute()
    {
        if (!$this->archivo_size) return 'N/A';
        
        $bytes = $this->archivo_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obtener la extensión del archivo
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->archivo_original, PATHINFO_EXTENSION);
    }

    /**
     * Obtener el tipo de archivo legible
     */
    public function getTipoArchivoLegibleAttribute()
    {
        return self::TIPOS_ARCHIVO_PERMITIDOS[$this->archivo_tipo] ?? 'Desconocido';
    }

    /**
     * Obtener la fecha de entrega formateada
     */
    public function getFechaEntregaFormateadaAttribute()
    {
        return $this->fecha_entrega ? $this->fecha_entrega->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Obtener la fecha de calificación formateada
     */
    public function getFechaCalificacionFormateadaAttribute()
    {
        return $this->fecha_calificacion ? $this->fecha_calificacion->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Verificar si la tarea está calificada
     */
    public function getEstaCalificadaAttribute()
    {
        return !is_null($this->calificacion) && $this->estado === 'calificada';
    }

    /**
     * Obtener el color del estado para UI
     */
    public function getColorEstadoAttribute()
    {
        return match($this->estado) {
            'pendiente' => '#ffc107',
            'revisada' => '#17a2b8', 
            'calificada' => '#28a745',
            'rechazada' => '#dc3545',
            default => '#6c757d'
        };
    }

    /**
     * Obtener el ícono del estado
     */
    public function getIconoEstadoAttribute()
    {
        return match($this->estado) {
            'pendiente' => '⏳',
            'revisada' => '👁️',
            'calificada' => '✅',
            'rechazada' => '❌',
            default => '📄'
        };
    }

    // ============== SCOPES ==============

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por matrícula de alumno
     */
    public function scopeAlumno($query, $matricula)
    {
        return $query->where('alumno_matricula', $matricula);
    }

    /**
     * Scope para filtrar por materia
     */
    public function scopeMateria($query, $materia)
    {
        return $query->where('materia', $materia);
    }

    /**
     * Scope para filtrar por cuatrimestre
     */
    public function scopeCuatrimestre($query, $cuatrimestre)
    {
        return $query->where('cuatrimestre', $cuatrimestre);
    }

    /**
     * Scope para tareas calificadas
     */
    public function scopeCalificadas($query)
    {
        return $query->where('estado', 'calificada')->whereNotNull('calificacion');
    }

    /**
     * Scope para tareas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para ordenar por fecha de entrega
     */
    public function scopeOrdenEntrega($query, $direccion = 'desc')
    {
        return $query->orderBy('fecha_entrega', $direccion);
    }

    // ============== MÉTODOS ==============

    /**
     * Obtener la URL del archivo
     */
    public function getUrlArchivo()
    {
        return asset('storage/' . $this->archivo_ruta);
    }

    /**
     * Verificar si el archivo existe
     */
    public function archivoExiste()
    {
        return \Storage::disk('public')->exists($this->archivo_ruta);
    }

    /**
     * Eliminar archivo físico
     */
    public function eliminarArchivo()
    {
        if ($this->archivoExiste()) {
            return \Storage::disk('public')->delete($this->archivo_ruta);
        }
        return true;
    }

    /**
     * Calificar la tarea
     */
    public function calificar($calificacion, $comentarios = null)
    {
        $this->update([
            'calificacion' => $calificacion,
            'comentarios' => $comentarios,
            'estado' => 'calificada',
            'fecha_calificacion' => now(),
        ]);

        return $this;
    }

    /**
     * Marcar como revisada
     */
    public function marcarRevisada($comentarios = null)
    {
        $this->update([
            'estado' => 'revisada',
            'comentarios' => $comentarios,
        ]);

        return $this;
    }

    /**
     * Rechazar la tarea
     */
    public function rechazar($comentarios)
    {
        $this->update([
            'estado' => 'rechazada',
            'comentarios' => $comentarios,
        ]);

        return $this;
    }

    /**
     * Obtener todas las tareas de un alumno
     */
    public static function tareasAlumno($matricula)
    {
        return self::where('alumno_matricula', $matricula)
                   ->orderBy('fecha_entrega', 'desc')
                   ->get();
    }

    /**
     * Obtener estadísticas de un alumno
     */
    public static function estadisticasAlumno($matricula)
    {
        $tareas = self::where('alumno_matricula', $matricula);
        
        return [
            'total' => $tareas->count(),
            'pendientes' => $tareas->clone()->where('estado', 'pendiente')->count(),
            'revisadas' => $tareas->clone()->where('estado', 'revisada')->count(),
            'calificadas' => $tareas->clone()->where('estado', 'calificada')->count(),
            'rechazadas' => $tareas->clone()->where('estado', 'rechazada')->count(),
            'promedio_calificacion' => $tareas->clone()->whereNotNull('calificacion')->avg('calificacion'),
            'ultima_entrega' => $tareas->clone()->orderBy('fecha_entrega', 'desc')->first()?->fecha_entrega,
        ];
    }

    /**
     * Obtener tareas por fecha
     */
    public static function tareasPorFecha($fechaInicio = null, $fechaFin = null)
    {
        $query = self::query();
        
        if ($fechaInicio) {
            $query->whereDate('fecha_entrega', '>=', $fechaInicio);
        }
        
        if ($fechaFin) {
            $query->whereDate('fecha_entrega', '<=', $fechaFin);
        }
        
        return $query->orderBy('fecha_entrega', 'desc')->get();
    }

    // ============== EVENTOS ==============

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear una tarea, establecer fecha de entrega si no existe
        static::creating(function ($tarea) {
            if (is_null($tarea->fecha_entrega)) {
                $tarea->fecha_entrega = now();
            }
            
            if (is_null($tarea->estado)) {
                $tarea->estado = 'pendiente';
            }
        });

        // Al eliminar una tarea, eliminar también su archivo
        static::deleting(function ($tarea) {
            $tarea->eliminarArchivo();
        });
    }

    
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_matricula', 'matricula');
    }
    
    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }
    
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
    
}