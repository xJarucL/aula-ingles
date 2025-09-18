<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Alumno;
use App\Models\Actividad;
use App\Models\Parcial;
use App\Models\Cuatrimestre;
use Symfony\Component\HttpFoundation\Response;

class AccesoAlumnoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permisos): Response
    {
        // Verificar si hay sesión activa de alumno
        if (!Session::has('alumno_datos')) {
            return $this->redirectToLogin($request, 'Debes iniciar sesión como alumno primero');
        }

        $alumnoData = Session::get('alumno_datos');
        
        // Verificar que el alumno aún existe y está activo
        $alumno = Alumno::find($alumnoData['id']);
        if (!$alumno || !$alumno->activo) {
            Session::forget('alumno_datos');
            return $this->redirectToLogin($request, 'Tu cuenta no está disponible');
        }

        // Actualizar último acceso
        $alumno->actualizarUltimoAcceso();

        // Validar permisos específicos basados en la ruta
        foreach ($permisos as $permiso) {
            if (!$this->verificarPermiso($request, $alumno, $permiso)) {
                return $this->accessDenied($request, "No tienes permiso para acceder a este recurso");
            }
        }

        // Validaciones específicas por ruta
        if (!$this->validarAccesoRuta($request, $alumno)) {
            return $this->accessDenied($request, "Acceso denegado a este recurso");
        }

        return $next($request);
    }

    /**
     * Verificar permisos específicos
     */
    private function verificarPermiso(Request $request, Alumno $alumno, string $permiso): bool
    {
        switch ($permiso) {
            case 'ver_actividades':
                return $this->puedeVerActividades($request, $alumno);
                
            case 'realizar_actividad':
                return $this->puedeRealizarActividad($request, $alumno);
                
            case 'ver_resultados':
                return $this->puedeVerResultados($request, $alumno);
                
            case 'acceder_parcial':
                return $this->puedeAccederParcial($request, $alumno);
                
            case 'inscribirse_grupo':
                return $this->puedeInscribirseGrupo($request, $alumno);
                
            default:
                return true;
        }
    }

    /**
     * Validar acceso específico por ruta
     */
    private function validarAccesoRuta(Request $request, Alumno $alumno): bool
    {
        $routeName = $request->route()->getName();
        
        switch ($routeName) {
            case 'alumnos.actividades':
                return $this->validarAccesoParcial($request, $alumno);
                
            case 'alumnos.ver-actividad':
                return $this->validarAccesoActividad($request, $alumno);
                
            case 'alumnos.procesar-respuesta':
                return $this->validarProcesamientoRespuesta($request, $alumno);
                
            case 'alumnos.resultado':
                return $this->validarAccesoResultado($request, $alumno);
                
            default:
                return true;
        }
    }

    /**
     * Validar acceso a un parcial específico
     */
    private function validarAccesoParcial(Request $request, Alumno $alumno): bool
    {
        $parcialId = $request->route('parcialId');
        if (!$parcialId) return false;

        $parcial = Parcial::with('cuatrimestre')->find($parcialId);
        if (!$parcial) return false;

        // CONTROL ESTRICTO: Solo su cuatrimestre actual
        if ($parcial->cuatrimestre->orden != $alumno->cuatrimestre_actual) {
            Log::warning("Acceso denegado a parcial de cuatrimestre diferente", [
                'alumno_id' => $alumno->id,
                'cuatrimestre_alumno' => $alumno->cuatrimestre_actual,
                'cuatrimestre_parcial' => $parcial->cuatrimestre->orden,
                'parcial_id' => $parcialId
            ]);
            return false;
        }

        // CONTROL ESTRICTO: Solo parciales accesibles
        if ($parcial->numero > $alumno->parcial_actual) {
            Log::warning("Acceso denegado a parcial no disponible", [
                'alumno_id' => $alumno->id,
                'parcial_alumno' => $alumno->parcial_actual,
                'parcial_solicitado' => $parcial->numero
            ]);
            return false;
        }

        return true;
    }

    /**
     * Validar acceso a una actividad específica
     */
    private function validarAccesoActividad(Request $request, Alumno $alumno): bool
    {
        $actividadId = $request->route('actividadId');
        if (!$actividadId) return false;

        $actividad = Actividad::with(['parcial.cuatrimestre'])->find($actividadId);
        if (!$actividad) return false;

        return $alumno->puedeAccederAActividad($actividad);
    }

    /**
     * Validar procesamiento de respuesta
     */
    private function validarProcesamientoRespuesta(Request $request, Alumno $alumno): bool
    {
        $actividadId = $request->route('actividadId');
        if (!$actividadId) return false;

        $actividad = Actividad::with(['parcial.cuatrimestre'])->find($actividadId);
        if (!$actividad) return false;

        // Verificar acceso básico
        if (!$alumno->puedeAccederAActividad($actividad)) {
            return false;
        }

        // Verificar intentos disponibles
        $intentosRealizados = \App\Models\ActividadIntento::where('actividad_id', $actividadId)
            ->where(function($query) use ($alumno) {
                $query->where('alumno_id', $alumno->id)
                      ->orWhere('alumno_nombre', $alumno->nombre_completo);
            })
            ->count();

        if ($intentosRealizados >= $actividad->intentos_permitidos) {
            Log::warning("Intento de procesar respuesta sin intentos disponibles", [
                'alumno_id' => $alumno->id,
                'actividad_id' => $actividadId,
                'intentos_realizados' => $intentosRealizados,
                'intentos_permitidos' => $actividad->intentos_permitidos
            ]);
            return false;
        }

        return true;
    }

    /**
     * Validar acceso a resultado
     */
    private function validarAccesoResultado(Request $request, Alumno $alumno): bool
    {
        $intentoId = $request->route('intentoId');
        if (!$intentoId) return false;

        $intento = \App\Models\ActividadIntento::with(['actividad.parcial.cuatrimestre'])
            ->find($intentoId);
            
        if (!$intento) return false;

        // Verificar que el intento pertenece al alumno
        $esDelAlumno = $intento->alumno_id === $alumno->id || 
                      $intento->alumno_nombre === $alumno->nombre_completo;

        if (!$esDelAlumno) {
            Log::warning("Intento de acceso a resultado de otro alumno", [
                'alumno_id' => $alumno->id,
                'intento_id' => $intentoId,
                'intento_alumno_id' => $intento->alumno_id
            ]);
            return false;
        }

        // Verificar acceso a la actividad del intento
        return $alumno->puedeAccederAActividad($intento->actividad);
    }

    /**
     * Verificar si puede ver actividades
     */
    private function puedeVerActividades(Request $request, Alumno $alumno): bool
    {
        // Verificar que el alumno tiene cuatrimestre activo
        $cuatrimestre = Cuatrimestre::where('orden', $alumno->cuatrimestre_actual)
                                  ->where('activo', true)
                                  ->first();
        return $cuatrimestre !== null;
    }

    /**
     * Verificar si puede realizar actividades
     */
    private function puedeRealizarActividad(Request $request, Alumno $alumno): bool
    {
        // Verificar que el alumno está en un estado válido para realizar actividades
        return $alumno->activo && $alumno->cuatrimestre_actual > 0 && $alumno->parcial_actual > 0;
    }

    /**
     * Verificar si puede ver resultados
     */
    private function puedeVerResultados(Request $request, Alumno $alumno): bool
    {
        return $alumno->activo;
    }

    /**
     * Verificar si puede acceder a un parcial
     */
    private function puedeAccederParcial(Request $request, Alumno $alumno): bool
    {
        return $this->validarAccesoParcial($request, $alumno);
    }

    /**
     * Verificar si puede inscribirse a grupos
     */
    private function puedeInscribirseGrupo(Request $request, Alumno $alumno): bool
    {
        return $alumno->activo && $alumno->cuatrimestre_actual > 0;
    }

    /**
     * Redireccionar al login con mensaje
     */
    private function redirectToLogin(Request $request, string $mensaje): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $mensaje,
                'redirect' => route('alumnos.panel')
            ], 401);
        }

        return redirect()->route('alumnos.panel')->with('error', $mensaje);
    }

    /**
     * Respuesta de acceso denegado
     */
    private function accessDenied(Request $request, string $mensaje): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $mensaje
            ], 403);
        }

        return redirect()->route('alumnos.mis-grupos')->with('error', $mensaje);
    }
}

/**
 * Middleware más específico para validación de cuatrimestre/parcial
 */
class ValidarCuatrimestreParcialMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.panel')
                ->with('error', 'Debes iniciar sesión primero');
        }

        $alumnoData = Session::get('alumno_datos');
        $alumno = Alumno::find($alumnoData['id']);

        if (!$alumno || !$alumno->activo) {
            Session::forget('alumno_datos');
            return redirect()->route('alumnos.panel')
                ->with('error', 'Tu cuenta no está disponible');
        }

        // Validar que el cuatrimestre del alumno sea válido
        $cuatrimestreValido = Cuatrimestre::where('orden', $alumno->cuatrimestre_actual)
                                        ->where('activo', true)
                                        ->exists();

        if (!$cuatrimestreValido) {
            Log::error("Alumno con cuatrimestre inválido", [
                'alumno_id' => $alumno->id,
                'cuatrimestre_actual' => $alumno->cuatrimestre_actual
            ]);
            
            return redirect()->route('alumnos.panel')
                ->with('error', 'Tu cuatrimestre actual no está disponible. Contacta al administrador.');
        }

        // Validar que el parcial del alumno sea válido
        $parcialValido = \App\Models\Parcial::whereHas('cuatrimestre', function($query) use ($alumno) {
                $query->where('orden', $alumno->cuatrimestre_actual)
                      ->where('activo', true);
            })
            ->where('numero', $alumno->parcial_actual)
            ->exists();

        if (!$parcialValido) {
            Log::error("Alumno con parcial inválido", [
                'alumno_id' => $alumno->id,
                'cuatrimestre_actual' => $alumno->cuatrimestre_actual,
                'parcial_actual' => $alumno->parcial_actual
            ]);
            
            return redirect()->route('alumnos.panel')
                ->with('error', 'Tu parcial actual no está disponible. Contacta al administrador.');
        }

        return $next($request);
    }
}

/**
 * Middleware para limitar acceso a solo horarios académicos (opcional)
 */
class HorarioAcademicoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar en producción
        if (app()->environment('production')) {
            $now = now();
            $hora = $now->hour;
            $diaSemana = $now->dayOfWeek; // 0 = domingo, 6 = sábado

            // Horario académico: Lunes a Viernes, 7:00 AM a 10:00 PM
            $esHorarioAcademico = $diaSemana >= 1 && $diaSemana <= 5 && $hora >= 7 && $hora <= 22;

            if (!$esHorarioAcademico) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'El sistema de actividades solo está disponible en horario académico (Lunes a Viernes, 7:00 AM - 10:00 PM)'
                    ], 403);
                }

                return redirect()->route('alumnos.panel')
                    ->with('warning', 'El sistema de actividades solo está disponible en horario académico (Lunes a Viernes, 7:00 AM - 10:00 PM)');
            }
        }

        return $next($request);
    }
}