<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Grupo;
use App\Models\Alumno;
use App\Models\Inscripcion;
use App\Models\User;
use App\Models\Actividad;
use App\Models\Cuatrimestre;
use App\Models\Parcial;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SistemaAlumnosSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker::create('es_ES'); // Faker en español
    }

    public function run()
    {
        $this->command->info('🚀 Creando datos DINÁMICOS para el sistema de alumnos...');

        // 1. Crear Carreras dinámicamente
        $this->command->info('📚 Creando carreras...');
        $carrerasBase = [
            'Tecnologías de la Información y Comunicación' => 'TICS',
            'Contaduría' => 'CONT',
            'Enfermería' => 'ENF',
            'Gastronomía' => 'GAST',
            'Agricultura Sustentable' => 'AGRI',
            'Mecatrónica' => 'MECA',
            'Energías Renovables' => 'ENER'
        ];

        foreach ($carrerasBase as $nombre => $codigo) {
            Carrera::firstOrCreate(['codigo' => $codigo], [
                'nombre' => $nombre,
                'codigo' => $codigo,
                'activa' => $this->faker->boolean(90) // 90% probabilidad de estar activa
            ]);
        }

        // 2. Crear Cuatrimestres dinámicamente
        $this->command->info('📅 Creando cuatrimestres...');
        for ($i = 1; $i <= 6; $i++) {
            Cuatrimestre::firstOrCreate(['orden' => $i], [
                'nombre' => $this->getNombreCuatrimestre($i),
                'orden' => $i,
                'activo' => $i <= 5 // Solo los primeros 5 activos
            ]);
        }

        // 3. Crear Parciales dinámicamente
        $this->command->info('📝 Creando parciales...');
        $cuatrimestres = Cuatrimestre::all();
        foreach ($cuatrimestres as $cuatrimestre) {
            for ($parcial = 1; $parcial <= 3; $parcial++) {
                Parcial::firstOrCreate([
                    'cuatrimestre_id' => $cuatrimestre->id,
                    'numero' => $parcial
                ], [
                    'nombre' => $this->getNombreParcial($parcial)
                ]);
            }
        }

        // 4. Crear Períodos Escolares dinámicos
        $this->command->info('📅 Creando períodos escolares...');
        $periodos = [
            ['2024-3', 'Cuatrimestre Septiembre-Diciembre 2024', '2024-09-01', '2024-12-15', false],
            ['2025-1', 'Cuatrimestre Enero-Abril 2025', '2025-01-15', '2025-04-30', true],
            ['2025-2', 'Cuatrimestre Mayo-Agosto 2025', '2025-05-01', '2025-08-15', false],
        ];

        foreach ($periodos as [$codigo, $nombre, $inicio, $fin, $activo]) {
            PeriodoEscolar::firstOrCreate(['codigo' => $codigo], [
                'nombre' => $nombre,
                'tipo' => 'academico',
                'fecha_inicio' => $inicio,
                'fecha_fin' => $fin,
                'activo' => $activo
            ]);
        }

        // 5. Crear Profesores dinámicos
        $this->command->info('👩‍🏫 Creando profesores...');
        $profesoresCreados = [];
        for ($i = 1; $i <= 8; $i++) {
            $nombre = $this->faker->firstName();
            $apellido = $this->faker->lastName();
            $email = strtolower($nombre . '.' . $apellido) . '@utenglish.edu.mx';

            $profesor = User::firstOrCreate(['email' => $email], [
                'name' => "Prof. {$nombre} {$apellido}",
                'email' => $email,
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]);
            $profesoresCreados[] = $profesor;
        }

        // 6. Crear Materias dinámicas
        $this->command->info('📖 Creando materias...');
        $materiasTemplates = [
            1 => ['Inglés Básico I', 'Inglés Conversacional I', 'Gramática Fundamental'],
            2 => ['Inglés Básico II', 'Inglés Conversacional II', 'Vocabulario Avanzado'],
            3 => ['Inglés Intermedio I', 'Business English I', 'Writing Skills I'],
            4 => ['Inglés Intermedio II', 'Business English II', 'Writing Skills II'],
            5 => ['Inglés Avanzado I', 'Technical English', 'Presentation Skills'],
            6 => ['Inglés Avanzado II', 'Professional English', 'Academic Writing']
        ];

        $grupos = [];
        $periodoActivo = PeriodoEscolar::where('activo', true)->first();

        foreach (Carrera::all() as $carrera) {
            foreach ($materiasTemplates as $cuatrimestre => $materias) {
                foreach ($materias as $index => $nombreMateria) {
                    $codigo = strtoupper($carrera->codigo) . '-' . $cuatrimestre . sprintf('%02d', $index + 1);

                    $materia = Materia::firstOrCreate([
                        'codigo' => $codigo,
                        'carrera_id' => $carrera->id
                    ], [
                        'nombre' => $nombreMateria,
                        'carrera_id' => $carrera->id,
                        'cuatrimestre_numero' => $cuatrimestre,
                        'activa' => $this->faker->boolean(85)
                    ]);

                    // Crear 1-2 grupos por materia
                    $numGrupos = $this->faker->numberBetween(1, 2);
                    for ($g = 1; $g <= $numGrupos; $g++) {
                        $letraGrupo = chr(64 + $g); // A, B, C...
                        $grupo = Grupo::firstOrCreate([
                            'materia_id' => $materia->id,
                            'codigo' => $codigo . '-' . $letraGrupo
                        ], [
                            'nombre' => $materia->nombre . ' - Grupo ' . $letraGrupo,
                            'codigo' => $codigo . '-' . $letraGrupo,
                            'profesor_id' => $profesoresCreados[array_rand($profesoresCreados)]->id,
                            'periodo_escolar_id' => $periodoActivo->id,
                            'activo' => true
                        ]);
                        $grupos[] = $grupo;
                    }
                }
            }
        }

        // 7. Crear Alumnos dinámicos
        $this->command->info('👨‍🎓 Creando alumnos...');
        $alumnosCreados = [];
        $carreraTics = Carrera::where('codigo', 'TICS')->first();

        for ($i = 1; $i <= 20; $i++) {
            $nombre = $this->faker->firstName();
            $apellidos = $this->faker->lastName() . ' ' . $this->faker->lastName();
            $matricula = '2025' . sprintf('%06d', $i);

            $alumno = Alumno::firstOrCreate(['matricula' => $matricula], [
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'matricula' => $matricula,
                'carrera_id' => $carreraTics->id,
                'cuatrimestre_actual' => $this->faker->numberBetween(1, 4),
                'email' => strtolower(str_replace(' ', '.', $nombre . '.' . explode(' ', $apellidos)[0])) . '@alumno.ut.edu.mx',
                'activo' => $this->faker->boolean(95),
                'ultimo_acceso' => $this->faker->dateTimeBetween('-30 days', 'now')
            ]);
            $alumnosCreados[] = $alumno;
        }

        // 8. Crear Inscripciones dinámicas
        $this->command->info('📝 Creando inscripciones...');
        foreach ($alumnosCreados as $alumno) {
            $gruposDisponibles = collect($grupos)->filter(function($grupo) use ($alumno) {
                return $grupo->materia->carrera_id == $alumno->carrera_id &&
                       $grupo->materia->cuatrimestre_numero == $alumno->cuatrimestre_actual;
            });

            // Inscribir en 2-4 materias aleatorias
            $gruposParaInscribir = $gruposDisponibles->random(min($this->faker->numberBetween(2, 4), $gruposDisponibles->count()));

            foreach ($gruposParaInscribir as $grupo) {
                Inscripcion::firstOrCreate([
                    'alumno_id' => $alumno->id,
                    'grupo_id' => $grupo->id
                ], [
                    'estado' => $this->faker->randomElement(['inscrito', 'retirado', 'completado']),
                    'fecha_inscripcion' => $this->faker->dateTimeBetween('-60 days', 'now')
                ]);
            }
        }

        // 9. ✅ CREAR ACTIVIDADES DINÁMICAS ✅
        $this->command->info('📋 Creando actividades dinámicas...');

        $parciales = Parcial::whereHas('cuatrimestre', function($q) {
            $q->whereIn('orden', [1, 2, 3]);
        })->get();

        foreach ($parciales as $parcial) {
            $numActividades = $this->faker->numberBetween(3, 8);

            for ($a = 1; $a <= $numActividades; $a++) {
                $tipoActividad = $this->faker->randomElement(['quiz', 'quiz', 'quiz', 'completar']); // Más quizzes

                $actividad = [
                    'nombre' => $this->generarNombreActividad($tipoActividad),
                    'descripcion' => $this->generarDescripcionActividad($tipoActividad),
                    'contenido' => $this->generarContenidoActividad($tipoActividad),
                    'activa' => $this->faker->boolean(80),
                    'parcial_id' => $parcial->id
                ];

                try {
                    Actividad::firstOrCreate([
                        'nombre' => $actividad['nombre'],
                        'parcial_id' => $parcial->id
                    ], $actividad);
                } catch (\Exception $e) {
                    $this->command->warn("Error creando actividad: " . $e->getMessage());
                }
            }
        }

        $this->mostrarResumen($profesoresCreados, $alumnosCreados);
    }

    // ========== MÉTODOS AUXILIARES DINÁMICOS ==========

    private function getNombreCuatrimestre($numero)
    {
        $nombres = [
            1 => 'Primer Cuatrimestre',
            2 => 'Segundo Cuatrimestre',
            3 => 'Tercer Cuatrimestre',
            4 => 'Cuarto Cuatrimestre',
            5 => 'Quinto Cuatrimestre',
            6 => 'Sexto Cuatrimestre'
        ];
        return $nombres[$numero] ?? "{$numero}° Cuatrimestre";
    }

    private function getNombreParcial($numero)
    {
        $nombres = [1 => 'Primer Parcial', 2 => 'Segundo Parcial', 3 => 'Tercer Parcial'];
        return $nombres[$numero] ?? "{$numero}° Parcial";
    }

    private function generarNombreActividad($tipo)
    {
        $nombresQuiz = [
            'Quiz: Vocabulario Básico', 'Quiz: Gramática Presente Simple', 'Quiz: Números en Inglés',
            'Quiz: Colores y Formas', 'Quiz: La Familia', 'Quiz: Comida y Bebidas',
            'Quiz: Días de la Semana', 'Quiz: Profesiones', 'Quiz: Animales',
            'Quiz: Partes del Cuerpo', 'Quiz: La Casa', 'Quiz: Transporte'
        ];

        $nombresCompletar = [
            'Completar: Oraciones Básicas', 'Completar: Verbos Regulares', 'Completar: Artículos',
            'Completar: Preposiciones', 'Completar: Adjetivos', 'Completar: Pronombres'
        ];

        return $tipo === 'quiz'
            ? $this->faker->randomElement($nombresQuiz)
            : $this->faker->randomElement($nombresCompletar);
    }

    private function generarDescripcionActividad($tipo)
    {
        $descripciones = [
            'quiz' => [
                'Evaluación de vocabulario fundamental en inglés',
                'Prueba de conocimientos gramaticales básicos',
                'Test de comprensión de estructuras del idioma',
                'Evaluación de conceptos importantes del curso',
                'Quiz interactivo para reforzar el aprendizaje'
            ],
            'completar' => [
                'Ejercicio para completar oraciones en inglés',
                'Práctica de estructuras gramaticales',
                'Actividad de refuerzo del vocabulario',
                'Ejercicio de aplicación práctica',
                'Completar frases con el vocabulario aprendido'
            ]
        ];

        return $this->faker->randomElement($descripciones[$tipo]);
    }

    private function generarContenidoActividad($tipo)
    {
        if ($tipo === 'quiz') {
            return $this->generarQuizDinamico();
        } else {
            return $this->generarCompletarDinamico();
        }
    }

    private function generarQuizDinamico()
    {
        $preguntasPool = [
            [
                'pregunta' => '¿Cómo se dice "Hola" en inglés?',
                'opciones' => ['A' => 'Hello', 'B' => 'Goodbye', 'C' => 'Please', 'D' => 'Thank you'],
                'respuesta_correcta' => 'A'
            ],
            [
                'pregunta' => '¿Cuál es el plural de "book"?',
                'opciones' => ['A' => 'books', 'B' => 'book', 'C' => 'bookes', 'D' => 'bookies'],
                'respuesta_correcta' => 'A'
            ],
            [
                'pregunta' => 'Completa: "I ___ a student"',
                'opciones' => ['A' => 'am', 'B' => 'is', 'C' => 'are', 'D' => 'be'],
                'respuesta_correcta' => 'A'
            ],
            [
                'pregunta' => 'She ___ to work every day.',
                'opciones' => ['A' => 'go', 'B' => 'goes', 'C' => 'going', 'D' => 'gone'],
                'respuesta_correcta' => 'B'
            ],
            [
                'pregunta' => '¿Cómo se dice "rojo" en inglés?',
                'opciones' => ['A' => 'Red', 'B' => 'Blue', 'C' => 'Green', 'D' => 'Yellow'],
                'respuesta_correcta' => 'A'
            ],
            [
                'pregunta' => 'They ___ students.',
                'opciones' => ['A' => 'am', 'B' => 'is', 'C' => 'are', 'D' => 'be'],
                'respuesta_correcta' => 'C'
            ],
            [
                'pregunta' => '¿Qué día viene después del Monday?',
                'opciones' => ['A' => 'Sunday', 'B' => 'Tuesday', 'C' => 'Wednesday', 'D' => 'Thursday'],
                'respuesta_correcta' => 'B'
            ],
            [
                'pregunta' => 'The cat ___ milk.',
                'opciones' => ['A' => 'drink', 'B' => 'drinks', 'C' => 'drinking', 'D' => 'drank'],
                'respuesta_correcta' => 'B'
            ]
        ];

        $numPreguntas = $this->faker->numberBetween(3, 6);
        $preguntasSeleccionadas = $this->faker->randomElements($preguntasPool, $numPreguntas);

        return [
            'tipo' => 'quiz',
            'contenido' => [
                'preguntas' => $preguntasSeleccionadas
            ]
        ];
    }

    private function generarCompletarDinamico()
    {
        $ejerciciosPool = [
            ['oracion' => 'My name ___ John.', 'respuesta' => 'is'],
            ['oracion' => 'She ___ from Mexico.', 'respuesta' => 'is'],
            ['oracion' => 'They ___ students.', 'respuesta' => 'are'],
            ['oracion' => 'I ___ English every day.', 'respuesta' => 'study'],
            ['oracion' => 'We ___ in the classroom.', 'respuesta' => 'are'],
            ['oracion' => 'He ___ to school by bus.', 'respuesta' => 'goes'],
            ['oracion' => 'The book ___ on the table.', 'respuesta' => 'is'],
            ['oracion' => 'You ___ very smart.', 'respuesta' => 'are']
        ];

        $numEjercicios = $this->faker->numberBetween(3, 5);
        $ejerciciosSeleccionados = $this->faker->randomElements($ejerciciosPool, $numEjercicios);

        return [
            'tipo' => 'completar',
            'contenido' => [
                'ejercicios' => $ejerciciosSeleccionados
            ]
        ];
    }

    private function mostrarResumen($profesores, $alumnos)
    {
        $this->command->info('✅ ¡Datos DINÁMICOS creados exitosamente!');
        $this->command->info('');
        $this->command->info('📋 RESUMEN:');
        $this->command->info('- Carreras: ' . Carrera::count());
        $this->command->info('- Cuatrimestres: ' . Cuatrimestre::count());
        $this->command->info('- Parciales: ' . Parcial::count());
        $this->command->info('- Materias: ' . Materia::count());
        $this->command->info('- Profesores: ' . User::count());
        $this->command->info('- Grupos: ' . Grupo::count());
        $this->command->info('- Alumnos: ' . Alumno::count());
        $this->command->info('- Inscripciones: ' . Inscripcion::count());
        $this->command->info('- Actividades: ' . Actividad::count());
        $this->command->info('');
        $this->command->info('🔑 DATOS DE PRUEBA PARA LOGIN:');

        // Mostrar algunos alumnos de ejemplo
        $alumnosEjemplo = collect($alumnos)->take(5);
        foreach ($alumnosEjemplo as $alumno) {
            $this->command->info("- Matrícula: {$alumno->matricula} ({$alumno->nombre} {$alumno->apellidos}) - {$alumno->cuatrimestre_actual}°");
        }

        $this->command->info('- Carrera: TICS (Tecnologías de la Información)');
        $this->command->info('');
        $this->command->info('👩‍🏫 PROFESORES (Password: 123456):');
        foreach (collect($profesores)->take(3) as $profesor) {
            $this->command->info("- {$profesor->name} ({$profesor->email})");
        }
        $this->command->info('');
        $this->command->info('🚀 SIGUIENTE PASO:');
        $this->command->info('Ir a: http://localhost:8000/alumnos');
        $this->command->info("Usar cualquier matrícula de arriba ↑");
    }
}
