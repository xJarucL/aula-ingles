<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrabajoController extends Controller
{
    public function index()
    {
        $archivos = Storage::disk('public')->files('trabajos');
        $archivos = collect($archivos)->map(fn($file) => basename($file));
        
        // ✅ CORREGIDO: Vista en la carpeta profesores
        return view('profesores.mis_trabajos', compact('archivos'));
    }

    public function subir(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'archivo.required' => 'Debes seleccionar un archivo',
            'archivo.mimes' => 'Solo se permiten archivos PDF, DOC y DOCX',
            'archivo.max' => 'El archivo no puede pesar más de 10MB'
        ]);

        try {
            $archivo = $request->file('archivo');
            $nombreOriginal = $archivo->getClientOriginalName();
            $nombre = time() . '_' . preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $nombreOriginal);
            
            $archivo->storeAs('trabajos', $nombre, 'public');

            return back()->with('success', 'El archivo se subió correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo subir el archivo: ' . $e->getMessage());
        }
    }

    public function descargar($archivo)
    {
        $ruta = storage_path("app/public/trabajos/{$archivo}");

        if (!file_exists($ruta)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->download($ruta);
    }

    public function eliminar($archivo)
    {
        try {
            $ruta = storage_path("app/public/trabajos/{$archivo}");

            if (file_exists($ruta)) {
                unlink($ruta);
                return response()->json([
                    'success' => true,
                    'message' => 'Archivo eliminado correctamente.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'El archivo no existe.'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }
}