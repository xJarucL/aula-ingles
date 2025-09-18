<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Cuatrimestre;
use App\Models\Parcial;
use App\Models\Actividad;
use App\Models\ActividadIntento;
use App\Models\Inscripcion;
use App\Models\ProgresoParcial;

class AlumnoController extends Controller
{
    
    public function panel()
    {
        $alumnoData = Session::get('alumno_datos');
        
        
        if ($alumnoData) {
            
            $alumno = Alumno::where('matricula', $alumnoData['matricula'])
                           ->where('activo', true)
                           ->first();
            
            if (!$alumno) {
                Session::forget('alumno_datos');
                return redirect()->route('alumnos.panel')
                    ->with('error', 'Tu cuenta no está disponible. Contacta al administrador.');
            }
            
            
            $this->actualizarDatosSesion($alumno);
        }
        
        
        $carreras = Carrera::where('activa', true)->get();
        
        return view('alumnos.panel', compact('carreras'));
    }

    /**
     * Método público para evaluar una actividad dado su contenido y respuestas
     * Retorna la misma estructura que procesarQuiz/procesarCompletar
     */
    public function evaluarActividadContenido($actividad, $respuestas)
    {
        $datos = ['respuestas' => $respuestas];
        return $this->procesarRespuestasPorTipo($actividad, $datos);
    }

    /**
     * Procesar ingreso del alumno con validaciones estrictas
     */
    public function procesarIngreso(Request $request)
    {
        try {
            
            $request->validate([
                'matricula' => 'required|string|max:20',
                'password' => 'required|string|min:4'
            ]);

            $matricula = strtoupper(trim($request->matricula));
            $password = $request->password;

            
            $alumno = Alumno::where('matricula', $matricula)->first();
            
            
            $attemptKey = 'login_attempts_' . $matricula;
            $attempts = Cache::get($attemptKey, 0);
            $lockKey = 'login_locked_' . $matricula;
            if (Cache::has($lockKey)) {
                return back()->withInput()->with('error', 'Demasiados intentos fallidos. Intenta de nuevo más tarde.');
            }

            if (!$alumno) {
                
                $alumno = $this->crearNuevoAlumno($matricula, null, $request);
            } else {
                
                if (!$alumno->activo) {
                    return back()->withInput()
                        ->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
                }

                
                if (!$alumno->password) {
                    return back()->withInput()
                        ->with('error', 'Tu cuenta no tiene contraseña establecida. Contacta al administrador.');
                }

                
                if (!\Illuminate\Support\Facades\Hash::check($password, $alumno->password)) {
                    
                    $attempts++;
                    Cache::put($attemptKey, $attempts, now()->addMinutes(15));
                    if ($attempts >= 5) {
                        Cache::put($lockKey, true, now()->addMinutes(15));
                        return back()->withInput()->with('error', 'Demasiados intentos fallidos. Intenta de nuevo en 15 minutos.');
                    }

                    return back()->withInput()->with('error', 'Contraseña incorrecta. Intentos: ' . $attempts);
                }
                
                Cache::forget($attemptKey);

                
            }

            
            $alumno->actualizarUltimoAcceso();

            
                        $carreraId = $alumno->carrera_id;
                        $cuatrimestreSeleccionado = $alumno->cuatrimestre_actual;
                        $parcialSeleccionado = $alumno->parcial_actual;

                        
                        $grupoAsignado = Grupo::whereHas('materia', function($q) use ($alumno, $cuatrimestreSeleccionado) {
                                $q->where('carrera_id', $alumno->carrera_id)
                                    ->where('cuatrimestre_numero', $cuatrimestreSeleccionado);
                        })->whereHas('inscripciones', function($q) use ($alumno) {
                                $q->where('alumno_id', $alumno->id)
                                    ->where('estado', 'inscrito');
                        })->first();

                        
                        $this->crearSesionAlumno($alumno);
                        $alumnoData = Session::get('alumno_datos');
                        $alumnoData['seleccion'] = [
                                'carrera_id' => $carreraId,
                                'cuatrimestre' => $cuatrimestreSeleccionado,
                                'parcial' => $parcialSeleccionado,
                                'grupo_id' => $grupoAsignado ? $grupoAsignado->id : null,
                                'grupo_codigo' => $grupoAsignado ? $grupoAsignado->codigo : null
                        ];
                        Session::put('alumno_datos', $alumnoData);

            
            return redirect()->route('alumnos.mis-grupos')
                ->with('success', '¡Bienvenido ' . $alumno->nombre . '!');
                
        } catch (\Exception $e) {
            Log::error('Error en procesarIngreso: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Error al procesar tu ingreso. Inténtalo de nuevo.');
        }
    }

    /**
     * Mis grupos - Dashboard principal con control estricto de acceso
     */
    public function misGrupos()
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        
        
        $alumno = Alumno::with('carrera')->find($alumnoData['id']);
        if (!$alumno || !$alumno->activo) {
            Session::forget('alumno_datos');
            return redirect()->route('alumnos.panel')
                ->with('error', 'Tu sesión ha expirado o tu cuenta no está disponible.');
        }

        
        $cuatrimestreActual = Cuatrimestre::where('orden', $alumno->cuatrimestre_actual)
            ->where('activo', true)
            ->first();
        
        if (!$cuatrimestreActual) {
            return view('alumnos.mis_grupos_simple', [
                'alumnoData' => $alumnoData,
                'parciales' => collect(),
                'cuatrimestreActual' => null,
                'error' => 'No hay cuatrimestre activo disponible para tu nivel.'
            ]);
        }

        
        $parciales = Parcial::where('cuatrimestre_id', $cuatrimestreActual->id)
            ->orderBy('numero')
            ->get();

        
        $parcialActual = $parciales->where('numero', $alumno->parcial_actual)->first();

        
        $gruposDisponibles = $this->obtenerGruposDisponibles($alumno);

        
        $progresoParcial = null;
        if ($parcialActual) {
            $progresoParcial = $this->obtenerProgresoParcial($alumno, $parcialActual);
        }
        
        return view('alumnos.mis_grupos_simple', compact(
            'alumnoData', 
            'parciales', 
            'cuatrimestreActual', 
            'parcialActual',
            'gruposDisponibles',
            'progresoParcial'
        ));
    }

    /**
     * Mostrar actividades de un parcial específico con control de acceso estricto
     */
    public function actividades($parcialId)
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);
        
        if (!$alumno || !$alumno->activo) {
            Session::forget('alumno_datos');
            return redirect()->route('alumnos.panel')
                ->with('error', 'Tu sesión ha expirado.');
        }

        
        $parcial = Parcial::with('cuatrimestre')->find($parcialId);
        
        if (!$parcial) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'Parcial no encontrado');
        }

        
        if ($parcial->cuatrimestre->orden != $alumno->cuatrimestre_actual) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'No tienes acceso a este parcial. Solo puedes ver actividades de tu cuatrimestre actual.');
        }

        
        if (!$this->puedeAccederAParcial($alumno, $parcial)) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'No puedes acceder a este parcial aún. Completa primero los parciales anteriores.');
        }

        
        $actividades = $this->obtenerActividadesParcial($parcial, $alumno);

        
        $intentos = ActividadIntento::whereIn('actividad_id', $actividades->pluck('id'))
            ->where('alumno_nombre', $alumno->nombre_completo)
            ->get()
            ->groupBy('actividad_id');

        
        $this->actualizarProgresoParcial($alumno, $parcial, $actividades, $intentos);

        return view('alumnos.actividades_simple', compact(
            'actividades', 
            'alumnoData', 
            'parcial', 
            'intentos',
            'alumno'
        ));
    }

    /**
     * Ver una actividad específica con validaciones de acceso
     */
    public function verActividad($actividadId)
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);
        
        
        $actividad = Actividad::with(['parcial.cuatrimestre'])->find($actividadId);
        
        if (!$actividad || !$actividad->activa) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'Actividad no encontrada o no disponible');
        }

        
        if (!$this->puedeAccederAActividad($alumno, $actividad)) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'No tienes acceso a esta actividad');
        }

        
        $intentosPrevios = ActividadIntento::where('actividad_id', $actividadId)
            ->where(function($query) use ($alumno) {
                $query->where('alumno_id', $alumno->id)
                      ->orWhere('alumno_nombre', $alumno->nombre_completo);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        
        $puedeIntentar = $intentosPrevios->count() < $actividad->intentos_permitidos;

        return view('alumnos.ver_actividad', compact(
            'actividad', 
            'alumnoData', 
            'intentosPrevios', 
            'puedeIntentar'
        ));
    }

    /**
     * Procesar respuesta de actividad con validaciones estrictas
     */
    public function procesarRespuesta(Request $request, $actividadId)
    {
        if (!Session::has('alumno_datos')) {
            return response()->json(['error' => 'Sesión no válida'], 403);
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);
        
        
        $actividad = Actividad::with(['parcial.cuatrimestre'])->find($actividadId);
        
        if (!$actividad || !$this->puedeAccederAActividad($alumno, $actividad)) {
            return response()->json(['error' => 'No tienes acceso a esta actividad'], 403);
        }

        
        $intentosRealizados = ActividadIntento::where('actividad_id', $actividadId)
            ->where(function($query) use ($alumno) {
                $query->where('alumno_id', $alumno->id)
                      ->orWhere('alumno_nombre', $alumno->nombre_completo);
            })
            ->count();

        if ($intentosRealizados >= $actividad->intentos_permitidos) {
            return response()->json(['error' => 'Ya has agotado tus intentos para esta actividad'], 403);
        }

        try {
            
            Log::info('procesarRespuesta - inicio', [
                'actividad_id' => $actividadId,
                'alumno_id' => $alumno->id ?? null,
                'alumno_nombre' => $alumno->nombre_completo ?? null,
                'payload' => $request->all()
            ]);
            DB::beginTransaction();

            
            $resultado = $this->procesarRespuestasPorTipo($actividad, $request->all());

            
            $intento = ActividadIntento::create([
                'actividad_id' => $actividadId,
                'alumno_id' => $alumno->id,
                'alumno_nombre' => $alumno->nombre_completo,
                'numero_intento' => $intentosRealizados + 1,
                
                'respuestas' => $resultado['respuestas'],
                'puntaje' => $resultado['puntaje'],
                'total_preguntas' => $resultado['total_preguntas'],
                'porcentaje' => $resultado['porcentaje'],
                'tiempo_completado' => $request->input('tiempo', 0)
            ]);

            
            $this->actualizarProgresoParcialIntento($alumno, $actividad, $resultado);

            DB::commit();
            
            Log::info('procesarRespuesta - resultado', [
                'intento_id' => $intento->id,
                'resultado' => $resultado,
                'intento' => [
                    'respuestas' => $intento->respuestas,
                    'puntaje' => $intento->puntaje,
                    'total_preguntas' => $intento->total_preguntas,
                    'porcentaje' => $intento->porcentaje
                ]
            ]);

            return response()->json([
                'success' => true,
                'intento_id' => $intento->id,
                'puntaje' => $resultado['puntaje'],
                'total' => $resultado['total_preguntas'],
                'porcentaje' => $resultado['porcentaje'],
                'detalles' => $resultado['detalles'] ?? []
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar respuesta: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar la respuesta'], 500);
        }
    }

    /**
     * Ver resultado de un intento específico
     */
    public function verResultado($intentoId)
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        $intento = ActividadIntento::with(['actividad.parcial.cuatrimestre'])
            ->where('id', $intentoId)
            ->where(function($query) use ($alumno) {
                $query->where('alumno_id', $alumno->id)
                      ->orWhere('alumno_nombre', $alumno->nombre_completo);
            })
            ->first();

        if (!$intento) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'Resultado no encontrado');
        }

        return view('alumnos.resultado', compact('intento', 'alumnoData'));
    }

    /**
     * Historial completo del alumno
     */
    public function historial()
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        
        $intentos = ActividadIntento::with(['actividad.parcial.cuatrimestre'])
            ->where(function($query) use ($alumno) {
                $query->where('alumno_id', $alumno->id)
                      ->orWhere('alumno_nombre', $alumno->nombre_completo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        
        $estadisticas = $this->obtenerEstadisticasAlumno($alumno);

        return view('alumnos.historial', compact('intentos', 'alumnoData', 'estadisticas'));
    }

    /**
     * Cerrar sesión del alumno
     */
    public function cerrarSesion()
    {
        Session::forget('alumno_datos');
        return redirect()->route('alumnos.panel')
            ->with('success', 'Sesión cerrada correctamente');
    }

    
    
    

    /**
     * Crear nuevo alumno con validaciones
     */
    private function crearNuevoAlumno($matricula, $carreraId, $request)
    {
        
        $info = $this->extraerInfoMatricula($matricula);
        
        $alumno = Alumno::create([
            'nombre' => $request->input('nombre', 'Estudiante'),
            'apellidos' => $request->input('apellidos', ''),
            'matricula' => $matricula,
            'email' => $this->generarEmailAlumno($matricula),
            'carrera_id' => $carreraId,
            'cuatrimestre_actual' => $info['cuatrimestre_estimado'],
            'parcial_actual' => 1,
            'activo' => true
        ]);

        return $alumno;
    }

    /**
     * Extraer información de la matrícula
     */
    private function extraerInfoMatricula($matricula)
    {
        
        $year = substr($matricula, 0, 4);
        $numero = substr($matricula, 4);
        
        
        $yearActual = date('Y');
        $cuatrimestreEstimado = max(1, ($yearActual - $year) * 3 + 1);
        $cuatrimestreEstimado = min(9, $cuatrimestreEstimado); 
        
        return [
            'year' => $year,
            'numero' => $numero,
            'cuatrimestre_estimado' => $cuatrimestreEstimado
        ];
    }

    /**
     * Generar email para el alumno
     */
    private function generarEmailAlumno($matricula)
    {
        return strtolower($matricula) . '@alumno.utesc.edu.mx';
    }

    /**
     * Crear sesión completa del alumno
     */
    private function crearSesionAlumno($alumno)
    {
        Session::put('alumno_datos', [
            'id' => $alumno->id,
            'nombre' => $alumno->nombre_completo,
            'matricula' => $alumno->matricula,
            'carrera' => $alumno->carrera->nombre,
            'carrera_id' => $alumno->carrera_id,
            'cuatrimestre' => $alumno->cuatrimestre_actual,
            'parcial' => $alumno->parcial_actual,
            'email' => $alumno->email,
            'ultimo_acceso' => now()->toDateTimeString()
        ]);
    }

    /**
     * Actualizar datos de sesión
     */
    private function actualizarDatosSesion($alumno)
    {
        $alumnoData = Session::get('alumno_datos');
        $alumnoData['cuatrimestre'] = $alumno->cuatrimestre_actual;
        $alumnoData['parcial'] = $alumno->parcial_actual;
        $alumnoData['ultimo_acceso'] = now()->toDateTimeString();
        Session::put('alumno_datos', $alumnoData);
    }

    /**
     * Verificar si el alumno puede acceder a un parcial específico
     */
    private function puedeAccederAParcial($alumno, $parcial)
    {
        
        return $parcial->numero <= $alumno->parcial_actual;
    }

    /**
     * Verificar si el alumno puede acceder a una actividad específica
     */
    private function puedeAccederAActividad($alumno, $actividad)
    {
        
        if (!$actividad->activa) {
            return false;
        }

        
        if ($actividad->parcial->cuatrimestre->orden != $alumno->cuatrimestre_actual) {
            return false;
        }

        
        if (!$this->puedeAccederAParcial($alumno, $actividad->parcial)) {
            return false;
        }

        
        if ($actividad->fecha_disponible && $actividad->fecha_disponible > now()) {
            return false;
        }

        if ($actividad->fecha_limite && $actividad->fecha_limite < now()) {
            return false;
        }

        return true;
    }

    /**
     * Obtener grupos disponibles para el alumno
     */
    private function obtenerGruposDisponibles($alumno)
    {
        return Grupo::with(['materia', 'periodoEscolar'])
            ->whereHas('materia', function($query) use ($alumno) {
                $query->where('carrera_id', $alumno->carrera_id)
                      ->where('cuatrimestre_numero', $alumno->cuatrimestre_actual);
            })
            ->where('activo', true)
            ->get();
    }

    /**
     * Obtener actividades de un parcial para el alumno
     */
    private function obtenerActividadesParcial($parcial, $alumno)
    {
        return Actividad::where('parcial_id', $parcial->id)
            ->where('activa', true)
            ->where(function($query) {
                
                $query->whereNull('fecha_limite')
                      ->orWhere('fecha_limite', '>=', now());
            })
            ->with(['parcial.cuatrimestre'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtener progreso del alumno en un parcial
     */
    private function obtenerProgresoParcial($alumno, $parcial)
    {
        return ProgresoParcial::firstOrCreate([
            'alumno_id' => $alumno->id,
            'parcial_id' => $parcial->id
        ], [
            'cuatrimestre_id' => $parcial->cuatrimestre_id,
            'fecha_inicio' => now()
        ]);
    }

    /**
     * Procesar respuestas según el tipo de actividad
     */
    private function procesarRespuestasPorTipo($actividad, $datos)
    {
        $contenido = $actividad->contenido;
        $tipo = $contenido['tipo'] ?? 'quiz';
        
        switch ($tipo) {
            case 'quiz':
                return $this->procesarQuiz($contenido, $datos);
            case 'completar':
                return $this->procesarCompletar($contenido, $datos);
            case 'listening':
                return $this->procesarListening($contenido, $datos);
            default:
                return $this->procesarQuiz($contenido, $datos);
        }
    }

    /**
     * Procesar respuestas de quiz
     */
    private function procesarQuiz($contenido, $datos)
    {
        
        $preguntas = [];
        if (is_array($contenido)) {
            if (isset($contenido['contenido']['preguntas']) && is_array($contenido['contenido']['preguntas'])) {
                $preguntas = $contenido['contenido']['preguntas'];
            } elseif (isset($contenido['preguntas']) && is_array($contenido['preguntas'])) {
                $preguntas = $contenido['preguntas'];
            }
        }

        $respuestas = $datos['respuestas'] ?? [];

        
        foreach ($respuestas as $k => $v) {
            
            if (is_array($v) && count($v) > 0) {
                $respuestas[$k] = trim((string) $v[0]);
                continue;
            }

            
            if (is_string($v)) {
                $maybe = json_decode($v, true);
                if (json_last_error() === JSON_ERROR_NONE && ($maybe !== null)) {
                    if (is_array($maybe) && count($maybe) > 0) {
                        $respuestas[$k] = trim((string) array_values($maybe)[0]);
                        continue;
                    }
                }
                $respuestas[$k] = trim($v);
            }
        }

        
        $puntaje = 0;
        $total = count($preguntas);

    $detalles = [];
    foreach ($preguntas as $index => $pregunta) {
            $respuestaAlumnoRaw = $respuestas[$index] ?? '';
            $respuestaCorrectaRaw = $pregunta['correcta'] ?? $pregunta['respuesta_correcta'] ?? '';

            
            $rawOpciones = is_array($pregunta['opciones'] ?? null) ? $pregunta['opciones'] : [];
            $opciones = [];
            $letters = range('A', 'Z');
            $idx = 0;
            foreach ($rawOpciones as $k => $v) {
                
                if (is_string($k) && strlen(trim($k)) === 1 && ctype_alpha($k)) {
                    $key = strtoupper(trim($k));
                } else {
                    $key = $letters[$idx] ?? (string)$idx;
                    $idx++;
                }
                $opciones[$key] = $v;
            }

            
            $raKey = null;
            $raVal = is_string($respuestaAlumnoRaw) ? trim($respuestaAlumnoRaw) : (string)$respuestaAlumnoRaw;
            $raUpper = strtoupper($raVal);

            
            if ($raVal !== '' && isset($opciones[$raUpper])) {
                $raKey = $raUpper;
            }

            
            if ($raKey === null && $raVal !== '') {
                foreach ($opciones as $k => $v) {
                    if (mb_strtolower(trim($v)) === mb_strtolower($raVal)) {
                        $raKey = (string)$k;
                        break;
                    }
                }
            }

            
            $rcKey = null;
            $rcVal = is_string($respuestaCorrectaRaw) ? trim($respuestaCorrectaRaw) : (string)$respuestaCorrectaRaw;
            $rcUpper = strtoupper($rcVal);

            if ($rcVal !== '' && isset($opciones[$rcUpper])) {
                $rcKey = $rcUpper;
            } else {
                foreach ($opciones as $k => $v) {
                    if (mb_strtolower(trim($v)) === mb_strtolower($rcVal)) {
                        $rcKey = (string)$k;
                        break;
                    }
                }
            }

            
            $isCorrect = false;
            if ($raKey !== null && $rcKey !== null) {
                if (strtoupper($raKey) === strtoupper($rcKey)) {
                    $isCorrect = true;
                }
            } elseif ($raVal !== '' && $rcVal !== '') {
                if (mb_strtolower($raVal) === mb_strtolower($rcVal)) {
                    $isCorrect = true;
                }
            }

            if ($isCorrect) {
                $puntaje++;
            }

            
            $detalles[] = [
                'index' => $index,
                'pregunta' => $pregunta['pregunta'] ?? null,
                'opciones' => $opciones,
                'respuesta_alumno_raw' => $respuestaAlumnoRaw,
                'respuesta_alumno_key' => $raKey,
                'respuesta_alumno_text' => ($raKey && isset($opciones[$raKey])) ? $opciones[$raKey] : ($respuestaAlumnoRaw !== null ? (string)$respuestaAlumnoRaw : null),
                'respuesta_correcta_raw' => $respuestaCorrectaRaw,
                'respuesta_correcta_key' => $rcKey,
                'respuesta_correcta_text' => ($rcKey && isset($opciones[$rcKey])) ? $opciones[$rcKey] : ($respuestaCorrectaRaw !== null ? (string)$respuestaCorrectaRaw : null),
                'correcta' => $isCorrect
            ];
        }

        $porcentaje = $total > 0 ? round(($puntaje / $total) * 100, 2) : 0;

        return [
            'respuestas' => $respuestas,
            'puntaje' => $puntaje,
            'total_preguntas' => $total,
            'porcentaje' => $porcentaje,
            'detalles' => $detalles
        ];
    }

    /**
     * Procesar respuestas de completar
     */
    private function procesarCompletar($contenido, $datos)
    {
        $ejercicios = $contenido['contenido']['ejercicios'] ?? [];
        $respuestas = $datos['respuestas'] ?? [];
        
        $puntaje = 0;
        $total = count($ejercicios);
        
        foreach ($ejercicios as $index => $ejercicio) {
            $respuestaAlumno = trim($respuestas[$index] ?? '');
            $respuestaCorrecta = trim($ejercicio['respuesta'] ?? '');
            
            if (strtolower($respuestaAlumno) === strtolower($respuestaCorrecta)) {
                $puntaje++;
            }
        }
        
        $porcentaje = $total > 0 ? round(($puntaje / $total) * 100, 2) : 0;
        
        return [
            'respuestas' => $respuestas,
            'puntaje' => $puntaje,
            'total_preguntas' => $total,
            'porcentaje' => $porcentaje
        ];
    }

    /**
     * Procesar respuestas de listening
     */
    private function procesarListening($contenido, $datos)
    {
        
        return $this->procesarQuiz($contenido, $datos);
    }

    /**
     * Actualizar progreso del parcial después de un intento
     */
    private function actualizarProgresoParcialIntento($alumno, $actividad, $resultado)
    {
        $progreso = ProgresoParcial::firstOrCreate([
            'alumno_id' => $alumno->id,
            'parcial_id' => $actividad->parcial_id
        ], [
            'cuatrimestre_id' => $actividad->parcial->cuatrimestre_id,
            'fecha_inicio' => now()
        ]);

        
        $totalActividades = Actividad::where('parcial_id', $actividad->parcial_id)
            ->where('activa', true)
            ->count();

        $actividadesCompletadas = ActividadIntento::whereHas('actividad', function($query) use ($actividad) {
                $query->where('parcial_id', $actividad->parcial_id);
            })
            ->where('alumno_id', $alumno->id)
            ->distinct('actividad_id')
            ->count();

        $promedioCalificaciones = ActividadIntento::whereHas('actividad', function($query) use ($actividad) {
                $query->where('parcial_id', $actividad->parcial_id);
            })
            ->where('alumno_id', $alumno->id)
            ->avg('porcentaje');

        $progreso->update([
            'total_actividades' => $totalActividades,
            'actividades_completadas' => $actividadesCompletadas,
            'promedio_calificaciones' => $promedioCalificaciones
        ]);
    }

    /**
     * Actualizar progreso general del parcial
     */
    private function actualizarProgresoParcial($alumno, $parcial, $actividades, $intentos)
    {
        $progreso = $this->obtenerProgresoParcial($alumno, $parcial);
        
        $totalActividades = $actividades->count();
        $actividadesCompletadas = $intentos->keys()->count();
        
        $calificaciones = $intentos->map(function($intentosActividad) {
            return $intentosActividad->max('porcentaje');
        })->filter();
        
        $promedioCalificaciones = $calificaciones->avg();
        
        $progreso->update([
            'total_actividades' => $totalActividades,
            'actividades_completadas' => $actividadesCompletadas,
            'promedio_calificaciones' => $promedioCalificaciones
        ]);
    }

    /**
     * Obtener estadísticas generales del alumno
     */
    private function obtenerEstadisticasAlumno($alumno)
    {
        $totalIntentos = ActividadIntento::where('alumno_id', $alumno->id)->count();
        $promedioGeneral = ActividadIntento::where('alumno_id', $alumno->id)->avg('porcentaje');
        $actividadesCompletadas = ActividadIntento::where('alumno_id', $alumno->id)
            ->distinct('actividad_id')
            ->count();

        return [
            'total_intentos' => $totalIntentos,
            'promedio_general' => round($promedioGeneral, 2),
            'actividades_completadas' => $actividadesCompletadas
        ];
    }

    
    
    
    

    /**
     * Actividades de grupo (compatibilidad)
     */
    public function actividadesGrupo($grupoId)
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);
        
        
        $cuatrimestreActual = Cuatrimestre::where('orden', $alumno->cuatrimestre_actual)
            ->where('activo', true)
            ->first();
            
        if ($cuatrimestreActual) {
            $parcialActual = Parcial::where('cuatrimestre_id', $cuatrimestreActual->id)
                ->where('numero', $alumno->parcial_actual)
                ->first();
                
            if ($parcialActual) {
                return redirect()->route('alumnos.actividades', $parcialActual->id);
            }
        }
        
        return redirect()->route('alumnos.mis-grupos')
            ->with('error', 'No se pudo acceder a las actividades del grupo');
    }

    /**
     * Inscribirse a un grupo
     */
    public function inscribirseGrupo(Request $request, $grupoId)
    {
        if (!Session::has('alumno_datos')) {
            return response()->json(['error' => 'Sesión no válida'], 403);
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);
        
        $grupo = Grupo::find($grupoId);
        if (!$grupo || !$grupo->activo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        
        if ($grupo->materia->cuatrimestre_numero != $alumno->cuatrimestre_actual) {
            return response()->json(['error' => 'Este grupo no es para tu cuatrimestre'], 403);
        }

        try {
            Inscripcion::updateOrCreate([
                'alumno_id' => $alumno->id,
                'grupo_id' => $grupoId
            ], [
                'estado' => 'inscrito',
                'fecha_inscripcion' => now(),
                'cuatrimestre_inscripcion' => $alumno->cuatrimestre_actual,
                'parcial_inscripcion' => $alumno->parcial_actual
            ]);

            return response()->json(['success' => true, 'message' => 'Inscripción exitosa']);

        } catch (\Exception $e) {
            Log::error('Error en inscripción: ' . $e->getMessage());
            return response()->json(['error' => 'Error al inscribirse'], 500);
        }
    }

    /**
     * Retirarse de un grupo
     */
    public function retirarseGrupo(Request $request, $grupoId)
    {
        if (!Session::has('alumno_datos')) {
            return response()->json(['error' => 'Sesión no válida'], 403);
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        try {
            $inscripcion = Inscripcion::where('alumno_id', $alumno->id)
                ->where('grupo_id', $grupoId)
                ->first();

            if ($inscripcion) {
                $inscripcion->update(['estado' => 'retirado']);
                return response()->json(['success' => true, 'message' => 'Te has retirado del grupo']);
            }

            return response()->json(['error' => 'No estás inscrito en este grupo'], 404);

        } catch (\Exception $e) {
            Log::error('Error al retirarse: ' . $e->getMessage());
            return response()->json(['error' => 'Error al retirarse del grupo'], 500);
        }
    }

    /**
     * Ver progreso de un grupo (wrapper simple)
     */
    public function progresoGrupo($grupoId)
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        $grupo = Grupo::with(['materia', 'periodoEscolar'])->find($grupoId);
        if (!$grupo) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'Grupo no encontrado');
        }

        
        $cuatrimestreActual = Cuatrimestre::where('orden', $alumno->cuatrimestre_actual)
            ->where('activo', true)
            ->first();

        $parciales = collect();
        if ($cuatrimestreActual) {
            $parciales = Parcial::where('cuatrimestre_id', $cuatrimestreActual->id)->orderBy('numero')->get();
        }

        return view('alumnos.mis_grupos_simple', compact('alumnoData', 'parciales'))
            ->with('grupoSeleccionado', $grupoId);
    }

    /**
     * Actualizar perfil básico del alumno (compatibilidad)
     */
    public function actualizarPerfil(Request $request)
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        if (!$alumno) {
            Session::forget('alumno_datos');
            return redirect()->route('alumnos.panel')
                ->with('error', 'Alumno no encontrado');
        }

        $request->validate([
            'nombre' => 'nullable|string|max:100',
            'apellidos' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150'
        ]);

        $alumno->update($request->only(['nombre', 'apellidos', 'email']));

        
        $this->actualizarDatosSesion($alumno);

        return redirect()->back()->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Mostrar actividades por cuatrimestre (compatibilidad)
     */
    public function actividadesCuatrimestre($cuatrimestreId)
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        $cuatrimestre = Cuatrimestre::find($cuatrimestreId);
        if (!$cuatrimestre) {
            return redirect()->route('alumnos.mis-grupos')
                ->with('error', 'Cuatrimestre no encontrado');
        }

        
        $parciales = Parcial::where('cuatrimestre_id', $cuatrimestre->id)->orderBy('numero')->get();

        return view('alumnos.actividades_parcial', compact('parciales', 'alumnoData', 'cuatrimestre'));
    }

    /**
     * Estadísticas del alumno (mostradas en JSON o vista según petición)
     */
    public function estadisticas()
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes ingresar tus datos primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        
        $estadisticas = $this->obtenerEstadisticasAlumno($alumno);

        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'estadisticas' => $estadisticas]);
        }

        return view('alumnos.historial', ['estadisticas' => $estadisticas, 'alumnoData' => $alumnoData, 'intentos' => collect()]);
    }
}