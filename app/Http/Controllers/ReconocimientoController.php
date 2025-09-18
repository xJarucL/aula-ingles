<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReconocimientoController extends Controller
{
    public function index()
    {
        $fraseEsperada = session('frase_esperada', 'Hello, how are you?');
        return view('voice.reconocimiento', compact('fraseEsperada'));
    }

    public function actualizarFrase(Request $request)
    {
        $request->validate([
            'frase_esperada' => 'required|string|max:500'
        ]);

        session(['frase_esperada' => $request->frase_esperada]);

        return back()->with('success', 'Frase actualizada exitosamente');
    }

    public function verificar(Request $request)
    {
        $request->validate([
            'frase_esperada' => 'required|string',
            'frase_dicha' => 'required|string'
        ]);

        $esperado = $this->limpiarTexto($request->frase_esperada);
        $dicho = $this->limpiarTexto($request->frase_dicha);

        if ($esperado === $dicho) {
            $resultado = [
                'status' => 'correcto',
                'message' => '¡Correcto! La frase coincide perfectamente.',
                'class' => 'success'
            ];
        } else {
            $palabrasEsperadas = explode(' ', $esperado);
            $coincidencias = 0;
            
            foreach ($palabrasEsperadas as $palabra) {
                if (strpos($dicho, $palabra) !== false) {
                    $coincidencias++;
                }
            }

            $umbral = max(2, count($palabrasEsperadas) - 1);
            
            if ($coincidencias >= $umbral) {
                $resultado = [
                    'status' => 'parcial',
                    'message' => 'Casi correcto. Revisa tu pronunciación o palabras.',
                    'class' => 'warning'
                ];
            } else {
                $resultado = [
                    'status' => 'incorrecto',
                    'message' => 'No coincide. Intenta de nuevo.',
                    'class' => 'error'
                ];
            }
        }

        return response()->json($resultado);
    }

    private function limpiarTexto($texto)
    {
        return strtolower(trim(preg_replace('/[.,!?¿¡]/', '', $texto)));
    }
}