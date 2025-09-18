<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GrupoAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $query = Grupo::with('profesor', 'periodoEscolar');
        if ($q) {
            $query->where('nombre', 'like', "%{$q}%")
                  ->orWhere('codigo', 'like', "%{$q}%");
        }

        $grupos = $query->orderBy('nombre')->paginate(20)->appends(['q' => $q]);

        $profesores = User::where('rol', 'docente')->orderBy('name')->get();

        return view('admin.grupos.index', compact('grupos', 'q', 'profesores'));
    }

    public function create()
    {
        $profesores = User::where('rol', 'docente')->orderBy('name')->get();
        return view('admin.grupos.create', compact('profesores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:grupos,codigo',
            'profesor_id' => 'nullable|exists:users,id',
            'capacidad_maxima' => 'nullable|integer|min:1',
        ]);

        $data['activo'] = $request->has('activo');
        $grupo = Grupo::create($data);

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo creado');
    }

    public function edit($id)
    {
        $grupo = Grupo::with('inscripciones.alumno')->findOrFail($id);
        $profesores = User::where('rol', 'docente')->orderBy('name')->get();
        return view('admin.grupos.edit', compact('grupo', 'profesores'));
    }

    public function update(Request $request, $id)
    {
        $grupo = Grupo::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:grupos,codigo,' . $grupo->id,
            'profesor_id' => 'nullable|exists:users,id',
            'capacidad_maxima' => 'nullable|integer|min:1',
        ]);
        $grupo->update(array_merge($data, ['activo' => $request->has('activo')]));

        return redirect()->route('admin.grupos.index')->with('success', 'Grupo actualizado');
    }

    public function destroy($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();
        return redirect()->route('admin.grupos.index')->with('success', 'Grupo eliminado');
    }

    // Import CSV de alumnos
    public function importarAlumnos(Request $request, $grupoId)
    {
        $grupo = Grupo::findOrFail($grupoId);
        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:csv,txt'
        ]);
        if ($validator->fails()) {
            return back()->with('error', 'Archivo inválido');
        }

        $path = $request->file('archivo')->getRealPath();
        $lines = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($lines));

        $added = 0; $skipped = 0;
        foreach ($lines as $row) {
            $row = array_combine($header, $row);
            if (!$row) continue;
            $matricula = trim($row['matricula'] ?? $row['matrícula'] ?? '');
            if (!$matricula) { $skipped++; continue; }

            $alumno = Alumno::firstOrCreate([
                'matricula' => strtoupper($matricula)
            ], [
                'nombre' => $row['nombre'] ?? ($row['nombre_completo'] ?? 'Sin nombre'),
                'email' => $row['email'] ?? null,
                'activo' => true
            ]);

            // Crear inscripción si no existe
            $ins = Inscripcion::firstOrCreate([
                'alumno_id' => $alumno->id,
                'grupo_id' => $grupo->id
            ], [
                'estado' => 'inscrito',
                'fecha_inscripcion' => now()
            ]);
            $added++;
        }

        return back()->with('success', "Import completed: added={$added}, skipped={$skipped}");
    }

    // Agregar alumno manualmente al grupo (por id/alumno_id)
    public function agregarAlumno(Request $request, $grupoId)
    {
        $grupo = Grupo::findOrFail($grupoId);
        $data = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id'
        ]);

        Inscripcion::updateOrCreate([
            'alumno_id' => $data['alumno_id'],
            'grupo_id' => $grupo->id
        ], [
            'estado' => 'inscrito',
            'fecha_inscripcion' => now()
        ]);

        return back()->with('success', 'Alumno agregado al grupo');
    }

    // Quitar (dar de baja) alumno (cambiar estado a 'inactivo' / 'retirado')
    public function quitarAlumno(Request $request, $grupoId, $alumnoId)
    {
        $ins = Inscripcion::where('grupo_id', $grupoId)->where('alumno_id', $alumnoId)->firstOrFail();
        $ins->update(['estado' => 'retirado', 'fecha_retiro' => now()]);
        return back()->with('success', 'Alumno retirado del grupo');
    }

    // Mover alumno entre grupos
    public function moverAlumno(Request $request, $grupoId)
    {
        $data = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'nuevo_grupo_id' => 'required|exists:grupos,id'
        ]);

        $ins = Inscripcion::where('grupo_id', $grupoId)->where('alumno_id', $data['alumno_id'])->firstOrFail();
        $ins->update(['grupo_id' => $data['nuevo_grupo_id']]);
        return back()->with('success', 'Alumno movido correctamente');
    }
}
