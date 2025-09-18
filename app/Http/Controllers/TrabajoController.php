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
        return view('mis_trabajos', compact('archivos'));
    }

    public function subir(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            $nombre = time() . '_' . $request->file('archivo')->getClientOriginalName();
            $request->file('archivo')->storeAs('trabajos', $nombre, 'public');

            return back()->with('success', 'El archivo se subió correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo subir el archivo.');
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
        
    $ruta = storage_path("app/public/trabajos/{$archivo}");

    if (file_exists($ruta)) {
        unlink($ruta);
        return back()->with('success', 'Archivo eliminado correctamente.');
    }

    return back()->with('error', 'El archivo no existe.');
}
  public function misTrabajos()
{
    // Si no tienes esta función, agrégala al TrabajoController
    return view('alumnos.mis_trabajos', [
        'trabajos' => collect([]) // Por ahora vacío, puedes agregar lógica después
    ]);
}
}

   