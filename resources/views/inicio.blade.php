@extends('layouts.app')

@section('content')
    <style>
        /* Fondo general */
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
        }
        .container {
            margin-top: 60px;
        }
        /* Tarjeta principal del examen */
        .activity-test {
            border: none;
            border-radius: 18px;
            margin: 30px auto;
            padding: 35px 40px 30px 40px;
            background: #fff;
            box-shadow: 0 8px 32px 0 rgba(30,132,125,0.25), 0 1.5px 8px rgba(30,132,125,0.10);
            max-width: 700px;
            position: relative;
            overflow: hidden;
        }
        .activity-test:before {
            content: "";
            position: absolute;
            top: -60px; left: -60px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, #1E847D 60%, transparent 100%);
            opacity: 0.08;
            z-index: 0;
        }
        .activity-test h3 {
            font-family: "Montserrat", Verdana, Helvetica, sans-serif;
            color: #1E847D;
            text-align: center;
            font-size: 2.1rem;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .activity-test p {
            text-align: center;
            color: #555;
            font-size: 1.1rem;
        }
        /* Preguntas */
        .question {
            border: none;
            border-radius: 12px;
            margin: 18px 0;
            padding: 18px 22px 14px 22px;
            background: #f4fafd;
            box-shadow: 0 2px 8px rgba(30,132,125,0.07);
            position: relative;
            transition: box-shadow 0.2s;
        }
        .question:hover {
            box-shadow: 0 4px 16px rgba(30,132,125,0.15);
        }
        .question h3 {
            color: #1E847D;
            font-size: 1.2rem;
            margin-bottom: 4px;
            text-align: left;
        }
        .question p {
            color: #333;
            font-size: 1.08rem;
            text-align: left;
            margin-bottom: 10px;
        }
        .question ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .question li {
            margin-bottom: 7px;
        }
        .question input[type="radio"] {
            accent-color: #1E847D;
            transform: scale(1.2);
            margin-right: 7px;
        }
        .question label {
            font-size: 1.05rem;
            cursor: pointer;
            transition: color 0.15s;
        }
        .question input[type="radio"]:checked + label,
        .question input[type="radio"]:checked ~ label {
            color: #1E847D;
            font-weight: 600;
        }
        /* Botón enviar */
        .submit button {
            background: linear-gradient(90deg, #1E847D 60%, #2CA6A4 100%);
            color: #fff;
            border: none;
            padding: 13px 38px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 1.15rem;
            font-family: "Montserrat", Verdana, Helvetica, sans-serif;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(30,132,125,0.13);
            transition: background 0.2s, transform 0.1s;
        }
        .submit button:hover {
            background: linear-gradient(90deg, #2CA6A4 60%, #1E847D 100%);
            transform: translateY(-2px) scale(1.04);
        }
        /* Modal resultados */
        #resultadosModal {
            display: none;
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background: #fff;
            border: 2.5px solid #1E847D;
            border-radius: 14px;
            box-shadow: 0 8px 32px 0 rgba(30,132,125,0.18);
            padding: 28px 24px 22px 24px;
            z-index: 1000;
            animation: modalIn 0.3s;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: translate(-50%, -60%) scale(0.95);}
            to { opacity: 1; transform: translate(-50%, -50%) scale(1);}
        }
        #resultadosModal h3 {
            color: #1E847D;
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 12px;
        }
        #resultadosContenido p {
            font-size: 1.08rem;
            margin: 7px 0;
        }
        #resultadosContenido span {
            font-weight: 600;
        }
        #resultadosModal button {
            background: #1E847D;
            color: #fff;
            border: none;
            padding: 10px 28px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 1.08rem;
            font-family: "Montserrat", Verdana, Helvetica, sans-serif;
            font-weight: 500;
            transition: background 0.2s;
        }
        #resultadosModal button:hover {
            background: #2CA6A4;
        }
        #overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(30,132,125,0.18);
            z-index: 999;
        }
        /* Responsive */
        @media (max-width: 600px) {
            .activity-test {
                padding: 18px 6px 18px 6px;
            }
            #resultadosModal {
                width: 95vw;
                padding: 18px 6px 18px 6px;
            }
        }
    </style>
    <div class="container">
        <h2 style="color:#1E847D; font-family: Montserrat, Verdana, Helvetica, sans-serif; font-size:2.2rem; text-align:center; margin-bottom: 18px; letter-spacing:1px;">Bienvenido a la plataforma de actividades</h2>
        <!-- Actividad de prueba -->
        <div class="activity-test">
            <h3>Examen de Prueba</h3>
            <p style="margin-bottom: 18px;">Demuestra tus conocimientos. Lee cada pregunta cuidadosamente y selecciona la respuesta correcta. ¡Éxito!</p>
            <form id="quizForm">
                <!-- Pregunta 1 -->
                <div class="question" data-index="0" style="display:block;">
                    <h3>Connectors</h3>
                    <p>It was raining, ______ he opened the umbrella.</p>
                    <ul>
                        <li><label><input name="quest_27297" type="radio" value="while" /> while</label></li>
                        <li><label><input name="quest_27297" type="radio" value="so" /> so</label></li>
                        <li><label><input name="quest_27297" type="radio" value="but" /> but</label></li>
                    </ul>
                </div>
                <!-- Pregunta 2 -->
                <div class="question" data-index="1" style="display:none;">
                    <h3>Connectors</h3>
                    <p>Sue likes eating pizza _____ pasta.</p>
                    <ul>
                        <li><label><input name="quest_27298" type="radio" value="because" /> because</label></li>
                        <li><label><input name="quest_27298" type="radio" value="when" /> when</label></li>
                        <li><label><input name="quest_27298" type="radio" value="and" /> and</label></li>
                    </ul>
                </div>
                <!-- Pregunta 3 -->
                <div class="question" data-index="2" style="display:none;">
                    <h3>Connectors</h3>
                    <p>We went fishing last Saturday _____ we didn't catch anything.</p>
                    <ul>
                        <li><label><input name="quest_27299" type="radio" value="because" /> because</label></li>
                        <li><label><input name="quest_27299" type="radio" value="but" /> but</label></li>
                        <li><label><input name="quest_27299" type="radio" value="when" /> when</label></li>
                    </ul>
                </div>
                <!-- Pregunta 4 -->
                <div class="question" data-index="3" style="display:none;">
                    <h3>Connectors</h3>
                    <p>Tom was embarrassed ___ he didn't know the answer to the question.</p>
                    <ul>
                        <li><label><input name="quest_27300" type="radio" value="so" /> so</label></li>
                        <li><label><input name="quest_27300" type="radio" value="and" /> and</label></li>
                        <li><label><input name="quest_27300" type="radio" value="because" /> because</label></li>
                    </ul>
                </div>
                <!-- Pregunta 5 -->
                <div class="question" data-index="4" style="display:none;">
                    <h3>Intensifiers</h3>
                    <p>too much is.....</p>
                    <ul>
                        <li><label><input name="quest_27313" type="radio" value="poco" /> poco</label></li>
                        <li><label><input name="quest_27313" type="radio" value="demasiado" /> demasiado</label></li>
                        <li><label><input name="quest_27313" type="radio" value="tanto" /> tanto</label></li>
                        <li><label><input name="quest_27313" type="radio" value="mucho" /> mucho</label></li>
                    </ul>
                </div>
                <!-- Pregunta 6 -->
                <div class="question" data-index="5" style="display:none;">
                    <h3>Relative Clauses</h3>
                    <p>A waiter is a person ______ job is to serve customers in a restaurant.</p>
                    <ul>
                        <li><label><input name="quest_27317" type="radio" value="that" /> that</label></li>
                        <li><label><input name="quest_27317" type="radio" value="whoose" /> whoose</label></li>
                        <li><label><input name="quest_27317" type="radio" value="where" /> where</label></li>
                    </ul>
                </div>
                <div class="navigation" style="display:flex;justify-content:space-between;align-items:center;margin-top:22px;">
                    <button type="button" id="prevBtn" style="display:none;background:#eee;color:#1E847D;border:none;padding:10px 24px;border-radius:7px;cursor:pointer;font-size:1rem;font-family:Montserrat;font-weight:600;" onclick="cambiarPregunta(-1)">Anterior</button>
                    <div id="nextBtnContainer" style="flex:1;display:flex;justify-content:flex-end;">
                        <button type="button" id="nextBtn" style="background:linear-gradient(90deg,#1E847D 60%,#2CA6A4 100%);color:#fff;border:none;padding:10px 24px;border-radius:7px;cursor:pointer;font-size:1rem;font-family:Montserrat;font-weight:600;" onclick="cambiarPregunta(1)">Siguiente</button>
                    </div>
                    <button type="button" id="submitBtn" style="display:none;background:linear-gradient(90deg,#1E847D 60%,#2CA6A4 100%);color:#fff;border:none;padding:10px 24px;border-radius:7px;cursor:pointer;font-size:1rem;font-family:Montserrat;font-weight:600;" onclick="mostrarResultados()">Enviar respuestas</button>
                </div>
            </form>
        </div>
        <!-- Ventana flotante para resultados -->
        <div id="resultadosModal">
            <h3>Resultados</h3>
            <div id="resultadosContenido"></div>
            <button onclick="cerrarResultados()">Cerrar</button>
        </div>
        <div id="overlay"></div>
    </div>
    <script>
    const respuestasCorrectas = {
        quest_27297: 'so',
        quest_27298: 'and',
        quest_27299: 'but',
        quest_27300: 'so',
        quest_27313: 'demasiado',
        quest_27317: 'whoose',
    };

    let preguntaActual = 0;
    const totalPreguntas = 6;

    function mostrarPregunta(index) {
        const preguntas = document.querySelectorAll('.question');
        preguntas.forEach((q, i) => {
            q.style.display = (i === index) ? 'block' : 'none';
        });
        // Botón anterior
        document.getElementById('prevBtn').style.display = (index > 0) ? 'inline-block' : 'none';
        // Botón siguiente
        document.getElementById('nextBtn').style.display = (index < totalPreguntas - 1) ? 'inline-block' : 'none';
        // Botón enviar
        document.getElementById('submitBtn').style.display = (index === totalPreguntas - 1) ? 'inline-block' : 'none';

        // Alinear el botón "Siguiente" a la derecha solo en la primera pregunta
        const nextBtnContainer = document.getElementById('nextBtnContainer');
        if (index === 0) {
            nextBtnContainer.style.justifyContent = 'flex-end';
        } else {
            nextBtnContainer.style.justifyContent = 'center';
        }

        // Deshabilitar el botón "Siguiente" si no hay respuesta seleccionada
        actualizarEstadoBotonSiguiente();
    }

    function cambiarPregunta(delta) {
        const preguntas = document.querySelectorAll('.question');
        // Guardar respuesta seleccionada (opcional, ya que los radios mantienen estado)
        preguntaActual += delta;
        if (preguntaActual < 0) preguntaActual = 0;
        if (preguntaActual >= totalPreguntas) preguntaActual = totalPreguntas - 1;
        mostrarPregunta(preguntaActual);
    }

    function actualizarEstadoBotonSiguiente() {
        const preguntas = document.querySelectorAll('.question');
        const pregunta = preguntas[preguntaActual];
        const radios = pregunta.querySelectorAll('input[type="radio"]');
        let respondida = false;
        radios.forEach(radio => {
            if (radio.checked) respondida = true;
        });
        const nextBtn = document.getElementById('nextBtn');
        if (preguntaActual < totalPreguntas - 1) {
            nextBtn.disabled = !respondida;
            nextBtn.style.opacity = respondida ? '1' : '0.5';
            nextBtn.style.pointerEvents = respondida ? 'auto' : 'none';
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        mostrarPregunta(preguntaActual);
        // Escuchar cambios en los radios para habilitar el botón "Siguiente"
        document.querySelectorAll('.question input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', actualizarEstadoBotonSiguiente);
        });
    });

    function mostrarResultados() {
        const form = document.getElementById('quizForm');
        const formData = new FormData(form);
        let resultados = '';
        let preguntaNumero = 1;
        let correctas = 0;
        for (const [pregunta, respuestaCorrecta] of Object.entries(respuestasCorrectas)) {
            const respuestaUsuario = formData.get(pregunta);
            if (respuestaUsuario === respuestaCorrecta) {
                resultados += `<p>Pregunta ${preguntaNumero}: <span style="color: #1E847D;">Correcto</span></p>`;
                correctas++;
            } else {
                resultados += `<p>Pregunta ${preguntaNumero}: <span style="color: #e74c3c;">Incorrecto</span></p>`;
            }
            preguntaNumero++;
        }
        resultados = `<p style="font-size:1.18rem; font-weight:600; margin-bottom:10px;">Calificación: ${correctas} / ${Object.keys(respuestasCorrectas).length}</p>` + resultados;
        document.getElementById('resultadosContenido').innerHTML = resultados;
        document.getElementById('resultadosModal').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function cerrarResultados() {
        document.getElementById('resultadosModal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
    </script>
@endsection

