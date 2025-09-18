<?php

namespace App\Http\Controllers;

use App\Models\Cuatrimestre;
use App\Models\Parcial;
use App\Models\Actividad;
use App\Models\ActividadIntento;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActividadController extends Controller
{
    public function index(Request $request)
    {
        $cuatrimestres = Cuatrimestre::with('parciales')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        $cuatrimestreId = $request->get('cuatrimestre');
        $parcialId = $request->get('parcial');
        $parcialSeleccionado = null;

        // CAMBIO PRINCIPAL: Solo cargar actividades si hay un parcial seleccionado
        $actividades = collect(); // Inicialmente vacío

        if ($parcialId) {
            $queryAct = Actividad::where('parcial_id', $parcialId)
                ->where('activa', true)
                ->with('parcial.cuatrimestre')
                ->orderBy('created_at', 'desc');

            // Si se envía group_id, filtrar por grupo
            if ($request->has('grupo_id')) {
                $queryAct->where('grupo_id', $request->get('grupo_id'));
            } else {
                // Si el usuario es docente, intentar limitar por grupos que imparte
                if (auth()->check() && (auth()->user()->rol ?? '') === 'docente') {
                    $profesorId = auth()->id();
                    $queryAct->where(function($q) use ($profesorId) {
                        $q->whereNull('grupo_id')
                          ->orWhereIn('grupo_id', function($sub) use ($profesorId) {
                              $sub->select('id')->from('grupos')->where('profesor_id', $profesorId);
                          });
                    });
                }
            }

            $actividades = $queryAct->get();
            $parcialSeleccionado = Parcial::with('cuatrimestre')->find($parcialId);
        }

        return view('profesores.actividades.panel', compact(
            'cuatrimestres',
            'actividades',
            'cuatrimestreId',
            'parcialId',
            'parcialSeleccionado'
        ));
    }

    /**
     * Formulario crear actividad
     */
    public function crear()
    {
        $cuatrimestres = Cuatrimestre::with('parciales')->get();
        return view('profesores.actividades.crear', compact('cuatrimestres'));
    }

    /**
     * Guardar actividad nueva
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'parcial_id' => 'required|exists:parciales,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contenido' => 'nullable|string',
            'cuatrimestre_id' => 'required|exists:cuatrimestres,id'
        ], [
            'nombre.required' => 'El nombre de la actividad es obligatorio',
            'parcial_id.required' => 'Debe seleccionar un parcial',
            'parcial_id.exists' => 'El parcial seleccionado no existe',
            'imagen.image' => 'El archivo debe ser una imagen válida',
            'imagen.max' => 'La imagen no puede pesar más de 2MB'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor corrige los errores del formulario');
        }

        try {
            $rutaImagen = null;
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = time() . '_' . preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $archivo->getClientOriginalName());
                $rutaImagen = $archivo->storeAs('actividades', $nombreArchivo, 'public');
            }

            $contenidoArray = null;
            if ($request->contenido) {
                $contenidoRaw = $request->contenido;
                if (!is_array($contenidoRaw)) {
                    $contenidoArray = @json_decode((string)$contenidoRaw, true);
                } else {
                    $contenidoArray = $contenidoRaw;
                }
                if (!is_array($contenidoArray)) {
                    $contenidoArray = ['tipo' => 'manual', 'datos' => $contenidoRaw];
                }
            }

            $actividad = Actividad::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'parcial_id' => $request->parcial_id,
                'imagen' => $rutaImagen,
                'contenido' => $contenidoArray,
                'activa' => 1, // <--- SIEMPRE ACTIVA
            ]);

            return redirect()->route('profesores.actividades')
                ->with('success', '✅ Actividad "' . $actividad->nombre . '" creada exitosamente');
        } catch (\Exception $e) {
            if (isset($rutaImagen) && $rutaImagen) {
                Storage::disk('public')->delete($rutaImagen);
            }
            return redirect()->back()
                ->with('error', 'Error al crear la actividad: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Ver actividad específica
     */
    public function show($id, Request $request)
    {
        $actividad = Actividad::with('parcial.cuatrimestre')->findOrFail($id);
        $modo = $request->get('modo', 'vista');
        return view('profesores.actividades.ver', compact('actividad', 'modo'));
    }

    public function ver($id)
    {
        $actividad = Actividad::findOrFail($id);
        $modo = request('modo', 'normal');
        return view('profesores.ver_actividad', compact('actividad', 'modo'));
    }

    /**
     * Formulario editar actividad
     */
    public function edit($id)
    {
        $actividad = Actividad::findOrFail($id);
        if (is_array($actividad->contenido)) {
            $actividad->contenido_formateado = $actividad->contenido;
        } else {
            $actividad->contenido_formateado = @json_decode((string)$actividad->contenido, true) ?: [];
        }
        $cuatrimestres = Cuatrimestre::with('parciales')->where('activo', true)->orderBy('orden')->get();

        return view('profesores.actividades.editar', compact('actividad', 'cuatrimestres'));
    }

    /**
     * Actualizar actividad existente
     */
    public function update(Request $request, $id)
    {
        $actividad = Actividad::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'parcial_id' => 'required|exists:parciales,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contenido' => 'nullable|json',
            'eliminar_imagen' => 'nullable|boolean',
            'cuatrimestre_id' => 'required|exists:cuatrimestres,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Procesar imagen
            $rutaImagen = $actividad->imagen;
            // Eliminar imagen anterior si se pidió
            if ($request->has('eliminar_imagen') && $actividad->imagen) {
                Storage::disk('public')->delete($actividad->imagen);
                $rutaImagen = null;
            }
            // Subir nueva imagen si corresponde
            if ($request->hasFile('imagen')) {
                if ($actividad->imagen) {
                    Storage::disk('public')->delete($actividad->imagen);
                }
                $archivo = $request->file('imagen');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $rutaImagen = $archivo->storeAs('actividades', $nombreArchivo, 'public');
            }

            // Actualizar actividad
            $actividad->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'parcial_id' => $request->parcial_id,
                'imagen' => $rutaImagen,
                'contenido' => $request->contenido ? (is_array($request->contenido) ? $request->contenido : (@json_decode((string)$request->contenido, true) ?: null)) : null,
                'activa' => $request->has('activa')
            ]);

            return redirect()->route('profesores.actividades')
                ->with('success', 'Actividad actualizada exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la actividad: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar actividad
     */
    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $imagen = $actividad->imagen;

        // Elimina la actividad primero
        $actividad->delete();

        // ¿Alguna otra actividad usa la misma imagen?
        if ($imagen && !Actividad::where('imagen', $imagen)->exists()) {
            Storage::disk('public')->delete($imagen);
        }

        return response()->json([
            'success' => true,
            'message' => 'Actividad eliminada exitosamente'
        ]);
    }

    /**
     * Obtener actividades por parcial (AJAX)
     */
    public function obtenerPorParcial(Request $request)
    {
        try {
            $parcialId = $request->get('parcial_id');
            
            if (!$parcialId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID de parcial requerido'
                ], 400);
            }

            $queryAct = Actividad::where('parcial_id', $parcialId)
                ->where('activa', true)
                ->with('parcial.cuatrimestre')
                ->orderBy('created_at', 'desc');
            if ($request->has('grupo_id')) {
                $queryAct->where('grupo_id', $request->get('grupo_id'));
            }
            $actividades = $queryAct->get();

            $parcial = Parcial::with('cuatrimestre')->find($parcialId);

            return response()->json([
                'success' => true,
                'actividades' => $actividades,
                'parcial' => $parcial,
                'total' => $actividades->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener actividades por parcial: ' . $e->getMessage(), [
                'parcial_id' => $request->get('parcial_id'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar estado de actividad (activa/inactiva)
     */
    public function toggleEstado($id)
    {
        try {
            $actividad = Actividad::findOrFail($id);
            $actividad->activa = !$actividad->activa;
            $actividad->save();

            $estado = $actividad->activa ? 'activada' : 'desactivada';

            return response()->json([
                'success' => true,
                'message' => "Actividad {$estado} exitosamente",
                'activa' => $actividad->activa
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicar una actividad existente
     */
    public function duplicar($id)
    {
        try {
            $actividadOriginal = Actividad::findOrFail($id);
            $nuevaActividad = $actividadOriginal->replicate();
            $nuevaActividad->nombre = $actividadOriginal->nombre . ' (Copia)';
            $nuevaActividad->save();

            return redirect()->route('profesores.actividades')
                ->with('success', 'Actividad duplicada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al duplicar la actividad: ' . $e->getMessage());
        }
    }

    /**
     * Procesar PDF y extraer preguntas
     */
    public function procesarPdf(Request $request)
    {
        try {
            // Validar el archivo subido
            $validator = Validator::make($request->all(), [
                'archivo_pdf' => 'required|file|mimes:pdf|max:10240' // Máximo 10MB
            ], [
                'archivo_pdf.required' => 'Es necesario seleccionar un archivo PDF',
                'archivo_pdf.mimes' => 'El archivo debe ser un PDF válido',
                'archivo_pdf.max' => 'El archivo PDF no puede pesar más de 10MB'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            // Procesar el PDF aquí
            return response()->json([
                'success' => true,
                'mensaje' => 'PDF procesado exitosamente',
                'contenido' => [
                    'tipo' => 'quiz',
                    'contenido' => [
                        'preguntas' => [] // Aquí van las preguntas extraídas
                    ]
                ],
                'estadisticas' => [
                    'preguntas_encontradas' => 0,
                    'tiempo_procesamiento' => '0.5s',
                    'tamaño_archivo' => '2MB'
                ]
            ]);

        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error al procesar PDF: ' . $e->getMessage(), [
                'archivo' => $request->file('archivo_pdf') ? $request->file('archivo_pdf')->getClientOriginalName() : 'ninguno',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Evalúa las respuestas de una actividad - CORRECCIÓN COMPLETA
     */
    private function evaluarRespuestas(Actividad $actividad, array $respuestas)
    {
$contenido = $actividad->contenido_formateado;
        $puntaje = 0;
        $total = 0;
        $detalles = [];

        // Validar que existe contenido
        if (!$contenido || !is_array($contenido)) {
            \Log::warning('ActividadController - Contenido inválido', [
                'actividad_id' => $actividad->id,
                'contenido' => $contenido
            ]);
            return [
                'total' => 0,
                'correctas' => 0,
                'puntaje' => 0,
                'porcentaje' => 0
            ];
        }

        $tipo = $contenido['tipo'] ?? 'opcion_multiple';

        \Log::info('=== ACTIVIDADCONTROLLER EVALUANDO ===', [
            'actividad_id' => $actividad->id,
            'tipo' => $tipo,
            'contenido_keys' => array_keys($contenido),
            'respuestas_recibidas' => $respuestas
        ]);

        switch ($tipo) {
            case 'opcion_multiple':
            case 'quiz':
                // BÚSQUEDA EXHAUSTIVA DE PREGUNTAS
                $preguntas = null;
                
                if (isset($contenido['contenido']['preguntas']) && is_array($contenido['contenido']['preguntas'])) {
                    $preguntas = $contenido['contenido']['preguntas'];
                    \Log::info('Preguntas encontradas en contenido.contenido.preguntas');
                } elseif (isset($contenido['preguntas']) && is_array($contenido['preguntas'])) {
                    $preguntas = $contenido['preguntas'];
                    \Log::info('Preguntas encontradas en contenido.preguntas');
                }

                if ($preguntas && is_array($preguntas) && count($preguntas) > 0) {
                    $total = count($preguntas);
                    
                    \Log::info('Procesando preguntas', [
                        'total_preguntas' => $total,
                        'estructura_primera_pregunta' => array_keys($preguntas[0] ?? [])
                    ]);

                    foreach ($preguntas as $index => $pregunta) {
                        $respuestaAlumno = $respuestas[$index] ?? null;
                        
                        // BUSCAR RESPUESTA CORRECTA EN DIFERENTES FORMATOS
                        $respuestaCorrecta = null;
                        if (isset($pregunta['respuesta_correcta'])) {
                            $respuestaCorrecta = $pregunta['respuesta_correcta'];
                        } elseif (isset($pregunta['correcta'])) {
                            $respuestaCorrecta = $pregunta['correcta'];
                        }
                        
                        \Log::info("Evaluando pregunta $index", [
                            'pregunta_texto' => $pregunta['pregunta'] ?? 'Sin texto',
                            'respuesta_alumno' => $respuestaAlumno,
                            'respuesta_correcta' => $respuestaCorrecta
                        ]);
                        
                        if ($respuestaAlumno !== null && $respuestaAlumno == $respuestaCorrecta) {
                            $puntaje++;
                        }
                    }
                } else {
                    \Log::warning('No se encontraron preguntas válidas en ActividadController', [
                        'actividad_id' => $actividad->id,
                        'contenido_structure' => array_keys($contenido)
                    ]);
                }
                break;

            case 'completar':
                // BÚSQUEDA EXHAUSTIVA DE EJERCICIOS
                $ejercicios = null;
                
                if (isset($contenido['ejercicios']) && is_array($contenido['ejercicios'])) {
                    $ejercicios = $contenido['ejercicios'];
                    \Log::info('Ejercicios encontrados en contenido.ejercicios');
                } elseif (isset($contenido['frases']) && is_array($contenido['frases'])) {
                    $ejercicios = $contenido['frases'];
                    \Log::info('Ejercicios encontrados en contenido.frases');
                } elseif (isset($contenido['contenido']['ejercicios']) && is_array($contenido['contenido']['ejercicios'])) {
                    $ejercicios = $contenido['contenido']['ejercicios'];
                    \Log::info('Ejercicios encontrados en contenido.contenido.ejercicios');
                }

                if ($ejercicios && is_array($ejercicios) && count($ejercicios) > 0) {
                    $total = count($ejercicios);
                    
                    \Log::info('Procesando ejercicios de completar', [
                        'total_ejercicios' => $total,
                        'estructura_primer_ejercicio' => array_keys($ejercicios[0] ?? [])
                    ]);

                    foreach ($ejercicios as $index => $ejercicio) {
                        $respuestaAlumno = trim(strtolower($respuestas[$index] ?? ''));
                        
                        // BUSCAR RESPUESTA CORRECTA EN DIFERENTES FORMATOS
                        $respuestaCorrecta = null;
                        if (isset($ejercicio['respuesta'])) {
                            $respuestaCorrecta = trim(strtolower($ejercicio['respuesta']));
                        } elseif (isset($ejercicio['respuestas_correctas']) && is_array($ejercicio['respuestas_correctas'])) {
                            $respuestaCorrecta = trim(strtolower($ejercicio['respuestas_correctas'][0]));
                        }
                        
                        \Log::info("Evaluando ejercicio $index", [
                            'ejercicio_texto' => $ejercicio['texto'] ?? 'Sin texto',
                            'respuesta_alumno' => $respuestaAlumno,
                            'respuesta_correcta' => $respuestaCorrecta
                        ]);
                        
                        if ($respuestaAlumno === $respuestaCorrecta) {
                            $puntaje++;
                        }
                    }
                } else {
                    \Log::warning('No se encontraron ejercicios válidos para completar en ActividadController', [
                        'actividad_id' => $actividad->id,
                        'contenido_structure' => array_keys($contenido)
                    ]);
                }
                break;

            default:
                // Para otros tipos, dar puntaje completo por participación
                $total = 1;
                $puntaje = 1;
                \Log::info('Tipo de actividad no reconocido en ActividadController', [
                    'tipo' => $tipo,
                    'actividad_id' => $actividad->id
                ]);
                break;
        }

        $porcentaje = $total > 0 ? round(($puntaje / $total) * 100, 2) : 0;

        $resultado = [
            'total' => $total,
            'correctas' => $puntaje,
            'puntaje' => $puntaje,
            'porcentaje' => $porcentaje
        ];

        \Log::info('=== RESULTADO FINAL ACTIVIDADCONTROLLER ===', [
            'actividad_id' => $actividad->id,
            'puntaje' => $puntaje,
            'total' => $total,
            'porcentaje' => $porcentaje,
            'tipo' => $tipo
        ]);

        return $resultado;
    }

    /**
     * Procesar respuesta de actividad
     */
    public function procesarRespuesta(Request $request, $actividadId)
    {
        if (!Session::has('alumno_datos')) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión expirada'
            ], 401);
        }

        try {
            $alumnoData = Session::get('alumno_datos');
            $alumno = Alumno::find($alumnoData['id']);
            $actividad = Actividad::findOrFail($actividadId);
            
            if (!$alumno) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información del alumno'
                ], 404);
            }
            
            $respuestas = $request->input('respuestas', []);

            // Sanitizar respuestas: aceptar JSON strings, arrays, o valores escalares
            if (is_string($respuestas)) {
                $decoded = json_decode($respuestas, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $respuestas = $decoded;
                }
            }

            // Normalizar cada respuesta
            $sanitized = [];
            foreach ($respuestas as $k => $v) {
                // Si viene como JSON string por pregunta, intentar decodificar
                if (is_string($v)) {
                    $vTrim = trim($v);
                    if ((str_starts_with($vTrim, '[') && str_ends_with($vTrim, ']')) || (str_starts_with($vTrim, '{') && str_ends_with($vTrim, '}'))) {
                        $maybe = json_decode($vTrim, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            // Si decodea a array y tiene un solo valor, tomar el primero
                            if (is_array($maybe)) {
                                // tomar primer elemento válido
                                $first = array_values($maybe)[0] ?? null;
                                $v = $first ?? $vTrim;
                            } else {
                                $v = $maybe;
                            }
                        }
                    }
                }

                // Si viene como array (por ejemplo inputs con mismo name), tomar primer valor
                if (is_array($v)) {
                    $v = array_values($v)[0] ?? null;
                }

                // Convertir a string y limpiar
                if ($v === null) {
                    $sanitized[$k] = null;
                } else {
                    $val = (string) $v;
                    $val = trim($val);
                    // Normalizar respuestas de opciones a mayúscula simple (A, B, C...)
                    if (preg_match('/^[a-zA-Z]$/', $val)) {
                        $val = strtoupper($val);
                    }
                    $sanitized[$k] = $val;
                }
            }

            $respuestas = $sanitized;
            
            $resultado = $this->evaluarRespuestas($actividad, $respuestas);

            // Contar intentos previos del alumno para esta actividad
            $intentosPrevios = ActividadIntento::where('actividad_id', $actividadId)
    ->where('alumno_nombre', $alumno->nombre_completo ?? ($alumno->nombre . ' ' . ($alumno->apellidos ?? '')))
    ->count();

            // Verificar límite de intentos si está configurado
            if ($actividad->intentos_permitidos && $intentosPrevios >= $actividad->intentos_permitidos) {
                return response()->json([
                    'success' => false,
                    'message' => 'Has agotado el número máximo de intentos para esta actividad'
                ], 400);
            }

            // Guardar intento con alumno_id
            $intento = ActividadIntento::create([
    'actividad_id' => $actividadId,
    // 'alumno_id' => $alumno->id,  // ← COMENTADA
    'alumno_nombre' => $alumno->nombre_completo ?? ($alumno->nombre . ' ' . ($alumno->apellidos ?? '')),
    'respuestas' => $respuestas,
    'puntaje' => $resultado['puntaje'],
    'total_preguntas' => $resultado['total'],
    'porcentaje' => $resultado['porcentaje'],
    'numero_intento' => $intentosPrevios + 1,
    'tiempo_completado' => $request->input('tiempo', 0)
]);

            return response()->json([
                'success' => true,
                'resultado' => $resultado,
                'intento_id' => $intento->id,
                'numero_intento' => $intento->numero_intento,
                'intentos_restantes' => $actividad->intentos_permitidos ? 
                    ($actividad->intentos_permitidos - $intento->numero_intento) : null
            ]);

        } catch (\Exception $e) {
            Log::error('Error en procesarRespuesta: ' . $e->getMessage(), [
                'actividad_id' => $actividadId,
                'alumno_id' => $alumnoData['id'] ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar las respuestas: ' . $e->getMessage()
            ], 500);
        }
    }
}