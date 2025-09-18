-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-08-2025 a las 02:06:08
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_ingles2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `parcial_id` bigint(20) UNSIGNED NOT NULL,
  `grupo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contenido` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contenido`)),
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_disponible` date DEFAULT NULL,
  `fecha_inicio_parcial` date DEFAULT NULL,
  `fecha_fin_parcial` date DEFAULT NULL,
  `link_publico` varchar(100) DEFAULT NULL,
  `acceso_publico` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_limite` timestamp NULL DEFAULT NULL,
  `intentos_permitidos` int(11) NOT NULL DEFAULT 3,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `nombre`, `descripcion`, `imagen`, `parcial_id`, `grupo_id`, `contenido`, `activa`, `fecha_disponible`, `fecha_inicio_parcial`, `fecha_fin_parcial`, `link_publico`, `acceso_publico`, `fecha_limite`, `intentos_permitidos`, `created_at`, `updated_at`) VALUES
(1, 'Quiz Present Simple', 'Quiz Present Simple', 'actividades/1752646837_Present_Simple.jpg', 1, NULL, '{\"tipo\":\"quiz\",\"contenido\":{\"preguntas\":[{\"pregunta\":\"Quiz Present Simple\",\"opciones\":{\"A\":\"A\",\"B\":\"B\",\"C\":\"C\",\"D\":\"D\"},\"correcta\":\"A\"},{\"pregunta\":\"Quiz Present Simple\",\"opciones\":{\"A\":\"A\",\"B\":\"S\",\"C\":\"S\",\"D\":\"A\"},\"correcta\":\"D\"}]}}', 1, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-07-16 13:20:37', '2025-07-16 13:20:37'),
(2, 'Quiz Present Simple', 'asdasdas', 'actividades/1752648543_Present_Simple.jpg', 1, NULL, '{\"tipo\":\"quiz\",\"contenido\":{\"preguntas\":[{\"pregunta\":\"asdas\",\"opciones\":{\"A\":\"asdas\",\"B\":\"dasd\",\"C\":\"dasdad\",\"D\":\"a\"},\"correcta\":\"A\"},{\"pregunta\":\"das\",\"opciones\":{\"A\":\"dasd\",\"B\":\"dsadas\",\"C\":\"asdas\",\"D\":\"dsa\"},\"correcta\":\"B\"}]}}', 0, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-07-16 13:49:03', '2025-07-16 13:49:03'),
(3, 'cdscsd', 'dscdscds', 'actividades/1752648957_Present_Simple.jpg', 1, NULL, '{\"tipo\":\"quiz\",\"contenido\":{\"preguntas\":[{\"pregunta\":\"dsds\",\"opciones\":{\"A\":\"sdd\",\"B\":\"sdsds\",\"C\":\"sdsd\",\"D\":\"sd\"},\"correcta\":\"B\"},{\"pregunta\":\"dsdsa\",\"opciones\":{\"A\":\"dsa\",\"B\":\"dsad\",\"C\":\"dsad\",\"D\":\"sad\"},\"correcta\":\"C\"},{\"pregunta\":\"sdas\",\"opciones\":{\"A\":\"dsad\",\"B\":\"asda\",\"C\":\"sdsada\",\"D\":\"sda\"},\"correcta\":\"A\"}]}}', 0, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-07-16 13:55:57', '2025-07-16 13:55:57'),
(9, 'English Practice', 'asxasd', 'actividades/1752860162_English_actividty.jpg', 1, NULL, '{\"tipo\":\"quiz\",\"contenido\":{\"preguntas\":[{\"pregunta\":\"sadas\",\"opciones\":{\"A\":\"asd\",\"B\":\"asdas\",\"C\":\"asd\",\"D\":\"asd\"},\"correcta\":\"A\"},{\"pregunta\":\"as\",\"opciones\":{\"A\":\"adsd\",\"B\":\"a\",\"C\":\"das\",\"D\":\"dsad\"},\"correcta\":\"A\"},{\"pregunta\":\"asdsa\",\"opciones\":{\"A\":\"das\",\"B\":\"dasd\",\"C\":\"dasd\",\"D\":\"as\"},\"correcta\":\"C\"},{\"pregunta\":\"sc\",\"opciones\":{\"A\":\"sc\",\"B\":\"cssc\",\"C\":\"scs\",\"D\":\"sc\"},\"correcta\":\"D\"},{\"pregunta\":\"cs\",\"opciones\":{\"A\":\"cs\",\"B\":\"sc\",\"C\":\"cs\",\"D\":\"c\"},\"correcta\":\"B\"},{\"pregunta\":\"s\",\"opciones\":{\"A\":\"cs\",\"B\":\"cs\",\"C\":\"css\",\"D\":\"cs\"},\"correcta\":\"B\"},{\"pregunta\":\"cs\",\"opciones\":{\"A\":\"sc\",\"B\":\"sc\",\"C\":\"sc\",\"D\":\"sc\"},\"correcta\":\"A\"}]}}', 1, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-07-19 00:36:02', '2025-07-19 01:24:15'),
(14, 'English Activity', 'This is a english activity', 'actividades/1753472489_English_actividty.jpg', 1, NULL, '{\"tipo\":\"quiz\",\"contenido\":{\"preguntas\":[{\"pregunta\":\"sadsa\",\"opciones\":{\"A\":\"das\",\"B\":\"dasd\",\"C\":\"adsa\",\"D\":\"ad\"},\"correcta\":\"A\"}]}}', 1, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-07-24 02:40:03', '2025-07-26 02:42:39'),
(17, 'Ejercicio completar', 'Descripción', 'actividades/1753299949_completar.jpg', 2, NULL, '{\"tipo\":\"completar\",\"contenido\":{\"ejercicios\":[{\"oracion\":\"I want __ Coffe\",\"respuesta\":\"a\"}]}}', 1, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-07-24 02:45:49', '2025-07-24 02:45:49'),
(19, 'Present Simple', 'Present simple Quiz', 'actividades/1753327767_Present_Simple.jpg', 1, NULL, '{\"tipo\":\"quiz\",\"contenido\":{\"preguntas\":[{\"pregunta\":\"\\u00bfCu\\u00e1l es la forma correcta del presente simple en tercera persona?\",\"opciones\":{\"A\":\"He go to school\",\"B\":\"He goes to school\",\"C\":\"He going to school\",\"D\":\"He gone to school\"},\"correcta\":\"B\"},{\"pregunta\":\"\\u00bfQu\\u00e9 significa \\\"apple\\\" en espa\\u00f1ol?\",\"opciones\":{\"A\":\"Naranja\",\"B\":\"Manzana\",\"C\":\"Pl\\u00e1tano\",\"D\":\"Uva\"},\"correcta\":\"B\"},{\"pregunta\":\"Complete: \\\"I ___ a student\\\"\",\"opciones\":{\"A\":\"am\",\"B\":\"is\",\"C\":\"are\",\"D\":\"be\"},\"correcta\":\"A\"}]}}', 1, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-07-24 10:29:27', '2025-07-26 02:42:59'),
(27, 'actividad', 'actividad', 'actividades/1754605724_Present_Simple.jpg', 1, NULL, '{\"tipo\":\"importado\",\"preguntas\":[{\"pregunta\":\"IMAGEN According to picture, choose the best adjective I enjoyed watching the _______ elephant in the zoo.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" tiny\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" huge\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" small\"},\"correcta\":\"F\"},{\"pregunta\":\"IMAGEN According to picture, choose the best adjective The last week I watched coco. it is an __________ movie\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" animated\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" horror\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" action\"},\"correcta\":\"F\"},{\"pregunta\":\"IMAGEN According to picture, choose the best adjective My dog doesn t want to play with me, I have a _______ pet.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" lazy\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" active\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" weird\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"My sister is six years __________ than me.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" young\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" younger\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" youngest\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the\\u00a0correct\\u00a0sentence\\u00a0in the\\u00a0negative form\\u00a0of the \'Present Simple\' :\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" I doesn\'t like bananas.\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" She don\'t draw that well.\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" I don\'t take a shower in the morning.\"},\"correcta\":\"F\"},{\"pregunta\":\"What role in a sentence plays the word in\\u00a0bold? \\\"My friend\\u00a0eats\\u00a0sushi every Sunday.\\\"\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Verb\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Subject\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Complement\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"What role in a sentence play the words in\\u00a0red? \\\"My mom\\u00a0loves to paint landscapes.\\\"\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Verb\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Subject\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Complement\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. They ___ school.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" love\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" loves\"},\"correcta\":\"D\"},{\"pregunta\":\"Choose the correct option\\u00a0 She ____ two languages at school.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" study\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" studies\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" studys\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"\\u00a0We are at school. \\u00a0______ school is very nice.\\u00a0\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Our\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" His\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Her\"},\"correcta\":\"F\"},{\"pregunta\":\"Helen is __________ ballet dancer in our city.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" gooder\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" better\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" the best\"},\"correcta\":\"F\"},{\"pregunta\":\"What do we use the \'Simple Present Tense\'\\u00a0for?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" To talk about daily activities, past future actions.\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" To talk about daily activities, routines, facts and present actions.\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" To talk about actions.\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the\\u00a0correct sentence\\u00a0in\\u00a0positive form\\u00a0of\\u00a0\'the Present Simple\' :\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\"\",\"C\":\"\\\"font-size : 1rem; -webkit-text-size-adjust : 100 ;\\\" I was happy yesterday.\",\"D\":\"\\\"ltr\\\" style\",\"E\":\"\\\"text-align : left;\\\" She\'s eating some pizza.\",\"F\":\"\\\"ltr\\\" style\"},\"correcta\":\"F\"},{\"pregunta\":\"He _____ soccer in the afternoons. (play)\",\"opciones\":{\"A\":\"plays\"},\"correcta\":\"A\"},{\"pregunta\":\"Lisa _____ up at 6 :00am everyday. (wake)\\u00a0\",\"opciones\":{\"A\":\"wakes\"},\"correcta\":\"A\"},{\"pregunta\":\"Sandwich, carrot, and hamburger are ____________ nouns.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Countable\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Uncountable\"},\"correcta\":\"D\"},{\"pregunta\":\"Juice, cheese and bread are ___________ nouns.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Countable\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Uncountable\"},\"correcta\":\"D\"},{\"pregunta\":\"Look at the picture and choose countable nouns. IMAGEN\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" meatballs, panakes, boiled eggs\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" butter, toast, cheese\"},\"correcta\":\"D\"},{\"pregunta\":\"Sandwich, carrot, and hamburger are ____________ nouns.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Countable\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Uncountable\"},\"correcta\":\"D\"},{\"pregunta\":\"Juice, cheese and bread are ___________ nouns.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Countable\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Uncountable\"},\"correcta\":\"D\"},{\"pregunta\":\"Look at the picture and choose countable nouns. IMAGEN\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" meatballs, panakes, boiled eggs\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" butter, toast, cheese\"},\"correcta\":\"D\"},{\"pregunta\":\"Sandwich, carrot, and hamburger are ____________ nouns.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Countable\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Uncountable\"},\"correcta\":\"D\"},{\"pregunta\":\"Juice, cheese and bread are ___________ nouns.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Countable\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Uncountable\"},\"correcta\":\"D\"},{\"pregunta\":\"Look at the picture and choose countable nouns. IMAGEN\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" meatballs, panakes, boiled eggs\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" butter, toast, cheese\"},\"correcta\":\"D\"},{\"pregunta\":\"Choose the correct option. I ______ football.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" am playing\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" is playing\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" are playing\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. You _________ mango.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" am eating\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" are eating\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" is eating\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. She _________ in her bedroom.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" is sleeping\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" are sweemming\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" am working\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. Are you ________ japanese?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" study\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" studying\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" studied\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. I ______ football.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" am playing\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" is playing\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" are playing\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. You _________ mango.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" am eating\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" are eating\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" is eating\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. She _________ in her bedroom.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" is sleeping\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" are sweemming\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" am working\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. Are you ________ japanese?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" study\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" studying\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" studied\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. I ______ football.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" am playing\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" is playing\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" are playing\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. You _________ mango.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" am eating\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" are eating\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" is eating\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. She _________ in her bedroom.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" is sleeping\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" are sweemming\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" am working\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. Are you ________ japanese?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" study\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" studying\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" studied\"},\"correcta\":\"F\"},{\"pregunta\":\"PUT THE VERBS IN THE CORRECT VERB FORMS. Andy ________ the family car.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" wash\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" washes\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" wash\"},\"correcta\":\"F\"},{\"pregunta\":\"PUTIN THE CORRECT VERB FORMS. Every morning my mother __________ at 6 am.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" get up\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" gets up\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" get ups\"},\"correcta\":\"F\"},{\"pregunta\":\"PUT THE CORRECT VERB FORMS. His friend ___________ to school.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" go\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" gos\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" goes\"},\"correcta\":\"F\"},{\"pregunta\":\"WHICH SENTENCES QUESTION ARE CORRECT? He can bike ride.He can ride a bike.He can rides a bike.\",\"opciones\":{\"A\":\"He can bike ride.\",\"B\":\"\\\"ltr\\\" style\",\"C\":\"\\\"text-align : left;\\\" He can ride a bike.\",\"D\":\"\\\"ltr\\\" style\",\"E\":\"\\\"text-align : left;\\\" He can rides a bike\"},\"correcta\":\"E\"},{\"pregunta\":\"WHICH NEGATIVE SENTENCES ARE CORRECT? He do not reads a book. He does not read a book. He is not reading a book.\\u00a0\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" He do not reads a book.\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" He does not read a book.\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" He is not reading a book.\"},\"correcta\":\"F\"},{\"pregunta\":\"WHICH QUESTION IS IN THE PRESENT SIMPLE? Do she work in an office?Work she in an office?Does she work in an office?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Do she work in an office?\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Work she in an office?\",\"E\":\"Does she work in an office?\"},\"correcta\":\"E\"},{\"pregunta\":\"WHICH VERB FORMS ARE USED IN THE PRESENT SIMPLE? InfinitiveInfinitive sInfinitive ed\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Infinivite\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Infinitive s\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Infinitive ed\"},\"correcta\":\"F\"},{\"pregunta\":\"PUT THE VERBS IN THE CORRECT VERB FORMS. Andy ________ the family car.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" wash\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" washes\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" wash\"},\"correcta\":\"F\"},{\"pregunta\":\"PUTIN THE CORRECT VERB FORMS. Every morning my mother __________ at 6 am.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" get up\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" gets up\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" get ups\"},\"correcta\":\"F\"},{\"pregunta\":\"PUT THE CORRECT VERB FORMS. His friend ___________ to school.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" go\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" gos\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" goes\"},\"correcta\":\"F\"},{\"pregunta\":\"WHICH SENTENCES QUESTION ARE CORRECT? He can bike ride.He can ride a bike.He can rides a bike.\",\"opciones\":{\"A\":\"He can bike ride.\",\"B\":\"\\\"ltr\\\" style\",\"C\":\"\\\"text-align : left;\\\" He can ride a bike.\",\"D\":\"\\\"ltr\\\" style\",\"E\":\"\\\"text-align : left;\\\" He can rides a bike\"},\"correcta\":\"E\"},{\"pregunta\":\"WHICH NEGATIVE SENTENCES ARE CORRECT? He do not reads a book. He does not read a book. He is not reading a book.\\u00a0\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" He do not reads a book.\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" He does not read a book.\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" He is not reading a book.\"},\"correcta\":\"F\"},{\"pregunta\":\"WHICH QUESTION IS IN THE PRESENT SIMPLE? Do she work in an office?Work she in an office?Does she work in an office?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Do she work in an office?\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Work she in an office?\",\"E\":\"Does she work in an office?\"},\"correcta\":\"E\"},{\"pregunta\":\"WHICH VERB FORMS ARE USED IN THE PRESENT SIMPLE? InfinitiveInfinitive sInfinitive ed\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Infinivite\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Infinitive s\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Infinitive ed\"},\"correcta\":\"F\"},{\"pregunta\":\"PUT THE VERBS IN THE CORRECT VERB FORMS. Andy ________ the family car.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" wash\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" washes\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" wash\"},\"correcta\":\"F\"},{\"pregunta\":\"PUTIN THE CORRECT VERB FORMS. Every morning my mother __________ at 6 am.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" get up\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" gets up\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" get ups\"},\"correcta\":\"F\"},{\"pregunta\":\"PUT THE CORRECT VERB FORMS. His friend ___________ to school.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" go\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" gos\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" goes\"},\"correcta\":\"F\"},{\"pregunta\":\"WHICH SENTENCES QUESTION ARE CORRECT? He can bike ride.He can ride a bike.He can rides a bike.\",\"opciones\":{\"A\":\"He can bike ride.\",\"B\":\"\\\"ltr\\\" style\",\"C\":\"\\\"text-align : left;\\\" He can ride a bike.\",\"D\":\"\\\"ltr\\\" style\",\"E\":\"\\\"text-align : left;\\\" He can rides a bike\"},\"correcta\":\"E\"},{\"pregunta\":\"WHICH NEGATIVE SENTENCES ARE CORRECT? He do not reads a book. He does not read a book. He is not reading a book.\\u00a0\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" He do not reads a book.\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" He does not read a book.\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" He is not reading a book.\"},\"correcta\":\"F\"},{\"pregunta\":\"WHICH QUESTION IS IN THE PRESENT SIMPLE? Do she work in an office?Work she in an office?Does she work in an office?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Do she work in an office?\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Work she in an office?\",\"E\":\"Does she work in an office?\"},\"correcta\":\"E\"},{\"pregunta\":\"WHICH VERB FORMS ARE USED IN THE PRESENT SIMPLE? InfinitiveInfinitive sInfinitive ed\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Infinivite\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Infinitive s\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Infinitive ed\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. Do you need _______ help?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" much\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" many\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" some\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. There are ________ books on the shelf.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" much\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" some\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" any\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. There isn\'t ________ cheese for me. It\'s unfair.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" many\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" any\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" some\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. Do you need _______ help?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" much\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" many\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" some\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. There are ________ books on the shelf.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" much\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" some\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" any\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. There isn\'t ________ cheese for me. It\'s unfair.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" many\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" any\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" some\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. Do you need _______ help?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" much\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" many\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" some\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. There are ________ books on the shelf.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" much\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" some\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" any\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. There isn\'t ________ cheese for me. It\'s unfair.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" many\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" any\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" some\"},\"correcta\":\"F\"},{\"pregunta\":\"FORM QUESTIONSIN THE PRESENT SIMPLE. Frank to read comics\",\"opciones\":{\"A\":\"Frank reads comics\"},\"correcta\":\"A\"},{\"pregunta\":\"FORM A NEGATIVE SENTENCE. Mr. Smith teaches French.\",\"opciones\":{\"A\":\"Mr. Smith doesn\'t teach French.\",\"B\":\"Mr. Smith does not teach French.\"},\"correcta\":\"B\"},{\"pregunta\":\"Put the verbs in brackets into the gaps. Jill ____________ two children. (to have)\",\"opciones\":{\"A\":\"have\",\"B\":\"has\",\"C\":\"to have\"},\"correcta\":\"C\"},{\"pregunta\":\"PUT THE VERB IN BRACKETS INTO THE GAPS. She ______ my sister. (to be)\",\"opciones\":{\"A\":\"to be\",\"B\":\"am\",\"C\":\"is\"},\"correcta\":\"C\"},{\"pregunta\":\"Which of the following are adverbs of frecuency? \\u00a0alwaysat the momentoftensometimesyesterday\",\"opciones\":{\"A\":\"your answer\"},\"correcta\":\"A\"},{\"pregunta\":\"FORM QUESTIONSIN THE PRESENT SIMPLE. Frank to read comics\",\"opciones\":{\"A\":\"Frank reads comics\"},\"correcta\":\"A\"},{\"pregunta\":\"FORM A NEGATIVE SENTENCE. Mr. Smith teaches French.\",\"opciones\":{\"A\":\"Mr. Smith doesn\'t teach French.\",\"B\":\"Mr. Smith does not teach French.\"},\"correcta\":\"B\"},{\"pregunta\":\"Put the verbs in brackets into the gaps. Jill ____________ two children. (to have)\",\"opciones\":{\"A\":\"have\",\"B\":\"has\",\"C\":\"to have\"},\"correcta\":\"C\"},{\"pregunta\":\"PUT THE VERB IN BRACKETS INTO THE GAPS. She ______ my sister. (to be)\",\"opciones\":{\"A\":\"to be\",\"B\":\"am\",\"C\":\"is\"},\"correcta\":\"C\"},{\"pregunta\":\"Which of the following are adverbs of frecuency? \\u00a0alwaysat the momentoftensometimesyesterday\",\"opciones\":{\"A\":\"your answer\"},\"correcta\":\"A\"},{\"pregunta\":\"FORM QUESTIONSIN THE PRESENT SIMPLE. Frank to read comics\",\"opciones\":{\"A\":\"Frank reads comics\"},\"correcta\":\"A\"},{\"pregunta\":\"FORM A NEGATIVE SENTENCE. Mr. Smith teaches French.\",\"opciones\":{\"A\":\"Mr. Smith doesn\'t teach French.\",\"B\":\"Mr. Smith does not teach French.\"},\"correcta\":\"B\"},{\"pregunta\":\"Put the verbs in brackets into the gaps. Jill ____________ two children. (to have)\",\"opciones\":{\"A\":\"have\",\"B\":\"has\",\"C\":\"to have\"},\"correcta\":\"C\"},{\"pregunta\":\"PUT THE VERB IN BRACKETS INTO THE GAPS. She ______ my sister. (to be)\",\"opciones\":{\"A\":\"to be\",\"B\":\"am\",\"C\":\"is\"},\"correcta\":\"C\"},{\"pregunta\":\"Which of the following are adverbs of frecuency? \\u00a0alwaysat the momentoftensometimesyesterday\",\"opciones\":{\"A\":\"your answer\"},\"correcta\":\"A\"}]}', 1, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-08-08 05:28:44', '2025-08-08 05:28:44'),
(28, 'Activiad Prueba', 'Prueba piloto', 'actividades/1754668309_Present_Simple.jpg', 6, NULL, '{\"tipo\":\"importado\",\"preguntas\":[{\"pregunta\":\"IMAGEN According to picture, choose the best adjective I enjoyed watching the _______ elephant in the zoo.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" tiny\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" huge\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" small\"},\"correcta\":\"F\"},{\"pregunta\":\"IMAGEN According to picture, choose the best adjective The last week I watched coco. it is an __________ movie\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" animated\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" horror\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" action\"},\"correcta\":\"F\"},{\"pregunta\":\"IMAGEN According to picture, choose the best adjective My dog doesn t want to play with me, I have a _______ pet.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" lazy\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" active\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" weird\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"My sister is six years __________ than me.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" young\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" younger\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" youngest\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the\\u00a0correct\\u00a0sentence\\u00a0in the\\u00a0negative form\\u00a0of the \'Present Simple\' :\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" I doesn\'t like bananas.\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" She don\'t draw that well.\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" I don\'t take a shower in the morning.\"},\"correcta\":\"F\"},{\"pregunta\":\"What role in a sentence plays the word in\\u00a0bold? \\\"My friend\\u00a0eats\\u00a0sushi every Sunday.\\\"\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Verb\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Subject\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Complement\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"What role in a sentence play the words in\\u00a0red? \\\"My mom\\u00a0loves to paint landscapes.\\\"\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Verb\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" Subject\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Complement\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the correct option. They ___ school.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" love\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" loves\"},\"correcta\":\"D\"},{\"pregunta\":\"Choose the correct option\\u00a0 She ____ two languages at school.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" study\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" studies\\u00a0\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" studys\\u00a0\"},\"correcta\":\"F\"},{\"pregunta\":\"\\u00a0We are at school. \\u00a0______ school is very nice.\\u00a0\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" Our\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" His\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" Her\"},\"correcta\":\"F\"},{\"pregunta\":\"Helen is __________ ballet dancer in our city.\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" gooder\\u00a0\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" better\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" the best\"},\"correcta\":\"F\"},{\"pregunta\":\"What do we use the \'Simple Present Tense\'\\u00a0for?\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\" To talk about daily activities, past future actions.\",\"C\":\"\\\"ltr\\\" style\",\"D\":\"\\\"text-align : left;\\\" To talk about daily activities, routines, facts and present actions.\",\"E\":\"\\\"ltr\\\" style\",\"F\":\"\\\"text-align : left;\\\" To talk about actions.\"},\"correcta\":\"F\"},{\"pregunta\":\"Choose the\\u00a0correct sentence\\u00a0in\\u00a0positive form\\u00a0of\\u00a0\'the Present Simple\' :\",\"opciones\":{\"A\":\"\\\"ltr\\\" style\",\"B\":\"\\\"text-align : left;\\\"\",\"C\":\"\\\"font-size : 1rem; -webkit-text-size-adjust : 100 ;\\\" I was happy yesterday.\",\"D\":\"\\\"ltr\\\" style\",\"E\":\"\\\"text-align : left;\\\" She\'s eating some pizza.\",\"F\":\"\\\"ltr\\\" style\"},\"correcta\":\"F\"},{\"pregunta\":\"He _____ soccer in the afternoons. (play)\",\"opciones\":{\"A\":\"plays\"},\"correcta\":\"A\"},{\"pregunta\":\"Lisa _____ up at 6 :00am everyday. (wake)\\u00a0\",\"opciones\":{\"A\":\"wakes\"},\"correcta\":\"A\"}]}', 1, NULL, NULL, NULL, NULL, 0, NULL, 3, '2025-08-08 22:51:49', '2025-08-08 22:51:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_intentos`
--

CREATE TABLE `actividad_intentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `actividad_id` bigint(20) UNSIGNED NOT NULL,
  `alumno_nombre` varchar(255) DEFAULT NULL,
  `alumno_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nombre_estudiante` varchar(100) NOT NULL,
  `grupo` varchar(20) NOT NULL,
  `carrera` varchar(50) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `respuestas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`respuestas`)),
  `puntaje` int(11) NOT NULL DEFAULT 0,
  `total_preguntas` int(11) NOT NULL DEFAULT 0,
  `tiempo_completado` int(11) NOT NULL DEFAULT 0,
  `correctas` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL,
  `completado_en` timestamp NULL DEFAULT NULL,
  `numero_intento` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `carrera_id` bigint(20) UNSIGNED NOT NULL,
  `cuatrimestre_actual` int(11) NOT NULL DEFAULT 1,
  `parcial_actual` tinyint(4) NOT NULL DEFAULT 1,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1756077988),
('laravel_cache_5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1756077988;', 1756077988),
('laravel_cache_77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:1;', 1756078050),
('laravel_cache_77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1756078050;', 1756078050),
('laravel_cache_onetap:ip:127.0.0.1', 'i:1;', 1756078050),
('laravel_cache_onetap:ip:127.0.0.1:timer', 'i:1756078050;', 1756078050),
('laravel_cache_onetap:mail:lsjosearturo@gmail.com', 'i:1;', 1756078051),
('laravel_cache_onetap:mail:lsjosearturo@gmail.com:timer', 'i:1756078051;', 1756078051);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuatrimestres`
--

CREATE TABLE `cuatrimestres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `orden` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuatrimestres`
--

INSERT INTO `cuatrimestres` (`id`, `nombre`, `activo`, `orden`, `created_at`, `updated_at`) VALUES
(1, 'Primer Cuatrimestre', 1, 1, '2025-07-16 12:42:45', '2025-07-16 12:42:45'),
(2, 'Segundo Cuatrimestre', 1, 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(3, 'Tercer Cuatrimestre', 1, 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(4, 'Cuarto Cuatrimestre', 1, 4, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(5, 'Quinto Cuatrimestre', 1, 5, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(6, 'Sexto Cuatrimestre', 1, 6, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(7, 'Séptimo Cuatrimestre', 1, 7, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(8, 'Octavo Cuatrimestre', 1, 8, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(9, 'Noveno Cuatrimestre', 1, 9, '2025-07-16 12:42:46', '2025-07-16 12:42:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `profesor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_academico`
--

CREATE TABLE `historial_academico` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `carrera_id` bigint(20) UNSIGNED NOT NULL,
  `cuatrimestre_id` bigint(20) UNSIGNED NOT NULL,
  `periodo_escolar_id` bigint(20) UNSIGNED NOT NULL,
  `grupo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_periodo` enum('academico','estadias') NOT NULL,
  `resultado` enum('aprobado','reprobado','estadias_completadas','estadias_pendientes','baja_temporal','baja_definitiva','en_proceso') NOT NULL,
  `calificacion` decimal(5,2) DEFAULT NULL,
  `intentos` int(11) NOT NULL DEFAULT 1,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alumno_id` bigint(20) UNSIGNED NOT NULL,
  `grupo_id` bigint(20) UNSIGNED NOT NULL,
  `estado` enum('inscrito','retirado','completado') NOT NULL DEFAULT 'inscrito',
  `calificacion_final` decimal(5,2) DEFAULT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_retiro` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `carrera_id` bigint(20) UNSIGNED NOT NULL,
  `cuatrimestre_numero` int(11) NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `de_usuario_id` bigint(20) UNSIGNED NOT NULL,
  `para_usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mensaje` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_15_040416_create_tareas_table', 1),
(5, '2025_06_18_152128_create_mensajes_table', 1),
(6, '2025_07_16_051651_create_cuatrimestres_table', 2),
(7, '2025_07_16_051756_create_parciales_table', 3),
(8, '2025_07_08_180712_create_actividades_table', 4),
(9, '2025_07_24_170221_create_carreras_table', 5),
(10, '2025_07_24_170222_create_periodos_escolares_table', 5),
(11, '2025_07_25_184330_add_fields_to_tareas_table', 6),
(12, '2025_07_24_170227_create_actividad_intentos_table', 7),
(13, '2025_07_24_170229_modify_actividades_table', 7),
(15, '2025_07_24_170230_modify_users_table_for_professors', 8),
(16, '2025_07_28_172831_complete_actividad_intentos_table', 8),
(17, '2025_07_28_172900_add_public_access_to_actividades', 8),
(18, '2025_07_29_180556_grupos', 9),
(19, '2025_07_29_190443_add_fields_to_users_table', 10),
(20, '2025_08_18_000001_widen_enum_rol', 11),
(21, '2025_08_01_185014_add_google_id_to_users_table', 12),
(22, '2025_08_01_195909_create_actividad_intentos_table', 13),
(23, '2025_08_02_031028_create_materias_table', 13),
(24, '2025_08_02_031141_create_grupos_table', 14),
(25, '2025_08_02_031230_create_alumnos_table', 14),
(26, '2025_08_02_031339_create_inscripciones_table', 14),
(27, '2025_08_02_031501_modify_actividad_intentos_for_alumnos', 15),
(28, '2025_08_02_031513_modify_actividades_for_grupos', 15),
(29, '2025_08_02_035714_sistema_completo_alumnos', 15),
(30, '2025_08_04_000000_create_actividad_intentos_table', 15),
(31, '2025_08_06_151839_fix_actividad_intentos_table', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parciales`
--

CREATE TABLE `parciales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cuatrimestre_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `numero` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `parciales`
--

INSERT INTO `parciales` (`id`, `cuatrimestre_id`, `nombre`, `numero`, `created_at`, `updated_at`) VALUES
(1, 1, 'Primer Parcial', 1, '2025-07-16 12:42:45', '2025-07-16 12:42:45'),
(2, 1, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(3, 1, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(4, 2, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(5, 2, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(6, 2, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(7, 3, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(8, 3, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(9, 3, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(10, 4, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(11, 4, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(12, 4, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(13, 5, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(14, 5, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(15, 5, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(16, 6, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(17, 6, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(18, 6, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(19, 7, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(20, 7, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(21, 7, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(22, 8, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(23, 8, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(24, 8, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(25, 9, 'Primer Parcial', 1, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(26, 9, 'Segundo Parcial', 2, '2025-07-16 12:42:46', '2025-07-16 12:42:46'),
(27, 9, 'Tercer Parcial', 3, '2025-07-16 12:42:46', '2025-07-16 12:42:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `tipo` enum('academico','estadias') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BGskensuUfj9YLYCgYY7ycOU7COimOtQvgZ8j0jB', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR3BnV1V0RmVQRjZOb3NubUY1N09YTXllVGcxTUtHUkp0dzIxNlplaCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2Zlc29yZXMvbWlzLWVzdHVkaWFudGVzIjt9fQ==', 1756079040);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alumno` varchar(255) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `calificacion` double DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_calificacion` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `alumno`, `archivo`, `calificacion`, `comentario`, `fecha_calificacion`, `created_at`, `updated_at`) VALUES
(1, 'Carlos Muñoz', '1753467757_Future Forms ENGLISH III.pdf', 10, 'Excelente', '2025-07-26 01:56:50', '2025-07-26 01:22:37', '2025-07-26 01:56:50'),
(2, 'Juan Osuna', '1753469871_1750477880_5 conceptos.docx', 10, 'Entrega en tiempo y forma.', '2025-08-09 01:36:03', '2025-07-26 01:57:51', '2025-08-09 01:36:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `matricula` varchar(255) DEFAULT NULL,
  `rol` enum('alumno','profesor','admin') DEFAULT 'alumno',
  `grupo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `matricula`, `rol`, `grupo_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Carlos Yahir Muñoz Garcia', 'mgarciacarlos05@gmail.com', NULL, NULL, 'admin', NULL, NULL, '$2y$12$nGmNWj8qIEcbbySOt5TYPOqOQfvOsc7oRqyHVrq6Xac2VHkO/zMaq', 'rnpaDZY4RFQmh3fALPn9nrmNSplUA20bVAsgVX18SuFKT9zLHyvduVAVdK7r', '2025-08-16 12:18:32', '2025-08-23 12:56:53'),
(2, 'Profesor Test', 'profesor@test.com', NULL, NULL, 'alumno', NULL, NULL, '$2y$12$f6VqkB.2msVdvCo7yWvm6eXsmH9Doiskcg/hhAVOR9KV0IWdFJ73S', NULL, '2025-08-19 04:09:44', '2025-08-19 04:09:44'),
(3, 'TheLord', 'lsjosearturo@gmail.com', NULL, NULL, 'admin', NULL, NULL, '$2y$12$EUlbfzF8ozg.eVjaNwfRZeK.UvSIWVgyMulqJCPgQij1SPidW.Vt6', 'vougNXVxFnSICyfVAAB2EIjEqVrlz8Dlw4Nm9hX6lVGAm18n2JxYeTWicfA8', '2025-08-25 06:25:29', '2025-08-25 06:26:31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `actividades_link_publico_unique` (`link_publico`),
  ADD KEY `actividades_parcial_id_foreign` (`parcial_id`),
  ADD KEY `actividades_link_publico_index` (`link_publico`),
  ADD KEY `actividades_grupo_activa_index` (`grupo_id`,`activa`),
  ADD KEY `actividades_fechas_index` (`fecha_disponible`,`fecha_limite`),
  ADD KEY `actividades_grupo_id_activa_index` (`grupo_id`,`activa`),
  ADD KEY `actividades_fecha_disponible_fecha_limite_index` (`fecha_disponible`,`fecha_limite`);

--
-- Indices de la tabla `actividad_intentos`
--
ALTER TABLE `actividad_intentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_actividad_estudiante` (`actividad_id`,`nombre_estudiante`),
  ADD KEY `idx_actividad_fecha` (`actividad_id`,`completado_en`),
  ADD KEY `intentos_alumno_actividad_index` (`alumno_id`,`actividad_id`),
  ADD KEY `intentos_actividad_numero_index` (`actividad_id`,`numero_intento`),
  ADD KEY `actividad_intentos_alumno_actividad_index` (`alumno_id`,`actividad_id`),
  ADD KEY `actividad_intentos_actividad_numero_index` (`actividad_id`,`numero_intento`),
  ADD KEY `actividad_intentos_alumno_id_actividad_id_index` (`alumno_id`,`actividad_id`),
  ADD KEY `actividad_intentos_actividad_id_numero_intento_index` (`actividad_id`,`numero_intento`),
  ADD KEY `actividad_intentos_actividad_id_alumno_nombre_index` (`actividad_id`,`alumno_nombre`),
  ADD KEY `actividad_intentos_alumno_nombre_index` (`alumno_nombre`),
  ADD KEY `actividad_intentos_created_at_index` (`created_at`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumnos_matricula_unique` (`matricula`),
  ADD UNIQUE KEY `alumnos_email_unique` (`email`),
  ADD KEY `alumnos_carrera_id_cuatrimestre_actual_index` (`carrera_id`,`cuatrimestre_actual`),
  ADD KEY `alumnos_matricula_index` (`matricula`),
  ADD KEY `alumnos_activo_index` (`activo`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carreras_codigo_unique` (`codigo`);

--
-- Indices de la tabla `cuatrimestres`
--
ALTER TABLE `cuatrimestres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupos_profesor_id_foreign` (`profesor_id`);

--
-- Indices de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inscripciones_alumno_id_grupo_id_unique` (`alumno_id`,`grupo_id`),
  ADD KEY `inscripciones_grupo_id_estado_index` (`grupo_id`,`estado`),
  ADD KEY `inscripciones_alumno_id_index` (`alumno_id`),
  ADD KEY `inscripciones_fecha_inscripcion_index` (`fecha_inscripcion`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materias_codigo_unique` (`codigo`),
  ADD KEY `materias_carrera_id_cuatrimestre_numero_index` (`carrera_id`,`cuatrimestre_numero`),
  ADD KEY `materias_activa_index` (`activa`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mensajes_de_usuario_id_foreign` (`de_usuario_id`),
  ADD KEY `mensajes_para_usuario_id_foreign` (`para_usuario_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parciales`
--
ALTER TABLE `parciales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parciales_cuatrimestre_id_foreign` (`cuatrimestre_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `periodos_academicos_codigo_unique` (`codigo`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_matricula_unique` (`matricula`),
  ADD KEY `users_grupo_id_foreign` (`grupo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `actividad_intentos`
--
ALTER TABLE `actividad_intentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuatrimestres`
--
ALTER TABLE `cuatrimestres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `parciales`
--
ALTER TABLE `parciales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actividades_parcial_id_foreign` FOREIGN KEY (`parcial_id`) REFERENCES `parciales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `actividad_intentos`
--
ALTER TABLE `actividad_intentos`
  ADD CONSTRAINT `actividad_intentos_actividad_id_foreign` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actividad_intentos_alumno_id_foreign` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_carrera_id_foreign` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_profesor_id_foreign` FOREIGN KEY (`profesor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_alumno_id_foreign` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripciones_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `materias_carrera_id_foreign` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_de_usuario_id_foreign` FOREIGN KEY (`de_usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensajes_para_usuario_id_foreign` FOREIGN KEY (`para_usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `parciales`
--
ALTER TABLE `parciales`
  ADD CONSTRAINT `parciales_cuatrimestre_id_foreign` FOREIGN KEY (`cuatrimestre_id`) REFERENCES `cuatrimestres` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
