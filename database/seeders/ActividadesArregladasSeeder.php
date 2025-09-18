<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Actividad;
use App\Models\Parcial;
use App\Models\Cuatrimestre;

class ActividadesArregladasSeeder extends Seeder
{
    public function run()
    {
        // Limpiar actividades existentes si es necesario
        // Actividad::truncate();

        // Obtener parciales existentes
        $parciales = Parcial::with('cuatrimestre')->get();

        if ($parciales->isEmpty()) {
            $this->command->info('❌ No hay parciales disponibles. Ejecuta primero el seeder de parciales.');
            return;
        }

        $actividadesPrueba = [
            [
                'nombre' => '🧠 Quiz: Present Simple',
                'descripcion' => 'Practica el presente simple en inglés con estas preguntas',
                'contenido' => [
                    'tipo' => 'quiz',
                    'contenido' => [
                        'preguntas' => [
                            [
                                'pregunta' => '¿Cuál es la forma correcta del presente simple en tercera persona?',
                                'opciones' => [
                                    'A' => 'He go to school',
                                    'B' => 'He goes to school',
                                    'C' => 'He going to school',
                                    'D' => 'He gone to school'
                                ],
                                'respuesta_correcta' => 'B'
                            ],
                            [
                                'pregunta' => '¿Qué significa "apple" en español?',
                                'opciones' => [
                                    'A' => 'Naranja',
                                    'B' => 'Manzana',
                                    'C' => 'Plátano',
                                    'D' => 'Uva'
                                ],
                                'respuesta_correcta' => 'B'
                            ],
                            [
                                'pregunta' => 'Completa: "I ___ a student"',
                                'opciones' => [
                                    'A' => 'am',
                                    'B' => 'is',
                                    'C' => 'are',
                                    'D' => 'be'
                                ],
                                'respuesta_correcta' => 'A'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'nombre' => '✏️ Completar: Verb To Be',
                'descripcion' => 'Completa las oraciones con la forma correcta del verbo to be',
                'contenido' => [
                    'tipo' => 'completar',
                    'contenido' => [
                        'ejercicios' => [
                            [
                                'oracion' => 'My name ___ Carlos.',
                                'respuesta' => 'is'
                            ],
                            [
                                'oracion' => 'They ___ from Mexico.',
                                'respuesta' => 'are'
                            ],
                            [
                                'oracion' => 'I ___ 20 years old.',
                                'respuesta' => 'am'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'nombre' => '🎯 Quiz: Colors and Numbers',
                'descripcion' => 'Test de colores y números en inglés',
                'contenido' => [
                    'tipo' => 'quiz',
                    'contenido' => [
                        'preguntas' => [
                            [
                                'pregunta' => '¿Cómo se dice "rojo" en inglés?',
                                'opciones' => [
                                    'A' => 'Blue',
                                    'B' => 'Red',
                                    'C' => 'Green',
                                    'D' => 'Yellow'
                                ],
                                'respuesta_correcta' => 'B'
                            ],
                            [
                                'pregunta' => '¿Cómo se dice "cinco" en inglés?',
                                'opciones' => [
                                    'A' => 'Four',
                                    'B' => 'Six',
                                    'C' => 'Five',
                                    'D' => 'Seven'
                                ],
                                'respuesta_correcta' => 'C'
                            ],
                            [
                                'pregunta' => '¿Cuál es el color de una banana?',
                                'opciones' => [
                                    'A' => 'Red',
                                    'B' => 'Blue',
                                    'C' => 'Green',
                                    'D' => 'Yellow'
                                ],
                                'respuesta_correcta' => 'D'
                            ],
                            [
                                'pregunta' => '¿Cómo se dice "negro" en inglés?',
                                'opciones' => [
                                    'A' => 'White',
                                    'B' => 'Black',
                                    'C' => 'Gray',
                                    'D' => 'Brown'
                                ],
                                'respuesta_correcta' => 'B'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'nombre' => '📝 Completar: Daily Activities',
                'descripcion' => 'Completa las oraciones sobre actividades diarias',
                'contenido' => [
                    'tipo' => 'completar',
                    'contenido' => [
                        'ejercicios' => [
                            [
                                'oracion' => 'I ___ breakfast at 7 AM.',
                                'respuesta' => 'eat'
                            ],
                            [
                                'oracion' => 'She ___ to school every day.',
                                'respuesta' => 'goes'
                            ],
                            [
                                'oracion' => 'We ___ homework in the evening.',
                                'respuesta' => 'do'
                            ],
                            [
                                'oracion' => 'He ___ TV after dinner.',
                                'respuesta' => 'watches'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'nombre' => '🚀 Quiz Avanzado: Mixed Grammar',
                'descripcion' => 'Quiz de gramática mixta para nivel intermedio',
                'contenido' => [
                    'tipo' => 'quiz',
                    'contenido' => [
                        'preguntas' => [
                            [
                                'pregunta' => 'Choose the correct past tense: "Yesterday I ___ to the park"',
                                'opciones' => [
                                    'A' => 'go',
                                    'B' => 'goes',
                                    'C' => 'went',
                                    'D' => 'going'
                                ],
                                'respuesta_correcta' => 'C'
                            ],
                            [
                                'pregunta' => 'Which is the correct plural form?',
                                'opciones' => [
                                    'A' => 'childs',
                                    'B' => 'children',
                                    'C' => 'childrens',
                                    'D' => 'child'
                                ],
                                'respuesta_correcta' => 'B'
                            ],
                            [
                                'pregunta' => 'Complete: "She ___ been studying for 2 hours"',
                                'opciones' => [
                                    'A' => 'have',
                                    'B' => 'has',
                                    'C' => 'is',
                                    'D' => 'was'
                                ],
                                'respuesta_correcta' => 'B'
                            ],
                            [
                                'pregunta' => 'What\'s the comparative of "good"?',
                                'opciones' => [
                                    'A' => 'gooder',
                                    'B' => 'more good',
                                    'C' => 'better',
                                    'D' => 'best'
                                ],
                                'respuesta_correcta' => 'C'
                            ],
                            [
                                'pregunta' => 'Choose the correct future form: "Tomorrow it ___ rain"',
                                'opciones' => [
                                    'A' => 'will',
                                    'B' => 'going',
                                    'C' => 'is',
                                    'D' => 'was'
                                ],
                                'respuesta_correcta' => 'A'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Crear actividades para cada parcial
        foreach ($parciales as $parcial) {
            $this->command->info("📚 Creando actividades para: {$parcial->nombre} - {$parcial->cuatrimestre->nombre}");
            
            foreach ($actividadesPrueba as $actividadData) {
                try {
                    $actividad = Actividad::create([
                        'nombre' => $actividadData['nombre'],
                        'descripcion' => $actividadData['descripcion'],
                        'parcial_id' => $parcial->id,
                        'contenido' => $actividadData['contenido'],
                        'activa' => true
                    ]);

                    $this->command->info("✅ Creada: {$actividad->nombre}");

                    // Verificar que el contenido se guardó correctamente
                    if (is_array($actividad->contenido)) {
                        $tipo = $actividad->contenido['tipo'] ?? 'desconocido';
                        $cantidadItems = 0;
                        
                        if ($tipo === 'quiz' && isset($actividad->contenido['contenido']['preguntas'])) {
                            $cantidadItems = count($actividad->contenido['contenido']['preguntas']);
                        } elseif ($tipo === 'completar' && isset($actividad->contenido['contenido']['ejercicios'])) {
                            $cantidadItems = count($actividad->contenido['contenido']['ejercicios']);
                        }
                        
                        $this->command->info("   📊 Tipo: {$tipo}, Items: {$cantidadItems}");
                    } else {
                        $this->command->warn("   ⚠️ Contenido no es array: " . gettype($actividad->contenido));
                    }
                    
                } catch (\Exception $e) {
                    $this->command->error("❌ Error al crear {$actividadData['nombre']}: " . $e->getMessage());
                }
            }
        }

        $totalActividades = Actividad::count();
        $this->command->info("🎉 SEEDER COMPLETADO: {$totalActividades} actividades totales en la base de datos");
        
        // Mostrar estadísticas
        $this->mostrarEstadisticas();
    }

    private function mostrarEstadisticas()
    {
        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("📊 ESTADÍSTICAS DE ACTIVIDADES");
        $this->command->info(str_repeat('=', 50));
        
        $actividades = Actividad::with('parcial.cuatrimestre')->get();
        
        foreach ($actividades as $actividad) {
            $contenido = $actividad->contenido;
            $tipo = is_array($contenido) ? ($contenido['tipo'] ?? 'manual') : 'manual';
            
            $items = 0;
            if ($tipo === 'quiz' && isset($contenido['contenido']['preguntas'])) {
                $items = count($contenido['contenido']['preguntas']);
            } elseif ($tipo === 'completar' && isset($contenido['contenido']['ejercicios'])) {
                $items = count($contenido['contenido']['ejercicios']);
            }
            
            $parcialInfo = $actividad->parcial ? 
                "{$actividad->parcial->nombre} ({$actividad->parcial->cuatrimestre->nombre})" : 
                "Sin parcial";
                
            $this->command->info("📝 {$actividad->nombre}");
            $this->command->info("   Parcial: {$parcialInfo}");
            $this->command->info("   Tipo: {$tipo} | Items: {$items} | Activa: " . ($actividad->activa ? 'SÍ' : 'NO'));
            $this->command->info("");
        }
        
        $totalQuiz = $actividades->filter(function($a) {
            return is_array($a->contenido) && ($a->contenido['tipo'] ?? '') === 'quiz';
        })->count();
        
        $totalCompletar = $actividades->filter(function($a) {
            return is_array($a->contenido) && ($a->contenido['tipo'] ?? '') === 'completar';
        })->count();
        
        $this->command->info("🎯 RESUMEN:");
        $this->command->info("   Quiz: {$totalQuiz}");
        $this->command->info("   Completar: {$totalCompletar}");
        $this->command->info("   Total: " . $actividades->count());
        $this->command->info(str_repeat('=', 50) . "\n");
    }
}