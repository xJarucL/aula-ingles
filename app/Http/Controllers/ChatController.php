<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Mostrar chat grupal
     */
    public function index()
    {
        // Obtener mensajes grupales (sin destinatario específico)
        $mensajes = Mensaje::whereNull('para_usuario_id')
            ->with('emisor')
            ->orderBy('created_at', 'asc')
            ->take(50) // Limitar a los últimos 50 mensajes
            ->get();

        return view('chat.grupal', compact('mensajes'));
    }

    /**
     * Enviar mensaje al chat grupal
     */
    public function enviar(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000'
        ], [
            'mensaje.required' => 'El mensaje no puede estar vacío',
            'mensaje.max' => 'El mensaje no puede tener más de 1000 caracteres'
        ]);

        try {
            $mensaje = Mensaje::create([
                'de_usuario_id' => Auth::id(),
                'para_usuario_id' => null, // null = mensaje grupal
                'mensaje' => $request->mensaje
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => [
                    'id' => $mensaje->id,
                    'mensaje' => $mensaje->mensaje,
                    'usuario' => Auth::user()->name,
                    'fecha' => $mensaje->created_at->format('H:i')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje'
            ], 500);
        }
    }

    /**
     * Mostrar lista de usuarios para chat privado
     */
    public function usuarios()
    {
        $usuarios = User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return view('chat.usuarios', compact('usuarios'));
    }

    /**
     * Mostrar chat privado con un usuario específico
     */
    public function privado(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('chat.usuarios')
                ->with('error', 'No puedes chatear contigo mismo');
        }

        // Obtener mensajes entre los dos usuarios
        $mensajes = Mensaje::where(function($query) use ($user) {
            $query->where('de_usuario_id', Auth::id())
                  ->where('para_usuario_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('de_usuario_id', $user->id)
                  ->where('para_usuario_id', Auth::id());
        })
        ->with('emisor')
        ->orderBy('created_at', 'asc')
        ->get();

        return view('chat.privado', compact('user', 'mensajes'));
    }

    /**
     * Enviar mensaje privado
     */
    public function enviarPrivado(Request $request, User $user)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000'
        ]);

        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes enviarte mensajes a ti mismo'
            ], 400);
        }

        try {
            $mensaje = Mensaje::create([
                'de_usuario_id' => Auth::id(),
                'para_usuario_id' => $user->id,
                'mensaje' => $request->mensaje
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => [
                    'id' => $mensaje->id,
                    'mensaje' => $mensaje->mensaje,
                    'usuario' => Auth::user()->name,
                    'fecha' => $mensaje->created_at->format('H:i'),
                    'es_mio' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje'
            ], 500);
        }
    }

    /**
     * Obtener mensajes nuevos para actualización en tiempo real
     */
    public function obtenerMensajesNuevos(Request $request)
    {
        $ultimoId = $request->get('ultimo_id', 0);
        $tipoChat = $request->get('tipo', 'grupal'); // 'grupal' o 'privado'
        $usuarioId = $request->get('usuario_id', null);

        $query = Mensaje::where('id', '>', $ultimoId)
            ->with('emisor')
            ->orderBy('created_at', 'asc');

        if ($tipoChat === 'grupal') {
            $query->whereNull('para_usuario_id');
        } else {
            // Chat privado
            $query->where(function($q) use ($usuarioId) {
                $q->where('de_usuario_id', Auth::id())
                  ->where('para_usuario_id', $usuarioId);
            })->orWhere(function($q) use ($usuarioId) {
                $q->where('de_usuario_id', $usuarioId)
                  ->where('para_usuario_id', Auth::id());
            });
        }

        $mensajes = $query->get();

        return response()->json([
            'success' => true,
            'mensajes' => $mensajes->map(function($mensaje) {
                return [
                    'id' => $mensaje->id,
                    'mensaje' => $mensaje->mensaje,
                    'usuario' => $mensaje->emisor->name,
                    'fecha' => $mensaje->created_at->format('H:i'),
                    'es_mio' => $mensaje->de_usuario_id === Auth::id()
                ];
            })
        ]);
    }
}