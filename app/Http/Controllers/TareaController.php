<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class TareaController extends Controller
{
    /**
     * Mostrar todas las tareas para el profesor
     */
    public function index()
    {
        $tareas = Tarea::orderBy('created_at', 'desc')->get();
        return view('profesores.tareas', compact('tareas'));
    }

    /**
     * Crear tarea por parte del profesor para un grupo específico
     */
    public function crearParaGrupo(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'grupo_id' => 'required|exists:grupos,id',
            'fecha_entrega' => 'nullable|date'
        ]);

        try {
            $grupo = \App\Models\Grupo::findOrFail($request->grupo_id);

            $tarea = Tarea::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'materia' => $grupo->materia?->nombre ?? 'No especificada',
                'cuatrimestre' => $grupo->periodo_escolar?->cuatrimestre_actual ?? null,
                'alumno_nombre' => null,
                'alumno_matricula' => null,
                'alumno_carrera' => null,
                'archivo_original' => null,
                'archivo_guardado' => null,
                'archivo_ruta' => null,
                'archivo_size' => null,
                'archivo_tipo' => null,
                'calificacion' => null,
                'comentarios' => null,
                'estado' => 'pendiente',
                'fecha_entrega' => $request->fecha_entrega ?? now(),
                'grupo_id' => $grupo->id // Nota: requiere columna grupo_id en la tabla tareas
            ]);

            return redirect()->back()->with('success', 'Tarea creada para el grupo ' . $grupo->nombre);

        } catch (\Exception $e) {
            \Log::error('Error creando tarea para grupo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear tarea: ' . $e->getMessage());
        }
    }

    /**
     * Subir tarea por parte del alumno
     */
    public function subir(Request $request)
    {
        // Verificar que el alumno tenga sesión activa
        if (!Session::has('alumno_datos')) {
            return redirect()->route('alumnos.formulario')
                ->with('error', 'Debes iniciar sesión para subir tareas.');
        }

        $alumnoData = Session::get('alumno_datos');

        // Validación de datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'materia' => 'nullable|string|max:50',
            'cuatrimestre' => 'nullable|integer|min:1|max:10',
            'archivo' => 'required|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240', // 10MB máximo
        ], [
            'titulo.required' => 'El título de la tarea es obligatorio',
            'titulo.max' => 'El título no puede tener más de 255 caracteres',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres',
            'archivo.required' => 'Debes seleccionar un archivo',
            'archivo.mimes' => 'El archivo debe ser PDF, DOC, DOCX, TXT, JPG, JPEG o PNG',
            'archivo.max' => 'El archivo no puede pesar más de 10MB',
            'cuatrimestre.integer' => 'El cuatrimestre debe ser un número',
            'cuatrimestre.min' => 'El cuatrimestre debe ser al menos 1',
            'cuatrimestre.max' => 'El cuatrimestre no puede ser mayor a 10'
        ]);

        try {
            $archivo = $request->file('archivo');
            $nombreOriginal = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            
            // Generar nombre único para el archivo
            $nombreArchivo = time() . '_' . $alumnoData['matricula'] . '_' . preg_replace('/[^A-Za-z0-9\-_]/', '_', pathinfo($nombreOriginal, PATHINFO_FILENAME)) . '.' . $extension;
            
            // Guardar archivo en storage/app/public/tareas
            $rutaArchivo = $archivo->storeAs('tareas', $nombreArchivo, 'public');

            // Crear registro en base de datos
            $tarea = Tarea::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'materia' => $request->materia ?? 'No especificada',
                'cuatrimestre' => $request->cuatrimestre ?? $alumnoData['cuatrimestre'],
                'alumno_nombre' => $alumnoData['nombre'] . ' ' . ($alumnoData['apellidos'] ?? ''),
                'alumno_matricula' => $alumnoData['matricula'],
                'alumno_carrera' => $alumnoData['carrera'] ?? 'No especificada',
                'archivo_original' => $nombreOriginal,
                'archivo_guardado' => $nombreArchivo,
                'archivo_ruta' => $rutaArchivo,
                'archivo_size' => $archivo->getSize(),
                'archivo_tipo' => $archivo->getMimeType(),
                'calificacion' => null,
                'comentarios' => null,
                'estado' => 'pendiente', // pendiente, revisada, calificada
                'fecha_entrega' => now(),
            ]);

            // Log de actividad
            \Log::info('Tarea subida exitosamente', [
                'tarea_id' => $tarea->id,
                'alumno' => $alumnoData['matricula'],
                'archivo' => $nombreArchivo
            ]);

            return redirect()->back()
                ->with('success', '✅ ¡Tarea subida exitosamente! Tu profesor la revisará pronto. ID de tarea: #' . $tarea->id);

        } catch (\Exception $e) {
            // Log del error
            \Log::error('Error al subir tarea', [
                'error' => $e->getMessage(),
                'alumno' => $alumnoData['matricula'] ?? 'No identificado',
                'file' => $archivo->getClientOriginalName() ?? 'No especificado'
            ]);

            return redirect()->back()
                ->with('error', '❌ Error al subir la tarea: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Calificar una tarea
     */
    public function calificar(Request $request, $id)
    {
        $request->validate([
            'calificacion' => 'required|numeric|min:0|max:10',
            'comentarios' => 'nullable|string|max:1000'
        ], [
            'calificacion.required' => 'La calificación es obligatoria',
            'calificacion.numeric' => 'La calificación debe ser un número',
            'calificacion.min' => 'La calificación mínima es 0',
            'calificacion.max' => 'La calificación máxima es 10',
            'comentarios.max' => 'Los comentarios no pueden exceder 1000 caracteres'
        ]);

        try {
            $tarea = Tarea::findOrFail($id);
            $tarea->update([
                'calificacion' => $request->calificacion,
                'comentarios' => $request->comentarios,
                'estado' => 'calificada',
                'fecha_calificacion' => now(),
            ]);

            \Log::info('Tarea calificada', [
                'tarea_id' => $id,
                'calificacion' => $request->calificacion,
                'profesor' => auth()->user()->name ?? 'Sistema'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tarea calificada exitosamente',
                'calificacion' => $request->calificacion,
                'estado' => 'calificada'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al calificar tarea', [
                'tarea_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al calificar la tarea: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver archivo de tarea
     */
    public function verArchivo($archivo)
    {
        $rutaCompleta = storage_path('app/public/tareas/' . $archivo);

        if (!file_exists($rutaCompleta)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file($rutaCompleta);
    }

    /**
     * Descargar archivo de tarea
     */
    public function descargar($archivo)
    {
        $rutaCompleta = storage_path('app/public/tareas/' . $archivo);

        if (!file_exists($rutaCompleta)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($rutaCompleta);
    }

    /**
     * Eliminar tarea
     */
    public function eliminar($id)
    {
        try {
            $tarea = Tarea::findOrFail($id);
            $archivo = $tarea->archivo_guardado;

            // Eliminar archivo físico
            $rutaArchivo = 'tareas/' . $archivo;
            if (Storage::disk('public')->exists($rutaArchivo)) {
                Storage::disk('public')->delete($rutaArchivo);
            }

            // Eliminar registro de base de datos
            $tarea->delete();

            \Log::info('Tarea eliminada', [
                'tarea_id' => $id,
                'archivo' => $archivo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tarea eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar tarea', [
                'tarea_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la tarea: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener tareas de un alumno específico
     */
    public function tareasAlumno(Request $request)
    {
        if (!Session::has('alumno_datos')) {
            return response()->json(['error' => 'Sesión no válida'], 401);
        }

        $alumnoData = Session::get('alumno_datos');
        $tareas = Tarea::where('alumno_matricula', $alumnoData['matricula'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        return response()->json([
            'success' => true,
            'tareas' => $tareas
        ]);
    }

    /**
     * Obtener estadísticas de tareas del alumno
     */
    public function estadisticas()
    {
        if (!Session::has('alumno_datos')) {
            return response()->json(['error' => 'Sesión no válida'], 401);
        }

        $alumnoData = Session::get('alumno_datos');
        
        $stats = [
            'total' => Tarea::where('alumno_matricula', $alumnoData['matricula'])->count(),
            'pendientes' => Tarea::where('alumno_matricula', $alumnoData['matricula'])->where('estado', 'pendiente')->count(),
            'calificadas' => Tarea::where('alumno_matricula', $alumnoData['matricula'])->where('estado', 'calificada')->count(),
            'promedio' => Tarea::where('alumno_matricula', $alumnoData['matricula'])->whereNotNull('calificacion')->avg('calificacion')
        ];

        return response()->json([
            'success' => true,
            'estadisticas' => $stats
        ]);
    }
}