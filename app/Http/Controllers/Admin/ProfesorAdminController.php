<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Grupo;
use Illuminate\Support\Facades\Log;

class ProfesorAdminController extends Controller
{
    public function index()
    {
        $profesores = User::whereIn('rol', ['docente', 'admin'])->orderBy('name')->get();
        return view('admin.profesores.index', compact('profesores'));
    }

    public function edit($id)
    {
        $profesor = User::findOrFail($id);
        $grupos = Grupo::orderBy('nombre')->get();
        return view('admin.profesores.edit', compact('profesor', 'grupos'));
    }

    public function update(Request $request, $id)
    {
        $profesor = User::findOrFail($id);

        $profesor->update($request->only(['name', 'email']));

        // Asignar grupos si vienen
        if ($request->has('grupos')) {
            $grupoIds = array_filter((array) $request->input('grupos'));
            foreach ($grupoIds as $gid) {
                try {
                    $g = Grupo::find($gid);
                    if ($g) {
                        $g->profesor_id = $profesor->id;
                        $g->save();
                    }
                } catch (\Exception $e) {
                    Log::error('Error asignando grupo en admin update: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.profesores.index')->with('success', 'Profesor actualizado');
    }

    /**
     * Promover un grupo al siguiente cuatrimestre y guardar snapshot en historial
     */
    public function promoverGrupo(Request $request, $id)
    {
        $grupo = Grupo::findOrFail($id);

        // Guardar snapshot
        \DB::table('grupo_historials')->insert([
            'grupo_id' => $grupo->id,
            'cuatrimestre_id' => $grupo->cuatrimestre_id ?? null,
            'periodo_escolar_id' => $grupo->periodo_escolar_id ?? null,
            'snapshot' => json_encode($grupo->toArray()),
            'promovido_por' => auth()->id() ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Incrementar cuatrimestre (asunción: existe campo cuatrimestre_id numérico)
        if (isset($grupo->cuatrimestre_id)) {
            $grupo->cuatrimestre_id = $grupo->cuatrimestre_id + 1;
            $grupo->save();
        }

        return response()->json(['success' => true, 'message' => 'Grupo promovido']);
    }
}
