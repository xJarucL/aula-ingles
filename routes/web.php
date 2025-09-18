<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\AlumnoController;
use App\Http\Middleware\AccesoAlumnoMiddleware;
use App\Http\Middleware\ValidarCuatrimestreParcialMiddleware;
use App\Http\Middleware\HorarioAcademicoMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as VerifyCsrf;
use App\Http\Controllers\Auth\GoogleOneTapController;
use Illuminate\Http\Request;

// ========== RUTAS GENERALES ==========
Route::get('/', function () {
    $title = 'Índice';
    return view('index', compact('title'));
})->name('inicio');

Route::view('/reconocimiento', 'reconocimiento');
Route::view('/profesores/login', 'profesores.login')->name('profesores.login');
Route::view('/profesores/mis-estudiantes', 'profesores.mis_estudiantes')->name('profesores.estudiantes');
Route::view('/perfil', 'profesores.perfil')->name('perfil');
Route::view('/notificaciones', 'profesores.notificaciones')->name('notificaciones');

// Redirección para login (por compatibilidad con auth)
Route::get('/login', fn() => redirect()->route('profesores.login'))->name('login');

// Login temporal para pruebas
Route::post('/login-temporal', function() {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'profesor@test.com'],
        ['name' => 'Profesor Test', 'password' => bcrypt('123456')]
    );
    Auth::login($user);
    return response()->json(['success' => true]);
})->name('login.temporal');

Route::post('/login-temporal-estudiante', function() {
    $alumno = \App\Models\Alumno::with('carrera')->first();
    if ($alumno) {
        Session::put('alumno_datos', [
            'id' => $alumno->id,
            'nombre' => $alumno->nombre_completo ?? ($alumno->nombre . ' ' . ($alumno->apellidos ?? '')),
            'matricula' => $alumno->matricula,
            'carrera' => $alumno->carrera->nombre ?? null,
            'carrera_id' => $alumno->carrera_id,
            'cuatrimestre' => $alumno->cuatrimestre_actual ?? null,
            'parcial' => $alumno->parcial_actual ?? null,
            'email' => $alumno->email ?? null,
            'ultimo_acceso' => now()->toDateTimeString()
        ]);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'No hay alumnos en la base de datos para login temporal'], 500);
})->name('login.temporal.estudiante');

// ========== MÓDULO DE ALUMNOS (ÚNICO) ==========
Route::prefix('alumnos')->name('alumnos.')->group(function () {
    
    // Acceso y autenticación
    Route::get('/', [AlumnoController::class, 'panel'])->name('panel');
    Route::get('panel', [AlumnoController::class, 'panel'])->name('panel.alt');
    Route::get('ingresar', [AlumnoController::class, 'panel'])->name('ingresar');
    // Ruta de compatibilidad usada por algunos controladores
    Route::get('formulario', [AlumnoController::class, 'panel'])->name('formulario');
    Route::post('procesar', [AlumnoController::class, 'procesarIngreso'])->name('procesar');
    
    // Grupos y navegación
    Route::get('mis-grupos', [AlumnoController::class, 'misGrupos'])->name('mis-grupos');
    Route::post('inscribirse-grupo/{grupoId}', [AlumnoController::class, 'inscribirseGrupo'])->name('inscribirse-grupo');
    Route::get('actividades-grupo/{grupoId}', [AlumnoController::class, 'actividadesGrupo'])->name('actividades-grupo');
    Route::post('retirarse-grupo/{grupoId}', [AlumnoController::class, 'retirarseGrupo'])->name('retirarse-grupo');
    Route::get('progreso-grupo/{grupoId}', [AlumnoController::class, 'progresoGrupo'])->name('progreso-grupo');
    Route::post('actualizar-perfil', [AlumnoController::class, 'actualizarPerfil'])->name('actualizar-perfil');
    
    // Actividades
    Route::get('actividades/{parcialId}', [AlumnoController::class, 'actividades'])->name('actividades');
    Route::get('cuatrimestre/{cuatrimestreId}/actividades', [AlumnoController::class, 'actividadesCuatrimestre'])->name('cuatrimestre.actividades');
    Route::get('ver-actividad/{actividadId}', [AlumnoController::class, 'verActividad'])->name('ver-actividad');
    
    // Procesar respuestas
    Route::post('procesar-respuesta/{actividadId}', [AlumnoController::class, 'procesarRespuesta'])->name('procesar-respuesta');
    
    // Resultados e historial
    Route::get('resultado/{intentoId}', [AlumnoController::class, 'verResultado'])->name('resultado');
    Route::get('historial', [AlumnoController::class, 'historial'])->name('historial');
    Route::get('estadisticas', [AlumnoController::class, 'estadisticas'])->name('estadisticas');
    
    // RUTAS FALTANTES QUE CAUSAN ERRORES
    Route::get('logout', [AlumnoController::class, 'cerrarSesion'])->name('logout');
    Route::get('mis-trabajos', [TrabajoController::class, 'misTrabajos'])->name('mis.trabajos');
    
    // Sesión
    Route::get('cerrar-sesion', [AlumnoController::class, 'cerrarSesion'])->name('cerrar-sesion');
    
    // Subir tareas (removed - functionality disabled)
    // Route::get('subir', fn() => view('alumnos.subir_tarea'))->name('subir');
    // Route::post('subir-tarea', [TareaController::class, 'subir'])->name('subir-tarea');
    
    // Verificar estado de sesión (AJAX)
    Route::get('verificar-sesion', function() {
        return response()->json([
            'activa' => Session::has('alumno_datos'),
            'datos' => Session::get('alumno_datos')
        ]);
    })->name('verificar-sesion');
});

// ========== RUTAS DE COMPATIBILIDAD ==========
Route::post('/estudiantes/procesar', [AlumnoController::class, 'procesarIngreso'])->name('estudiantes.procesar');
Route::post('/alumnos-direct/procesar', [AlumnoController::class, 'procesarIngreso'])->name('alumnos-direct.procesar');

// ========== PANEL PROFESORES ==========
Route::get('/profesores/panel', [ProfesorController::class, 'panel'])->name('profesores.panel');

// ========== TRABAJOS Y TAREAS PROFESOR ==========
Route::prefix('profesores')->group(function () {
    // Trabajos
    Route::get('trabajos', [TrabajoController::class, 'index'])->name('trabajos.index');
    Route::post('trabajos/subir', [TrabajoController::class, 'subir'])->name('trabajos.subir');
    Route::get('trabajos/descargar/{archivo}', [TrabajoController::class, 'descargar'])->name('trabajos.descargar');
    Route::delete('trabajos/{archivo}', [TrabajoController::class, 'eliminar'])->name('trabajos.eliminar');

    // Tareas
    Route::get('tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::post('tareas/{id}/calificar', [TareaController::class, 'calificar'])->name('tareas.calificar');
});

// ========== VER ARCHIVOS DE TAREAS ==========
Route::get('/ver-archivo-tarea/{archivo}', function($archivo) {
    $path = storage_path('app/public/tareas/' . $archivo);
    
    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado');
    }
    
    return response()->file($path);
})->name('ver.archivo.tarea');

// ========== CHAT (protegido) ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.grupal');
    Route::post('/chat/enviar', [ChatController::class, 'enviar'])->name('chat.enviar');
    Route::get('/chat/usuarios', [ChatController::class, 'usuarios'])->name('chat.usuarios');
    Route::get('/chat/{user}', [ChatController::class, 'privado'])->name('chat.privado');
    Route::post('/chat/{user}/enviar', [ChatController::class, 'enviarPrivado'])->name('chat.privado.enviar');
});

// ========== ACTIVIDADES PROFESOR (ÚNICO) ==========
Route::prefix('profesores')->name('profesores.')->group(function () {
    // Panel principal de actividades
    Route::get('actividades', [ActividadController::class, 'index'])->name('actividades');

    // CRUD de actividades - RUTAS ESPECÍFICAS PRIMERO
    Route::get('actividades/crear', [ActividadController::class, 'crear'])->name('actividades.crear');

    // AJAX y extras - ANTES de las rutas con parámetros
    Route::get('actividades/filtrar', [ActividadController::class, 'filtrar'])->name('actividades.filtrar');
    
    // Ruta PDF
    Route::post('actividades/procesar-pdf', [ActividadController::class, 'procesarPdf'])->name('actividades.procesar-pdf');
    
    Route::post('actividades/{id}/toggle', [ActividadController::class, 'toggleEstado'])->name('actividades.toggle');
    Route::post('actividades/{id}/duplicar', [ActividadController::class, 'duplicar'])->name('actividades.duplicar');

    // RUTAS CON PARÁMETROS AL FINAL
    Route::post('actividades', [ActividadController::class, 'store'])->name('actividades.store');
    Route::get('actividades/{id}', [ActividadController::class, 'show'])->name('actividades.show');
    Route::get('actividades/{id}/editar', [ActividadController::class, 'edit'])->name('actividades.edit');
    Route::put('actividades/{id}', [ActividadController::class, 'update'])->name('actividades.update');
    Route::delete('actividades/{id}', [ActividadController::class, 'destroy'])->name('actividades.destroy');
    Route::get('actividades/{id}/ver', [ActividadController::class, 'ver'])->name('actividades.ver');
});

// RUTAS ADMIN (gestión de profesores)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('profesores', [App\Http\Controllers\Admin\ProfesorAdminController::class, 'index'])->name('profesores.index');
    Route::get('profesores/{id}/editar', [App\Http\Controllers\Admin\ProfesorAdminController::class, 'edit'])->name('profesores.edit');
    Route::put('profesores/{id}', [App\Http\Controllers\Admin\ProfesorAdminController::class, 'update'])->name('profesores.update');
    // Promover grupo (mover al siguiente cuatrimestre y guardar snapshot en historial)
    Route::post('grupos/{id}/promover', [App\Http\Controllers\Admin\ProfesorAdminController::class, 'promoverGrupo'])->name('grupos.promover');

    // Gestión de grupos (CRUD)
    Route::get('grupos', [App\Http\Controllers\Admin\GrupoAdminController::class, 'index'])->name('grupos.index');
    Route::get('grupos/crear', [App\Http\Controllers\Admin\GrupoAdminController::class, 'create'])->name('grupos.create');
    Route::post('grupos', [App\Http\Controllers\Admin\GrupoAdminController::class, 'store'])->name('grupos.store');
    Route::get('grupos/{id}/editar', [App\Http\Controllers\Admin\GrupoAdminController::class, 'edit'])->name('grupos.edit');
    Route::put('grupos/{id}', [App\Http\Controllers\Admin\GrupoAdminController::class, 'update'])->name('grupos.update');
    Route::delete('grupos/{id}', [App\Http\Controllers\Admin\GrupoAdminController::class, 'destroy'])->name('grupos.destroy');

    // Alumnos en grupos
    Route::post('grupos/{id}/importar-alumnos', [App\Http\Controllers\Admin\GrupoAdminController::class, 'importarAlumnos'])->name('grupos.importar');
    Route::post('grupos/{id}/agregar-alumno', [App\Http\Controllers\Admin\GrupoAdminController::class, 'agregarAlumno'])->name('grupos.agregarAlumno');
    Route::post('grupos/{id}/mover-alumno', [App\Http\Controllers\Admin\GrupoAdminController::class, 'moverAlumno'])->name('grupos.moverAlumno');
    Route::post('grupos/{id}/quitar-alumno/{alumnoId}', [App\Http\Controllers\Admin\GrupoAdminController::class, 'quitarAlumno'])->name('grupos.quitarAlumno');
});

// Endpoint para que un profesor envíe notificación al admin (coordinador)
Route::post('/profesores/enviar-a-admin', function (\Illuminate\Http\Request $request) {
    if (!auth()->check()) return response()->json(['success' => false, 'message' => 'No autorizado'], 401);

    $user = auth()->user();
    $admin = \App\Models\User::where('rol', 'admin')->first();
    if (!$admin) return response()->json(['success' => false, 'message' => 'No hay admin configurado'], 404);

    $mensaje = \App\Models\Mensaje::create([
        'de_usuario_id' => $user->id,
        'para_usuario_id' => $admin->id,
        'mensaje' => $request->input('mensaje', 'Sin contenido')
    ]);

    return response()->json(['success' => true, 'mensaje_id' => $mensaje->id]);
})->middleware('auth')->name('profesores.enviar.a.admin');

// Endpoint que recibe el ID token de Google One Tap (SIN CSRF)
Route::post('/auth/google/onetap', [GoogleOneTapController::class, 'handle'])
    ->withoutMiddleware([VerifyCsrf::class])
    ->middleware('throttle:10,1')   // <= ADD: 10 intentos por minuto por IP
    ->name('google.onetap.handle');

// Protege el panel de profesores con sesión + rol
Route::middleware(['auth', 'role:docente,admin'])->group(function () {
    Route::get('/profesores/panel', [ProfesorController::class, 'panel'])
        ->name('profesores.panel');

});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
     return redirect()->route('login')->with('auth_ok', 'Sesión cerrada correctamente');
})->name('logout');