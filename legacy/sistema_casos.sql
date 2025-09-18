-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-09-2025 a las 21:58:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_casos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casos`
--

CREATE TABLE `casos` (
  `id` int(11) NOT NULL,
  `numero_caso` varchar(50) NOT NULL,
  `label` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre_asociado` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos`
--

INSERT INTO `casos` (`id`, `numero_caso`, `label`, `fecha`, `cedula`, `nombre_asociado`, `descripcion`, `created_at`, `updated_at`) VALUES
(11, 'Z42025072201', '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', '2025-07-22', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(12, 'Z42025072202', '20/07/2025 MV MARÍA AUXILIADORA', '2025-07-22', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(13, 'Z42025072401', '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', '2025-07-24', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(14, 'Z42025072402', '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', '2025-07-24', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(15, 'Z42025072403', '23.-07-2025 T.A MANTA CUBA', '2025-07-24', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(16, 'Z42025072404', '23.-07-2025 T.A MANTA CUBA', '2025-07-24', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(17, 'Z42025080601', '08-05-2025. M.V PERIODISTA', '2025-08-06', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(18, 'Z42025080701', '07-08-2025. M.V MENOR DE EDAD GUABITO PORTOVIEJO07', '2025-08-07', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(19, 'Z42025081601', '16-08-2025. M.V LA MARISCAL', '2025-08-16', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(20, 'Z42025081801', '17-08-2025. M.V MENOR DE EDAD QUITO', '2025-08-18', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(21, 'Z42025082101', '18-07-2025. MV. LOMA DEL MULADAR PORTOVIEJO', '2025-08-21', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(22, 'Z42025082501', '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', '2025-08-25', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(23, 'Z42025082502', '23.-07-2025 T.A MANTA CUBA', '2025-08-25', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(24, 'Z42025082601', 'Website for equador.42web.io', '2025-08-26', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(25, 'Z04I250820250001', 'CASO DURAN PRUEBA', '2025-08-25', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(26, 'Z04I250820250002', 'DURAN PRUEBA 2', '2025-08-25', '1802709483', NULL, NULL, '2025-08-26 19:17:12', '2025-08-26 19:17:12'),
(27, 'Z04I260820250001', '26-08-2025 MUERTE MV ARMA BLANCA GUASMO SUR', '2025-08-26', '1802709483', NULL, NULL, '2025-08-27 00:17:42', '2025-08-27 00:17:42'),
(28, 'Z04I260820250002', '26-08-2025 MV ARMA FUEGO PROGRESO', '2025-08-26', '1802709483', NULL, NULL, '2025-08-27 01:24:45', '2025-08-27 01:24:45'),
(29, 'Z04I270820250001', '27-08-202 MV CRISTO CONSUELO', '2025-08-27', '1802709483', NULL, NULL, '2025-08-27 20:12:14', '2025-08-27 20:12:14'),
(30, 'Z04I270820250002', '27-08-2025 MV ROCAFUERTE', '2025-08-27', '1802709483', NULL, NULL, '2025-08-27 20:24:45', '2025-08-27 20:24:45'),
(31, 'Z04I310820250001', '31 08 2025 MV SAN PABLO', '2025-08-31', '1802709483', NULL, NULL, '2025-08-31 22:40:10', '2025-08-31 22:40:10'),
(32, 'Z42025090601', '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', '2025-09-06', '1802709483', NULL, NULL, '2025-09-06 15:03:33', '2025-09-06 15:03:33'),
(33, 'Z42025090602', '26-08-2025 MUERTE MV ARMA BLANCA GUASMO SUR', '2025-09-06', '1802709483', NULL, NULL, '2025-09-06 15:03:39', '2025-09-06 15:03:39'),
(34, 'Z42025090603', '23.-07-2025 T.A MANTA CUBA', '2025-09-06', '1802709483', NULL, NULL, '2025-09-06 15:05:02', '2025-09-06 15:05:02'),
(35, 'Z04I090920250001', '09-09-2025 MV MANTA CUBA', '2025-09-09', '1802709483', NULL, NULL, '2025-09-10 01:39:39', '2025-09-10 01:39:39'),
(36, 'Z04I090920250002', '09-09-2025 MV MANTA D', '2025-09-09', '1802709483', NULL, NULL, '2025-09-10 01:48:15', '2025-09-10 01:48:15'),
(37, 'Z04I120920250001', '12 09 2025 MV FUTBOL', '2025-09-12', '1802709483', NULL, NULL, '2025-09-12 20:01:59', '2025-09-12 20:01:59'),
(38, 'Z04I180920250001', '18 09 2025 MV PICOAZA', '2025-09-18', '1802709483', NULL, NULL, '2025-09-18 20:23:44', '2025-09-18 20:23:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_caso`
--

CREATE TABLE `detalle_caso` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `verificacion` varchar(255) DEFAULT NULL,
  `codigo_ecu` varchar(50) DEFAULT NULL,
  `zona` varchar(50) DEFAULT NULL,
  `subzona` varchar(50) DEFAULT NULL,
  `distrito` varchar(50) DEFAULT NULL,
  `circuito` varchar(50) DEFAULT NULL,
  `subcircuito` varchar(50) DEFAULT NULL,
  `espacio` varchar(50) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  `lugar_hecho` varchar(255) DEFAULT NULL,
  `coordenadas` varchar(100) DEFAULT NULL,
  `criminalistica` text DEFAULT NULL,
  `tipo_arma` varchar(100) DEFAULT NULL,
  `indicios` varchar(50) DEFAULT NULL,
  `tipo_delito` varchar(100) DEFAULT NULL,
  `motivacion` text DEFAULT NULL,
  `estado_caso` varchar(50) DEFAULT NULL,
  `justificacion` text DEFAULT NULL,
  `circunstancias` text DEFAULT NULL,
  `entrevistas` text DEFAULT NULL,
  `actividades` text DEFAULT NULL,
  `reporta` varchar(255) DEFAULT NULL,
  `fecha_hecho` date DEFAULT NULL,
  `hora_hecho` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_caso`
--

INSERT INTO `detalle_caso` (`id`, `caso_id`, `verificacion`, `codigo_ecu`, `zona`, `subzona`, `distrito`, `circuito`, `subcircuito`, `espacio`, `area`, `lugar_hecho`, `coordenadas`, `criminalistica`, `tipo_arma`, `indicios`, `tipo_delito`, `motivacion`, `estado_caso`, `justificacion`, `circunstancias`, `entrevistas`, `actividades`, `reporta`, `fecha_hecho`, `hora_hecho`) VALUES
(4, 11, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.086015,-80.463217', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Por disposición del SIS ECU-911, personal de la DINASED PORTOVIEJO se traslada el sector del camino viejo del sector del Guabito fin verificar dos personas fallecidas por arma de fuego, una vez constituidos en el lugar de los hechos se verifica sobre la calzada una persona de sexo masculino en posición de cúbito dorsal identificado por sus familiares como (+) Miguel Angel Meza Vera con cc. 1316172301, en el interior de de un domicilio un espacio destinado para portal un cuerpo sin vida en posición de cubito dorsal identificado como Edison Fernando Meza Vera con cc. 1350352660 los cuales en conjunto con personal de criminalística se realizó una inspección visual el cual presenta múltiples heridas por el paso de proyectil de arma de fuego de igual forma se tiene conocimiento que exite nueve personas heridas por armas de fuego las cuale fueron trasladadas por moradores hasta el hospital donde se tomó contacto con los galenos de turno mismo que manifiestan que las personas heridas  presentan heridas por arma de fuego de entrada y salida en diferentes partes del cuerpo  y su estado es estable.\r\n\r\nAl lugar de los hechos acude la unidad de criminalística al mando del Sgos. Edison Taco  mismo que fijo y levanto 48 vainas percutidas calibre 9 mm y una bala deformada.\r\n', 'Se entrevista con moradores del sector a  quien no quieren identificarse por temor a la represalias manifiestan que los jóvenes todos los fines de semana se dedican a jugar fútbol en la cancha de fútbol guiferza posterior a la reunión a beber bebidas alcohólicas y posterior llega tres motocicleta abordo Dos ciudadanos en cada de uno de ellos.', '- Entrevista con moradores \r\n\r\n- Verificación de cámaras de seguridad en la cual se procede a descargar varios video donde se observa la dinámica de los hechos violento.\r\n\r\n- Posterior de aquello  se activa varias Unidades ENTRE ELLAS DGI- GIA MANTA con el fin de dar con el paradero de los Presuntos Víctimarios por lo cual se logró allanar varias viviendas encontrando varios indicios asociativos al hecho: Dos motocicleta que se encuentran reportadas como Robada y 90 moniciones de 9 mm de marca SANTA BÁRBARA, y un casco homologado de motocicleta. | ', 'DINASED SZ MANABÍ-PORTOVIEJO', NULL, NULL),
(5, 12, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '57993', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.084072,-80.437142', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Por reporte del sistema integrado de seguridad ciudadana ECU911 personal de la DINASED MANTA avanza a verificar la existencia de una persona fallecida y dos heridas por el paso de proyectil de arma de fuego en el sector Masatl Av. 224 y calle 312 una vez constituidos en el lugar se pudo visualizar  una ambulancia trasladando a los ciudadanos heridos dicha ambulancia estaba al mando del Sr. Paramédico Tulio Muñiz  ambulancia del IESS le trasladan al hospital Rodríguez Zambrano así mismo se puede observar  una escena cerrada dónde se puede visualizar una persona fallecida de sexo masculino en posición de cubito dorsal, de nombres RIVADENEIRA RIVERA ANGEL JAVIER, al momento de realizar el examen visual externo conjuntamente con personal de Criminalistica se puede constatar varios orificios de similares características a las producidas por el arma de fuego, de igual manera personal de UCM  realizó la IOT de la escena, fijando y levantando los indicios antes descritos.\r\nMinutos después el ECU nos alerta que una motocicleta se encontraba abandonada a unos 500 metros aproximadamente del lugar de los hechos es así que se presume que es la motocicleta donde se moviliza a los victimarios, es así que la motocicleta será ingresada a los patio de retención vehicular de la PJ de Manta por parte del Servicio Preventivo. \r\nActo seguido nos trasladamos al Hospital Rodríguez Zambrano y al IESS casas de salud donde se encontraban los ciudadanos heridos de nombres  RIVADENEIRA DELGADO ELIANA CAROLINA Y RIVERA TUREZ CRISTHIAN OSWALDO, en el cual se toma contacto con los galeno de turno y manifiesta que ingresan dichos ciudanados y que se encuentran estables. \r\nDe lo sucedido se le dio a conocer al Sr Fiscal de Turno mediante vía telefónica mismo de dispuso que se realice las investigaciones respectivas de igual manera el levantamiento del cadaver y su respectivo traslado al Centro Forense. ', '-En el SS se toma contacto con los familiares que no desearon indentificarse por temor a represalias indicar que ingresan dos ciudadanos a bordo de una motocicleta dichos ciudadanos son de contextura gruesa (conductor) y parrillero  de contextura (flaco) indican que al momento de su llegada preguntan donde venden cerveza en el cual al momento de bajarse de la motocicleta proceden a  disparar en contra del hoy occiso y de la herida, y es así q retirarse con rumbo desconocido.', '-Identificación de la víctima \r\n-Entrevista con moradores \r\n-Coordinación con el Fiscal de Turno \r\n-Coordinación con el Centro Forense ', 'DINASED SZ MANABÍ-PORTOVIEJO', NULL, NULL),
(6, 11, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.093696,-80.279381', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', 'Se entrevista con moradores del sector...', 'Entrevista con moradores...', 'DINASED SZ MANABÍ-PORTOVIEJO', NULL, NULL),
(7, 11, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.081416,-80.440958', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '‎Por disposición del SIS ECU 911, personal de Turno de la DINASED nos trasladamos hasta la Vía del Muladar  a verificar una Persona  Fallecida por arma de fuego, una vez constituidos en el  lugar se puede constatar en la vía pública una cuerpo de sexo masculino tendido sobre la calzada sin vida con varias heridas por impacto de arma de fuego en varias partes del cuerpo los familiares los identifican con los  nombres Loor Ávila Juan Carlos.\r\n\r\nDe igual manera el Sr. Fiscal de Turno Abg. Luis Castillo  delega el respectivo levantamiento del cadáver y el traslado hasta el centro Forense de Manta.\r\n\r\nAl lugar acudió personal de Criminalística al mando del Sr. Cbos. Jonathan Intriago quien realiza la IOT y fijación y levantamiento de varios indicios balisticos en el lugar (7 Vainas Percutidas.).', 'Entrevista a la conviviente del occiso la señora Maria Alexandra Avila Dominguez con CC. 120650490, menciona que desde el dia miercoles sujetos desconocidos en una motocicleta lo perseguian desde el domicilio hasta el lugar de trabajo y hasta el banco.\r\n\r\nSe entrevista con familiares que no desean identificarse que el ahora occiso no habría sido inscrito en el registro civil.', '‎-Entrevista con Familiares del Occiso\r\n‎‎-Verificacion de cámaras de seguridad privadas cercanas al hecho\r\n-Levantamiento del Cadáver \r\n-Coordimacion con Fiscalía \r\n‎', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-07-18', '12:46:00'),
(8, 12, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.028510,-80.405523', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', 'Se entrevista con moradores del sector... |  | ', 'Entrevista con moradores...', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-07-22', '12:04:00'),
(9, 12, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-0.985691,-80.269958', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', 'Se entrevista con moradores del sector... |  | ', 'Entrevista con moradores... | ', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-07-27', '07:05:00'),
(10, 14, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.079357,-80.438258', NULL, NULL, NULL, NULL, 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', NULL, NULL, 'Personal de la DINASED Manta se trasladó hasta el lugar de los hechos, ubicado en el sector Cuba como referencia la parroquia Eloy Alfaro calle 325 y calle sin nombre , se visualiza una escena abierta resguardada por el servicio preventivo, dentro de la misma  se visualiza maculas color marrón presumiblemente sangre y varios indicios balísticos qué fueron fijados y embalados, al mando del Sr.Sgos.Portilla Gabriel . Posterior de esta actividad, otro equipo de la Dinased avanza ha verificar el estado de salud de la persona herida, en donde en el área de emergencia, se visualiza una persona de sexo masculino con varios impactos de arma de fuego en piernas y glúteos, sin embargo, presenta un estado de salud estable. \nDe esta novedad se dio a conocer al Fiscal de turno, Ab. Alexandra Bravo, quién avoca conocimiento de este hecho violento y delega las respectivas tareas investigativas.', '[]', '[]', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-07-24', '11:06:00'),
(11, 16, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '8754', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.078205,-80.452524', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', 'Se entrevista a la ciudadana Cinthia Elizabeth Vargas Macias con c.c 0926574260, número celular 0981254936, domicilida en la 11 y la B la misma que se identifica como cuñada del occiso y que sobre el caso indica que aproximadamente a las 12H00 su cuñado había dejado a hijo en casa después de haber realizado el expreso, posterior mediante comentarios de familiares, tiene conocimiento que a su cuñado le habrían disparado, además indica que su cuñado de dedicaría al trabajo en tricimoto, por lo que no aporta más información referente al caso. | ', 'Entrevista con moradores...', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-07-30', '08:00:00'),
(12, 17, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.080798,-80.450056', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mediante comunicación del personal de atencion ciudadana del distrito Ponce Enriquez se tiene conocimiento de una presunta \"toma de mina\" en el sector de la \"boya\" específicamente en la mina \"Roca de Oro\" y que se encuentran personas heridas, los mismos que fueron trasladados hasta el subcentro de salud del cantón Naranjal, por lo que inmediatamente nos trasladamos a Naranjal a fin de entrevistarnos con las personas heridas, una vez constituidos en el lugar nos supieron manifestar que personas armadas habrían ingresado a la mina \"Roca de Oro\" con prendas de vestir de similares características a las de la Policia Nacional (Pixelado) quienes habían disparado y luego los habrían llevado a la fuerza dejándolos abandonados por el sector de Balao, de igual manera  las personas heridas supieron indicar que los sospechosos se llevaron a 15 personas y desconocen el paradero de las otras 9, cabe indicar que las personas que se encontraban en el subcentro de salud de Naranjal responden a los nombres de; Carlos Christian Lara Lima C.C. 0922344999\r\nJose Narciso Diaz Briones C.C. 0920808946\r\nMery Margarita Castillo Mora C.C. 0703748079\r\nMuentes Bernal Wilder Jeovanny C.C. 1307325140\r\nYanez Ordóñez Franco Wilmer 0702815531\r\nJulio Cesar Lucio Solorzano 1716598998, posterior regresamos a Ponce Enríquez fin de ubicar el lugar de los hechos y unas vez constituidos en el lugar nos entrevistamos con una persona, quien dijo ser Guardia de seguridad, y mientras revisabamos la escena (campamento minero) se pudo constatar un cuerpo sin vida de sexo hombre, en posicion decubito ventral diagonal a una quebrada, al realizar el examen visual externo del cuerpo se pudo constatar una herida aparentemente producida por un arma de fuego a la altura de la cabeza. Cabe indicar que por delegación verbal del Sr. Fiscal de turno Dr. Pedro Maldonado se procedió a realizar el levantamiento del cuerpo.\r\n\r\nAl lugar avanzo personal de Criminalística de Naranjal al mando del Sr. Sgos. Merwin Montero quienes realizan la fijación de indicios.', 'Con el Sr. Miguel Abelardo Gonzales Lema, con C.C 1206632729, quien se identifica como guardia de seguridad de la empresa \"Browinn Security\", manifiesta que, aproximadamente a la 01h00 del día de hoy martes 05/08/2025, bajaron de la montaña varias personas vestidos de Policía con el uniforme pixelado quienes nos gritaron \"alto Policía\" luego proceden a disparar y nosotros como nos encontrabamos de guardia tambien tuve que repeler el ataque, pero como eran bastantes y algunos compañeros salieron corriendo yo también corrí y luego escuché un sonido fuerte como que explotaron dinamita de ahi ya no supe nada más. | ', '*Entrevista al personal del eje preventivo\r\n*Entrevista a los familiares del occiso\r\n*Verificación de Camaras de seguridad.', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-08-05', '12:12:00'),
(13, 17, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.080798,-80.450056', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mediante comunicación del personal de atencion ciudadana del distrito Ponce Enriquez se tiene conocimiento de una presunta \"toma de mina\" en el sector de la \"boya\" específicamente en la mina \"Roca de Oro\" y que se encuentran personas heridas, los mismos que fueron trasladados hasta el subcentro de salud del cantón Naranjal, por lo que inmediatamente nos trasladamos a Naranjal a fin de entrevistarnos con las personas heridas, una vez constituidos en el lugar nos supieron manifestar que personas armadas habrían ingresado a la mina \"Roca de Oro\" con prendas de vestir de similares características a las de la Policia Nacional (Pixelado) quienes habían disparado y luego los habrían llevado a la fuerza dejándolos abandonados por el sector de Balao, de igual manera  las personas heridas supieron indicar que los sospechosos se llevaron a 15 personas y desconocen el paradero de las otras 9, cabe indicar que las personas que se encontraban en el subcentro de salud de Naranjal responden a los nombres de; Carlos Christian Lara Lima C.C. 0922344999\r\nJose Narciso Diaz Briones C.C. 0920808946\r\nMery Margarita Castillo Mora C.C. 0703748079\r\nMuentes Bernal Wilder Jeovanny C.C. 1307325140\r\nYanez Ordóñez Franco Wilmer 0702815531\r\nJulio Cesar Lucio Solorzano 1716598998, posterior regresamos a Ponce Enríquez fin de ubicar el lugar de los hechos y unas vez constituidos en el lugar nos entrevistamos con una persona, quien dijo ser Guardia de seguridad, y mientras revisabamos la escena (campamento minero) se pudo constatar un cuerpo sin vida de sexo hombre, en posicion decubito ventral diagonal a una quebrada, al realizar el examen visual externo del cuerpo se pudo constatar una herida aparentemente producida por un arma de fuego a la altura de la cabeza. Cabe indicar que por delegación verbal del Sr. Fiscal de turno Dr. Pedro Maldonado se procedió a realizar el levantamiento del cuerpo.\r\n\r\nAl lugar avanzo personal de Criminalística de Naranjal al mando del Sr. Sgos. Merwin Montero quienes realizan la fijación de indicios.', 'Con el Sr. Miguel Abelardo Gonzales Lema, con C.C 1206632729, quien se identifica como guardia de seguridad de la empresa \"Browinn Security\", manifiesta que, aproximadamente a la 01h00 del día de hoy martes 05/08/2025, bajaron de la montaña varias personas vestidos de Policía con el uniforme pixelado quienes nos gritaron \"alto Policía\" luego proceden a disparar y nosotros como nos encontrabamos de guardia tambien tuve que repeler el ataque, pero como eran bastantes y algunos compañeros salieron corriendo yo también corrí y luego escuché un sonido fuerte como que explotaron dinamita de ahi ya no supe nada más. | ', '*Entrevista al personal del eje preventivo\r\n*Entrevista a los familiares del occiso\r\n*Verificación de Camaras de seguridad.', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-08-05', '12:12:00'),
(14, 11, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', 'Se entrevista con moradores del sector...', 'Entrevista con moradores...', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-08-12', '09:09:00'),
(15, 18, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.079170,-80.435801', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Por disposición del SIS ECU 911 DINASED avanzamos a verificar la presencia de una personas Fallecida por impactos de proyectil de arma de fuego en el sector de Tarqui al exterior del una panadería, una vez constituidos en el lugar de los hechos se toma contacto del personal de servicio preventivo al mando del Tnte Loor Cevallos mismo que manifiesta que por versión de moradores se llega a tener conocimiento que el fallecido era del sector y habría estado comprando panes.\r\nAl lugar asiste criminalistica quien realiza la IOT y la fijación de indicios balísticos. \r\n\r\nDe la novedad se dio a conocer vía telefónica al agente fiscal de turno Ab Alexandra Bravo, quien avoca conocimiento del hecho .', 'Palma Franco Grey Antonella 1350231641 (Hija) manifiesta que su papá habría salido a comprar pan a las 6:15 a una panadería cercana al domicilio así mismo indicó que personas se estaban aglomerando en la panadería por lo que avanzó hasta ese lugar y encontró a su papá En el suelo . | Angélica María Mero Chávez (Esposa) manifiesta que su esposo se dedicaba a la pesca y habría llegado el domingo así mismo indica que salió a comprar panes para el desayuno a una panadería cercana a su domicilio posterior la llaman a indicarle que su esposo se encontraba al exterior de la panadería fallecida .\r\n', '-Verificación de cámaras de seguridad mismas que se encuentran deshabilitadas.\r\n-Entrevista con Familiares ', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-08-12', '06:59:00'),
(16, 19, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.080526,-80.437040', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Por disposición del Ecu 911, personal de turno de muertes violentas nos trasladamos hasta el sector de esmeraldas libre, con el fin de verificar una persona fallecida por arma blanca en el cual había sido trasladado por familiares hasta  el hospital teodoro maldonado carbo, en el lugar se observa un cuerpo de sexo masculino en posición decúbito dorsal, sin signos vitales, el cual presenta varias heridas por arma blanca a la altura de la cabeza.\r\n\r\nDel procedimiento tuvo conocímiento el fiscal de turno  Ab.  Darwin Muñoz quien delegó el levantamiento del cadáver y dispuso que el cuerpo sea trasladado hasta el Departamento de Medicina Legal y Ciencias forenses para la respectiva necropsia de ley.', '[]', '[]', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-08-16', '10:01:00'),
(17, 20, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '5', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '-1.080197,-80.439272', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Por disposición del SIS ECU 911, DINASED MANTA avanzamos a verificar una persona fallecida por arma de fuego en el Cantón jipijapa sector Maldonado,  una vez ya  constituidos en el lugar de los hechos se constata en una escena abierta, zona rural, espacio público, aproximadamente a 45 minutos de movilización en vehículo, dónde no existe cobertura telefónica, sobre una vía de segundo orden se observa un cuerpo humano se sexo masculino sin vida, en posición decubito ventral, mismo que al realizarle una inspección ocular externa superficial se observa varias heridas con similares características a las producidas por el paso de proyectil de arma de fuego por diferentes partes del cuerpo.\r\nPersonal de Criminalistica realiza la fijación de varios indicios balisticos calibre 9 mm asociativos a este hecho violento.\r\n\r\nDe esta novedad se dio a conocer a la Fiscal de turno, Ab. Marcos Tulio , quién avoca conocimiento de este hecho violento y delega el respectivo traslado de cadáver hasta el Centro forense de la ciudad de Manta;', '[]', '[]', 'DINASED DMQ', '2025-08-17', '15:00:00'),
(18, 21, NULL, '74258', NULL, NULL, NULL, NULL, NULL, 'Público', 'Urbana', 'Via al Muladar', '-1.170512, -80.579498', 'SI', 'ARMA DE FUEGO', 'NO', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', 'Mediante información recabada se tiene conocimiento que el ahora occiso se encontraba libando fuera de un taller mecánico.', 'Por disposición del SIS ECU 911, personal de Turno de la DINASED nos trasladamos hasta la Vía del Muladar  a verificar una Persona  Fallecida por arma de fuego, una vez constituidos en el  lugar se puede constatar en la vía pública una cuerpo de sexo masculino tendido sobre la calzada sin vida con varias heridas por impacto de arma de fuego en varias partes del cuerpo los familiares los identifican con los  nombres Loor Ávila Juan Carlos.\r\n\r\nDe igual manera el Sr. Fiscal de Turno Abg. Luis Castillo  delega el respectivo levantamiento del cadáver y el traslado hasta el centro Forense de Manta.\r\n\r\nAl lugar acudió personal de Criminalística al mando del Sr. Cbos. Jonathan Intriago quien realiza la IOT y fijación y levantamiento de varios indicios balisticos en el lugar (7 Vainas Percutidas.).', '[\"\\u200e Entrevista a la conviviente del occiso la se\\u00f1ora Maria Alexandra Avila Dominguez con CC. 120650490, menciona que desde el dia miercoles sujetos desconocidos en una motocicleta lo perseguian desde el domicilio hasta el lugar de trabajo y hasta el banco.  Se entrevista con familiares que no desean identificarse que el ahora occiso no habr\\u00eda sido inscrito en el registro civil.\",\"\\u200e Entrevista a la conviviente del occiso la se\\u00f1ora Maria Alexandra Avila Dominguez con CC. 120650490, menciona que desde el dia miercoles sujetos desconocidos en una motocicleta lo perseguian desde el domicilio hasta el lugar de trabajo y hasta el banco.  Se entrevista con familiares que no desean identificarse que el ahora occiso no habr\\u00eda sido inscrito en el registro civil.\"]', '[\"\\u200e-Entrevista con Familiares del Occiso \\u200e\\u200e-Verificacion de c\\u00e1maras de seguridad privadas cercanas al hecho -Levantamiento del Cad\\u00e1ver  -Coordimacion con Fiscal\\u00eda\",\"\\u200e-Entrevista con Familiares del Occiso \\u200e\\u200e-Verificacion de c\\u00e1maras de seguridad privadas cercanas al hecho -Levantamiento del Cad\\u00e1ver  -Coordimacion con Fiscal\\u00eda\"]', '‎DINASED SZ MANABÍ - PORTOVIEJO', '2025-07-18', '12:30:00'),
(19, 22, 'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y CUATRO PERSONAS HERIDAS POR ARMA DE FUEGO EN EL DISTRITO DURÁN', '74258', '03', NULL, 'PASTAZA', 'SANTA CLARA', 'SANTA CLARA 1', 'Público', 'Urbana', 'El guabito', '-1.261128, -78.073242', 'SI', 'ARMA DE FUEGO', 'NO', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', NULL, NULL, '[\"\\u200e Entrevista a la conviviente del occiso la se\\u00f1ora Maria Alexandra Avila Dominguez con CC. 120650490, menciona que desde el dia miercoles sujetos desconocidos en una motocicleta lo perseguian desde el domicilio hasta el lugar de trabajo y hasta el banco.  Se entrevista con familiares que no desean identificarse que el ahora occiso no habr\\u00eda sido inscrito en el registro civil.\",\"\\u200e Entrevista a la conviviente del occiso la se\\u00f1ora Maria Alexandra Avila Dominguez con CC. 120650490, menciona que desde el dia miercoles sujetos desconocidos en una motocicleta lo perseguian desde el domicilio hasta el lugar de trabajo y hasta el banco.  Se entrevista con familiares que no desean identificarse que el ahora occiso no habr\\u00eda sido inscrito en el registro civil.\",\"\\u200e Entrevista a la conviviente del occiso la se\\u00f1ora Maria Alexandra Avila Dominguez con CC. 120650490, menciona que desde el dia miercoles sujetos desconocidos en una motocicleta lo perseguian desde el domicilio hasta el lugar de trabajo y hasta el banco.  Se entrevista con familiares que no desean identificarse que el ahora occiso no habr\\u00eda sido inscrito en el registro civil.\"]', '[\"\\u200e-Entrevista con Familiares del Occiso \\u200e\\u200e-Verificacion de c\\u00e1maras de seguridad privadas cercanas al hecho -Levantamiento del Cad\\u00e1ver  -Coordimacion con Fiscal\\u00eda\",\"\\u200e-Entrevista con Familiares del Occiso \\u200e\\u200e-Verificacion de c\\u00e1maras de seguridad privadas cercanas al hecho -Levantamiento del Cad\\u00e1ver  -Coordimacion con Fiscal\\u00eda\",\"\\u200e-Entrevista con Familiares del Occiso \\u200e\\u200e-Verificacion de c\\u00e1maras de seguridad privadas cercanas al hecho -Levantamiento del Cad\\u00e1ver  -Coordimacion con Fiscal\\u00eda\"]', 'DINASED SZ MANABÍ-PORTOVIEJO', '2025-08-25', '08:00:00'),
(20, 26, 'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y CUATRO PERSONAS HERIDAS POR ARMA DE FUEGO EN EL DISTRITO DURÁN', '180101', '08', NULL, 'DURAN', 'ARBOLITO', 'ARBOLITO 3', 'Público', 'Urbana', 'Coop Héctor Cobos sector 4, canchas múltiples Héctor Cobos', '-2.130582, -79.835175', 'NO', 'ARMA DE FUEGO', '-35 vainas percutidas 9mm', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', 'No sé puede definir la motivación de este evento, debido a que no existen las suficientes premisas para aclarar el hecho', 'servicio como personal de turno Reaccion 2 Muerte Violenta,  por disposición del Ecu 911,nos trasladamos hasta la Urbanización Paseo del sol 4, sector la pista del distrito Progreso, circuito Progreso 1 a verificar dos personas fallecidas por arma de fuego, una vez constituido en el lugar en domicilio Manzana 7802 villa 5 de dos plantas de hormigón en su interior específicamente en el primer dormitorio sobre una cama se encontraba una persona de sexo masculino en posición de cubito dorsal sin signos vitales quien responde a los nombres Vera Nuñez Jaime Joel cc. 0924329410 con varias heridas en su cuerpo producidas por el paso de proyectil de arma de fuego, en el segundo dormitorio específicamente en el piso se encontraba una persona de sexo femenino en posición de cubito dorsal sin signos vitales quien responde a los nombres la Sra. Vera Nuñez Martha Cecilia cc. 0921776332 con varias heridas en su cuerpo las mismas producidas al paso de proyectil de arma de fuego.\r\n‎\r\n‎Del procedimiento tuvo conocimiento el Sr. Fiscal de turno Abg. Michael Uriguen Uriguen  con Cc. 0703251124  número de celular 0996511609 quien delego el levantamiento del cadáver y dispuso que el cuerpo sea trasladado hasta el Departamento de Medicina Legal y Ciencias Forenses para la respectiva necropsia de ley.', '[\"Al ciudadano Bermeo Vera Derlys Ren\\u00e9, portador de la c\\u00e9dula de ciudadan\\u00eda N.\\u00b0 0951518638 y n\\u00famero telef\\u00f3nico 0979707526, quien se identifico como hijo y sobrino de los hoy occisos quien manifiesta que aproximadamente en horas de la madrugada se encontraba descansando en su habitaci\\u00f3n cuando, de manera repentina, escuch\\u00f3 fuertes golpes en la puerta de ingreso de su domicilio. Al levantarse y percatarse de la situaci\\u00f3n, observ\\u00f3 que ingresaron  alrededor de seis personas, quienes vest\\u00edan chompas negras y capuchas similares a las utilizadas por personal policial perteneciente a grupos investigativos quienes de forma violenta, fue sujetado por los individuos y arrojado al suelo,  agredido f\\u00edsicamente con golpes de pu\\u00f1o y patadas, impidi\\u00e9ndole reaccionar o pedir auxilio, cuando sali\\u00f3 de su dormitorio, logr\\u00f3 visualizar que su madre y su tio se encontraban tendidos en el interior de su dormitorios, sin signos vitales, a\\u00f1ade que durante todo el acontecimiento no escuch\\u00f3 detonaciones de arma de fuego, por lo cual presume que los agresores habr\\u00edan hecho uso de armas de fuego provistas de dispositivos silenciadores para cometer el il\\u00edcito sin generar ruido alguno, luego los individuos  procedieron a sustraer dos motocicletas de placas JC198J marca Suzuki modelo DR650 color negro, motocicleta marca Suzuki placa JB314V color negro Modelo GN125 de su propiedad, retir\\u00e1ndose del lugar en las mismas, dej\\u00e1ndolo en estado de shock ante lo sucedido.\"]', '[\"\\u200e- Entrevistas a familiares y personal del servicio preventivo \\u200e- Verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas y privadas\"]', '‎DINASED Z8', '2025-08-25', NULL),
(21, 25, 'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y CUATRO PERSONAS HERIDAS POR ARMA DE FUEGO EN EL DISTRITO DURÁN', 'I02B-20250825091981799', '08', NULL, 'DURAN', 'ARBOLITO', 'ARBOLITO 3', 'Público', 'Urbana', 'Coop Héctor Cobos sector 4, canchas múltiples Héctor Cobos', '-2.119054, -79.833801', 'UCM 2, al mando de señor Tnte. Richard Benavides', NULL, NULL, 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', 'Mediante entrevista libre y voluntaria el herido  \r\nHOLGUÍN MENDOZA JUAN FLORENCIO nos supo manifestar que el ataque armado se habría originado por otro medio de transporte por la competencia de pasajeros para pagar extorsiones( vacunas)', 'Por medio del presente me permito poner en su conocimiento Mi Coronel que mediante disposición del SIS-ECU911 avanzamos hasta el lugar antes indicado , con la finalidad de verificar Dos personas fallecidas y cuatro personas heridas por arma de fuego, una vez constituidos en el lugar se visualiza una escena abierta en el parque Héctor Cobos en el lugar dos cadáveres de sexo masculino  en posición decúbito dorsal. Al realizar un exámen visual externo de los cadáveres a simple vista se pudo apreciar que presentan heridas por paso de proyectil de arma de fuego cabe mencionar que las personas heridas fueron trasladadas hasta diferentes casas de salud.\r\n\r\nDel procedimiento se da a conocer al Sr.Fiscal de Turno Ab. David Nelson del Castillo, quien delegó el respectivo levantamiento de cadáver y su posterior traslado hasta el Departamento de Medicina Legal y Ciencias Forenses para la autopsia de ley.', '[\"Holgu\\u00edn Mendoza Juan Florencio con CC. 0925314304, tel\\u00e9fono 0994733357, domiciliado en el Cant\\u00f3n Dur\\u00e1n, Coop. Los Rosales Mz E, Solar 13, refiere que trabaja de chofer de una tricimoto en el Arbolito y a las 10 aproximadamente llegan varios sujetos desconocidos en varias motos y les realizan varios detonaciones de arma de fuego a todos los conductores de las tricimotos que se encontraban en el lugar, d\\u00f3nde resultan varias personas heridas y fallecidos, luego es trasladado hasta el hospital Ortega Moreira de Duran, al ver que no le atend\\u00edan con ayuda de familiares avanza hasta el hospital Universitario. Tambi\\u00e9n manifiesta que posiblemente sean los choferes de las cooperativas de buses los que le hayan enviado hacer el atentado ya que las tricimotos se le llevan los pasajeros, ya que ellos pagan la denominada vacuna a las GDOT del sector.\\r\\n\\u2022 En el hospital Enrique Ortega se toma contacto con el Sr V\\u00e9lez Rom\\u00e1n Luis Alberto  con CC 0950928747 el mismo que manifiesta que se encontraba comiendo por las canchas de la Coop H\\u00e9ctor Cobos cuando hab\\u00edan llegado tres motocicletas con dos ocupantes cada una los mismos que proceden a realizar detonaciones de arma de fuego del cual resulta herido y sus familiares lo trasladaron hasta el hospital Enrique Ortega para recibir atenci\\u00f3n m\\u00e9dica.\\r\\n\\u2022 En el lugar de los hechos se toma contacto con la Sra Jaquel\\u00edn Mariana Leon S\\u00e1nchez con CC 0920144524 quien se identifica como hermana del Occiso domiciliada en la Coop 5 de junio manifiesta que se encontraba en su domicilio cuando le avisaron que a su hermano lo habr\\u00edan asesinado en las canchas m\\u00faltiples H\\u00e9ctor Cobos el mismo que trabaja en su tricimoto as\\u00ed mismo desconoce las causas del porqu\\u00e9 atentaron contra la vida de su hermano.\"]', '[\"Levantamiento del cad\\u00e1ver\\r\\n- Entrevista al personal del eje preventivo\\r\\n- Verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas y privadas\"]', 'DINASED Z8', '2025-08-25', NULL),
(22, 27, 'VERIFICACIÓN DE UNA PERSONA FALLECIDA POR ARMA BLANCA EN EL DISTRITO SUR', 'I02B-202508251945-90280', '08', NULL, 'SUR', 'GUASMO', 'GUASMO 3', 'Público', 'Urbana', 'Cooperativa Julio Pote', '-2.276849, -79.896029', 'NO', 'ARMA BLANCA', 'NO', 'ASESINATO', 'VIOLENCIA CRIMINAL / DELINCUENCIA COMUN/ AMENAZA', 'INVESTIGACION', 'No existe suficiente premisa para poder determinar la motivación del hecho.', 'Por reporte del SIS-ECU 911, personal de turno de de Tentativa Asesinato, avanzamos hasta el hospital del Guasmo Sur, con el fin de verificar una persona herida por arma de fuego, dónde nos  entrevistamos con la galeno de turno Dra. Cristiana Chávez, la cual manifiesta que ingresa un paciente de sexo masculino de nombres Lucas Chilan Sérgio Darío de 29 años de edad con número de cédula  0951624303, con una herida en el emitoxar del lado izquierdo, con abundante pérdida de sangre, trauma cardíaco, se encontraba en quirófano, en estado reservado y por la gravedad de la herida fallece.\r\n\r\nDel procedimiento se da a conocer al señor  Fiscal de turno Abg. José Andrade Rivera, quien delgado el respectivo levantamiento del cadáver y su posterior traslado hasta el Departamento de Medicina Legal y Ciencias Forenses para la autopisia de ley.', '[\"En el hospital del Guasmo Sur, se entrevista al galeno de turno Dra. Cristiana Ch\\u00e1vez, la cual manifiesta que ingresa un paciente de sexo masculino de nombres Lucas Chilan S\\u00e9rgio Dar\\u00edo de 29 a\\u00f1os de edad con n\\u00famero de c\\u00e9dula  0951624303, con una herida en el emitoxar del lado izquierdo, con abundante p\\u00e9rdida de sangre, trauma card\\u00edaco, se encontraba en quir\\u00f3fano, en estado reservado y por la gravedad de la herida fallece.\"]', '[\"-Entrevistas a personal policial -Entrevista con familiares  -Entrevista del medico -levantamiento de informaci\\u00f3n  -verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas y privadas.\",\"En el hospital del Guasmo Sur, se entrevista al galeno de turno Dra. Cristiana Ch\\u00e1vez, la cual manifiesta que ingresa un paciente de sexo masculino de nombres Lucas Chilan S\\u00e9rgio Dar\\u00edo de 29 a\\u00f1os de edad con n\\u00famero de c\\u00e9dula  0951624303, con una herida en el emitoxar del lado izquierdo, con abundante p\\u00e9rdida de sangre, trauma card\\u00edaco, se encontraba en quir\\u00f3fano, en estado reservado y por la gravedad de la herida fallece.\"]', 'DINASED Z8', '2025-08-25', NULL),
(23, 28, '‎VERIFICACIÓN DE DOS PERSONAS FALLECIDAS POR HERIDAS DE ARMA DE FUEGO EN EL DISTRITO PROGRESO', '‎I02B-202508260343-71847', '08', NULL, 'PROGRESO', 'PROGRESO', 'PROGRESO 1', 'Público', 'Urbana', 'URBANIZACION PASEO DEL SOL 4 MANZANA 7802 VILLA 5 ‎', '-2.099841, -80.376251', NULL, NULL, NULL, 'ASESINATO', NULL, NULL, NULL, NULL, '[]', '[]', NULL, '2025-08-26', NULL),
(24, 24, '‎VERIFICACIÓN DE DOS PERSONAS FALLECIDAS POR HERIDAS DE ARMA DE FUEGO EN EL DISTRITO PROGRESO', '36953', '04', NULL, 'SANTO DOMINGO OESTE', 'BOMBOLI', 'BOMBOLI 1', 'Público', 'Urbana', NULL, '-0.240960, -79.177197', 'UCM 2, al mando de señor Tnte. Richard Benavides', 'ARMA DE FUEGO', 'NO', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'Pichincha', '‎No sé puede definir la motivación de este evento, debido a que no existen las suficientes premisas para aclarar el hecho\r\n‎', '‎Pongo en su conocimiento Mi Coronel que encontrandonos de servicio como personal de turno Reaccion 2 Muerte Violenta,  por disposición del Ecu 911,nos trasladamos hasta la Urbanización Paseo del sol 4, sector la pista del distrito Progreso, circuito Progreso 1 a verificar dos personas fallecidas por arma de fuego, una vez constituido en el lugar en domicilio Manzana 7802 villa 5 de dos plantas de hormigón en su interior específicamente en el primer dormitorio sobre una cama se encontraba una persona de sexo masculino en posición de cubito dorsal sin signos vitales quien responde a los nombres Vera Nuñez Jaime Joel cc. 0924329410 con varias heridas en su cuerpo producidas por el paso de proyectil de arma de fuego, en el segundo dormitorio específicamente en el piso se encontraba una persona de sexo femenino en posición de cubito dorsal sin signos vitales quien responde a los nombres la Sra. Vera Nuñez Martha Cecilia cc. 0921776332 con varias heridas en su cuerpo las mismas producidas al paso de proyectil de arma de fuego.\r\n‎', '[\"Al ciudadano Bermeo Vera Derlys Ren\\u00e9, portador de la c\\u00e9dula de ciudadan\\u00eda N.\\u00b0 0951518638 y n\\u00famero telef\\u00f3nico 0979707526, quien se identifico como hijo y sobrino de los hoy occisos quien manifiesta que aproximadamente en horas de la madrugada se encontraba descansando en su habitaci\\u00f3n cuando, de manera repentina, escuch\\u00f3 fuertes golpes en la puerta de ingreso de su domicilio. Al levantarse y percatarse de la situaci\\u00f3n, observ\\u00f3 que ingresaron  alrededor de seis personas, quienes vest\\u00edan chompas negras y capuchas similares a las utilizadas por personal policial perteneciente a grupos investigativos quienes de forma violenta, fue sujetado por los individuos y arrojado al suelo,  agredido f\\u00edsicamente con golpes de pu\\u00f1o y patadas, impidi\\u00e9ndole reaccionar o pedir auxilio, cuando sali\\u00f3 de su dormitorio, logr\\u00f3 visualizar que su madre y su tio se encontraban tendidos en el interior de su dormitorios, sin signos vitales, a\\u00f1ade que durante todo el acontecimiento no escuch\\u00f3 detonaciones de arma de fuego, por lo cual presume que los agresores habr\\u00edan hecho uso de armas de fuego provistas de dispositivos silenciadores para cometer el il\\u00edcito sin generar ruido alguno, luego los individuos  procedieron a sustraer dos motocicletas de placas JC198J marca Suzuki modelo DR650 color negro, motocicleta marca Suzuki placa JB314V color negro Modelo GN125 de su propiedad, retir\\u00e1ndose del lugar en las mismas, dej\\u00e1ndolo en estado de shock ante lo sucedido.\"]', '[\"\\u200e- Entrevistas a familiares y personal del servicio preventivo \\u200e- Verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas y privadas \\u200e\"]', 'DINASED Z8', '2025-08-14', NULL),
(25, 23, 'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y CUATRO PERSONAS HERIDAS POR ARMA DE FUEGO EN EL DISTRITO DURÁN', 'I02B-20250825091981799', '04', NULL, 'SANTO DOMINGO OESTE', 'JUAN EULOGIO', 'JUAN EULOGIO 2', 'Público', 'Urbana', 'camino viejo', '-0.238729, -79.181145', 'SI', 'ARMA DE FUEGO', '-35 vainas percutidas 9mm', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', NULL, NULL, '[\"En el hospital del Guasmo Sur, se entrevista al galeno de turno Dra. Cristiana Ch\\u00e1vez, la cual manifiesta que ingresa un paciente de sexo masculino de nombres Lucas Chilan S\\u00e9rgio Dar\\u00edo de 29 a\\u00f1os de edad con n\\u00famero de c\\u00e9dula  0951624303, con una herida en el emitoxar del lado izquierdo, con abundante p\\u00e9rdida de sangre, trauma card\\u00edaco, se encontraba en quir\\u00f3fano, en estado reservado y por la gravedad de la herida fallece.\"]', '[\"- Entrevistas a familiares y personal del servicio preventivo \\u200e- Verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas y privadas\"]', '‎DINASED SZ MANABÍ - PORTOVIEJO', '2025-08-13', NULL),
(26, 29, 'VERIFICACIÓN DE (02) PERSONAS FALLECIDAS POR EL PASO DE PROYECTIL DE ARMA DE FUEGO', '52794', '07', NULL, 'PASAJE', 'BUENA VISTA', 'BUENA VISTA 1', 'Privado', 'Rural', 'GUAYAS Y 8AVA NORTE', '-3.305256, -79.877903', 'SI Cptn. Jimmy Guerrero', 'ARMA DE FUEGO', 'SI - (07) vainas  - (02) balas deformadas', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', 'A DETERMINAR', 'Por disposición del Ecu 911, personal de turno de Muertes Violentas, nos trasladamos hasta el sector antes descrito, con el fin de verificar dos personas fallecidas por el paso de proyectil arma de fuego donde se pudo observar la existencia de un cuerpo humano de sexo masculino sin signos vitales, quien al realizarle un examen visual externo presentaban varios orificios con similares características a los producidos por el paso de proyectil de arma de fuego; otro cuerpo humano de sexo femenino sin signos vitales, quien al realizarle un examen visual externo presentaban varios orificios con similares características a los producidos por el paso de proyectil de arma de fuego, la misma que se habría encontrado en el área de emergencia del Hospital Teófilo Dávila, por la gravedad de las heridas se produce su deceso.\r\nDel procedimiento se le da a conocer a la Sra. Fiscal de turno del cantón Machala, Ab. Paul Iñiguez quien delegó el levantamiento de cadáver y dispuso que el cuerpo sea trasladado hasta el Departamento de Medicina Legal y Ciencias Forenses para la respectiva autopsia de ley.', '[\"Wagner Wellington Corozo Arroyo, no proporciona m\\u00e1s datos, quien refiere ser t\\u00edo de la ciudadana herida Yuliana Mayeli Corozo Arroyo,  de 24 a\\u00f1os de edad, es estudiante universitaria, son vecinos del ciudadano fallecido, viven en el Barrio Israel, salieron a comprar comida hasta el centro de la ciudad, de lo cual no saben nada m\\u00e1s, ni el motivo del por qu\\u00e9 se dio su atentado.\"]', '[\"* Verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas como privadas * Labores de investigaci\\u00f3n * Entrevistas  * Levantamiento de cad\\u00e1ver  * Coordinaci\\u00f3n con Fiscal\\u00eda\"]', 'DINASED SZ EL ORO', '2025-08-26', NULL),
(27, 30, '‎VERIFICACIÓN DE UNA  PERSONA FALLECIDA POR ARMA DE FUEGO', '75298', '04', NULL, 'ROCAFUERTE', 'LA FLORIDA', 'LA FLORIDA 1', 'Público', 'Urbana', 'Sector Valadez, vía a la planta de Valdez', '-0.789343, -80.326454', 'Si ‎IOT. Cbos. Camilo Isacc de UCM', 'PISTOLA', 'Si ‎08 Vainas percutidas', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', '‎Se conoce que el hoy cocido se dedicaría al expendido y consumo de droga.', '‎Por disposición del SIS ECU-911, personal de la DINASED PORTOVIEJO se trasladó hasta Rocafuerte en el sector de Valdez con el fin de verificar una persona fallecida por arma de fuego, una vez constituidos en el lugar, sobre la vía pública se observa un cuerpo sin vida en posición de cúbito dorsal identificado como  (+) Reina Saltos Wilson Steven con C.C 1313457713 de 28 años de edad de nacionalidad Ecuatoriana la misma que al realizarle un examen visual externo conjuntamente con personal de criminalística el cuerpo presenta varias heridas con similares características a las producidas por el paso de proyectil de arma de fuego.', '[\"Con Ciudadanos quienes no quisieron identificarse, manifiestan que trabajadores que se dirig\\u00edan a laborar en terrenos en la via a la Planta de Agua de Valdez aproximadamente a las 06h00 observan un cuerpo tirado en la v\\u00eda, por lo que dan aviso a la polic\\u00eda.\"]', '[\"- Coordinaci\\u00f3n con fiscal\\u00eda  \\u200e- \\u2060Verificaci\\u00f3n de c\\u00e1maras  \\u200e- \\u2060Entrevista con familiares  \\u200e\\u2060- levantamiento de cad\\u00e1ver\"]', '‎DINASED SZ MANABÍ- PORTOVIEJO', '2025-08-27', NULL),
(28, 31, '‎VERIFICACIÓN DE DOS PERSONAS FALLECIDAS POR HERIDAS DE ARMA DE FUEGO EN EL DISTRITO PROGRESO', '1001150', '04', NULL, 'PORTOVIEJO', 'LOS CEREZOS', 'LOS CEREZOS 2', 'Público', 'Urbana', NULL, '-1.056762, -80.470121', 'UCM 2, al mando de señor Tnte. Richard Benavides', 'ARMA DE FUEGO', '-35 vainas percutidas 9mm', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', NULL, 'En circunstancias de investigacion', '[]', '[]', '‎DINASED SZ MANABÍ - PORTOVIEJO', NULL, NULL),
(29, 35, 'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y CUATRO PERSONAS HERIDAS POR ARMA DE FUEGO EN EL DISTRITO DURÁN', '36953', '08', NULL, '9 DE OCTUBRE', 'ANTEPARA', 'ANTEPARA 3', 'Público', 'Urbana', 'Coop Héctor Cobos sector 4, canchas múltiples Héctor Cobos', '-2.208049, -79.916748', 'UCM 2, al mando de señor Tnte. Richard Benavides', 'ARMA DE FUEGO', 'NO', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', 'No se puede definir la motivación de este evento debido a que no existen las suficientes premisas para aclarar el hecho', 'Por disposición del Ecu 911, personal de turno de  muertes violentas nos trasladamos hasta el sector del Batallón, con el fin de verificar una persona fallecida por impactos de proyectil de arma de fuego, una vez constituidos en el lugar pudimos observar sobre la calzada, un cuerpo de sexo masculino en posición decúbito ventral, sin signos vitales, el cual presenta varias heridas por el paso de proyectil de arma de fuego.\r\n\r\nDel procedimiento tuvo conocímiento el fiscal de turno  Ab. Darwin Baldeon quien delegó el levantamiento del cadáver y dispuso que el cuerpo sea trasladado hasta el Departamento de Medicina Legal y Ciencias forenses para la respectiva necropsia de ley.', '[\"ENTREVISTAS REALIZADAS \\r\\nEntrevista con el sr Tnte. Luis Alexis Gallo Rivera cc.172344XXX con n\\u00famero de contacto 099414XXX que se encontraba de Guardian del Distrito Portete por disposicion del SIS- ECU-911 a partir de las 23H00 me indico que avance hasta el sector de la calle 34 y la B donde posiblemente existe una persona sin signos vitales tirada sobre la calzada con varios impactos de proyectil  causado por arma de fuego una vez ya constituidos en el lugar se percata de un cuidadano sin signos vitales tendido en la calzada con varios impactos de bala  producido  por arma de fuego donde al momento procedimos a pedir colaboraci\\u00f3n mediante ecu-911 a las unidades especialisadas para el respectivo levantamiento.\"]', '[\"ACTIVIDADES REALIZADAS:\\r\\n- Entrevistas a personal del servicio preventivo\\r\\n- Verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas y privadas\"]', 'DINASED Z8', '2025-09-09', NULL),
(30, 36, 'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y CUATRO PERSONAS HERIDAS POR ARMA DE FUEGO EN EL DISTRITO DURÁN', '36953', '04', NULL, 'SANTO DOMINGO ESTE', 'CENTRO', 'CENTRO 1', 'Público', 'Urbana', 'Coop Héctor Cobos sector 4, canchas múltiples Héctor Cobos', '-0.244279, -79.168716', 'SI Cptn. Jimmy Guerrero', 'ARMA DE FUEGO', 'NO', 'ASESINATO', 'Violencia Criminal/ Delincuencia Común/ Amenazas ‎', 'INVESTIGACION', 'No se puede definir la motivación de este evento debido a que no existen las suficientes premisas para aclarar el hecho', 'Por disposición del Ecu 911, personal de turno de  muertes violentas nos trasladamos hasta el sector del Batallón, con el fin de verificar una persona fallecida por impactos de proyectil de arma de fuego, una vez constituidos en el lugar pudimos observar sobre la calzada, un cuerpo de sexo masculino en posición decúbito ventral, sin signos vitales, el cual presenta varias heridas por el paso de proyectil de arma de fuego.\r\n\r\nDel procedimiento tuvo conocímiento el fiscal de turno  Ab. Darwin Baldeon quien delegó el levantamiento del cadáver y dispuso que el cuerpo sea trasladado hasta el Departamento de Medicina Legal y Ciencias forenses para la respectiva necropsia de ley.', '[\"Entrevista con el sr Tnte. Luis Alexis Gallo Rivera cc.172344XXX con n\\u00famero de contacto 099414XXX que se encontraba de Guardian del Distrito Portete por disposicion del SIS- ECU-911 a partir de las 23H00 me indico que avance hasta el sector de la calle 34 y la B donde posiblemente existe una persona sin signos vitales tirada sobre la calzada con varios impactos de proyectil  causado por arma de fuego una vez ya constituidos en el lugar se percata de un cuidadano sin signos vitales tendido en la calzada con varios impactos de bala  producido  por arma de fuego donde al momento procedimos a pedir colaboraci\\u00f3n mediante ecu-911 a las unidades especialisadas para el respectivo levantamiento\"]', '[\"- Entrevistas a personal del servicio preventivo - Verificaci\\u00f3n de c\\u00e1maras p\\u00fablicas y privadas\"]', '‎DINASED Z8', '2025-09-09', NULL),
(31, 37, 'VERIFICACIÓN DE DOS PERSONAS FALLECIDAS Y CUATRO PERSONAS HERIDAS POR ARMA DE FUEGO EN EL DISTRITO DURÁN', '36953', '04', NULL, 'SANTO DOMINGO OESTE', 'BOMBOLI', 'BOMBOLI 1', 'Público', 'Urbana', NULL, '-0.238992, -79.177643', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', '[]', NULL, NULL, NULL);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_08_21_202639_add_entrevistas_actividades_to_detalle_caso', 1),
(6, '2025_08_26_131629_create_victimas_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_investigacion`
--

CREATE TABLE `planes_investigacion` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `creado_por` int(11) NOT NULL,
  `actualizado_por` int(11) DEFAULT NULL,
  `objetivo_general` text DEFAULT NULL,
  `objetivos_especificos` text DEFAULT NULL,
  `alcance` text DEFAULT NULL,
  `metodologia` text DEFAULT NULL,
  `riesgos` text DEFAULT NULL,
  `indicadores` text DEFAULT NULL,
  `recursos` text DEFAULT NULL,
  `cronograma_json` text DEFAULT NULL,
  `creado_el` datetime DEFAULT current_timestamp(),
  `actualizado_el` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_investigacion`
--

CREATE TABLE `plan_investigacion` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `delito` varchar(100) DEFAULT NULL,
  `fiscal` varchar(100) DEFAULT NULL,
  `fecha_hecho` date DEFAULT NULL,
  `fecha_asignacion` date DEFAULT NULL,
  `fecha_delegacion` date DEFAULT NULL,
  `fecha_elaboracion` date DEFAULT NULL,
  `fecha_actualizacion` date DEFAULT NULL,
  `circunstancias` text DEFAULT NULL,
  `hipotesis` text DEFAULT NULL,
  `actividades` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`actividades`)),
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `cedula` varchar(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `agencia` varchar(50) DEFAULT NULL,
  `equipo` varchar(50) DEFAULT NULL,
  `caso` varchar(50) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `numero_caso` varchar(50) DEFAULT NULL,
  `rol` enum('admin','generador','editor') NOT NULL DEFAULT 'editor',
  `ultima_conexion` datetime DEFAULT NULL,
  `ip_conexion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `nickname`, `celular`, `cedula`, `contrasena`, `correo`, `agencia`, `equipo`, `caso`, `fecha_registro`, `numero_caso`, `rol`, `ultima_conexion`, `ip_conexion`) VALUES
(9, 'Marcelo', 'Barreno', 'Mateo', '0984693128', '1802709483', '$2y$10$zmVpQdBK0Sn23TPStrrf/eTnukQXzdpXUJIIq5G.ZXVqHi3VpH78S', 'marcelob55@hotmail.com', 'PORTOVIEJO', 'OPERATIVO', NULL, '2025-07-22 00:43:30', 'POR ASIGNAR', 'generador', NULL, NULL),
(10, 'Marcelo', 'Barreno', 'Mateo', '0984693128', '1800976118', '$2y$10$1ArVAtm0rMU7xXnWp9o8eOMQAgwlyxfDAaZRw9MgIkTdpsKE0Xcj6', 'marcelob55@hotmail.com', 'PORTOVIEJO', 'OPERATIVO', NULL, '2025-07-22 00:43:52', 'POR ASIGNAR', 'editor', NULL, NULL),
(11, 'Marcelo', 'Barreno', 'Mateo', '0984693128', '1702709483', '$2y$10$1KwMZexi5Wff0yOXh1rW8uGHzxt3dvjklkD56bSu9O8Oe/pVWsvQq', 'marcelob55@hotmail.com', 'PORTOVIEJO', 'OPERATIVO', NULL, '2025-07-22 17:02:34', 'POR ASIGNAR', 'editor', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `victimas`
--

CREATE TABLE `victimas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `caso_id` int(11) NOT NULL,
  `tipo` enum('occiso','herido') NOT NULL,
  `etiqueta` varchar(10) DEFAULT NULL,
  `nombres` varchar(120) DEFAULT NULL,
  `apellidos` varchar(120) DEFAULT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `edad` smallint(5) UNSIGNED DEFAULT NULL,
  `sexo` enum('M','F','I') DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `nacionalidad` varchar(255) DEFAULT NULL,
  `profesion_ocupacion` varchar(255) DEFAULT NULL,
  `movilizacion` varchar(255) DEFAULT NULL,
  `antecedentes` tinyint(4) DEFAULT NULL,
  `sajte_judicatura` tinyint(4) DEFAULT NULL,
  `noticia_del_delito_fiscalia` tinyint(4) DEFAULT NULL,
  `pertenece_gao` tinyint(4) DEFAULT NULL,
  `gao_cargo_funcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `victimas`
--

INSERT INTO `victimas` (`id`, `caso_id`, `tipo`, `etiqueta`, `nombres`, `apellidos`, `cedula`, `edad`, `sexo`, `alias`, `nacionalidad`, `profesion_ocupacion`, `movilizacion`, `antecedentes`, `sajte_judicatura`, `noticia_del_delito_fiscalia`, `pertenece_gao`, `gao_cargo_funcion`, `created_at`, `updated_at`) VALUES
(5, 25, 'occiso', 'A', 'MANUEL HUMBERTO', 'SÁNCHEZ SANCHEZ', '0925304370', 32, 'M', 'Se desconoce', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 20:00:49', '2025-08-26 20:00:49'),
(8, 26, 'occiso', 'A', 'MANUEL HUMBERTO', 'SÁNCHEZ SANCHEZ', '0925304370', 32, 'M', 'Se desconoce', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 20:10:40', '2025-08-26 20:10:40'),
(12, 22, 'herido', 'A', 'Marcelo', 'Barreno', '0950928747', 34, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 20:20:43', '2025-08-26 20:20:43'),
(13, 22, 'herido', 'B', 'Blanca Elina', 'Herrera Tobar', '0924550353', 45, 'F', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 20:20:43', '2025-08-26 20:20:43'),
(14, 22, 'herido', 'C', 'Julio', 'Iglesias', '0930844659', 33, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 20:20:43', '2025-08-26 20:20:43'),
(15, 25, 'occiso', 'B', 'GUSTAVO JOSÉ', 'MARTÍNEZ JORDAN', '17442744', 40, 'M', 'Se desconoce', 'Venezolana', NULL, 'A pie', NULL, NULL, NULL, NULL, NULL, '2025-08-26 21:24:27', '2025-08-26 21:27:44'),
(16, 25, 'herido', 'A', 'LUIS ALBERTO', 'VÉLEZ ROMÁN', '0950928747', 34, 'M', 'Se desconoce', 'Ecuatoriana', NULL, 'A pie', NULL, NULL, NULL, NULL, NULL, '2025-08-26 21:27:44', '2025-08-26 21:28:36'),
(17, 25, 'herido', 'B', 'GONZALO MANUEL', 'MELO REAL', '0924550353', 45, 'M', 'Se desconoce', 'Ecuatoriana', NULL, 'A pie', NULL, NULL, NULL, NULL, NULL, '2025-08-26 21:28:36', '2025-08-26 21:28:36'),
(18, 27, 'occiso', 'A', 'SÉRGIO DARÍO', 'LUCAS CHILAN', '0951624303', 28, 'M', 'SE DESCONOCE', 'Ecuatoriana', NULL, 'SE DESCONCE', NULL, NULL, NULL, NULL, NULL, '2025-08-27 00:30:45', '2025-08-27 00:53:36'),
(24, 24, 'occiso', 'A', 'Marcelo', 'Barreno', '0925304370', 32, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 23, 'occiso', 'A', 'Marcelo', 'Barreno', '0925304370', 32, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 23, 'herido', 'A', 'Marcelo', 'Barreno', '0950928747', 34, 'M', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 29, 'occiso', 'A', 'ANDY STALIN', 'CABEZAS LANDAZURI', '0706752904', 32, 'M', 'Se desconoce', 'Ecuatoriana', NULL, 'MOTOCICLETA', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 30, 'occiso', 'A', 'WILSON STEVEN', 'REINA SALTOS', '1313457713', 28, 'M', 'SE DESCONOCE', 'ECUATORIANA', NULL, 'A PIE', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 31, 'occiso', 'A', 'Marcelo', 'Barreno', '0925304370', 32, 'M', 'Se desconoce', 'Ecuatoriana', NULL, 'MOTOCICLETA', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 35, 'occiso', 'A', 'MARCELO', 'BARRENO', '18028555', 28, 'F', 'Se desconoce', 'Ecuatoriana', NULL, 'A pie', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 36, 'occiso', 'A', 'MARCELO', 'BARRENO', '18552444', 32, 'M', 'Se desconoce', 'Ecuatoriana', NULL, 'A pie', 1, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `casos`
--
ALTER TABLE `casos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_caso` (`numero_caso`),
  ADD KEY `cedula` (`cedula`);

--
-- Indices de la tabla `detalle_caso`
--
ALTER TABLE `detalle_caso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_id` (`caso_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `planes_investigacion`
--
ALTER TABLE `planes_investigacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_plan_caso` (`caso_id`);

--
-- Indices de la tabla `plan_investigacion`
--
ALTER TABLE `plan_investigacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_id` (`caso_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `victimas`
--
ALTER TABLE `victimas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_victimas_caso_tipo_etiqueta` (`caso_id`,`tipo`,`etiqueta`),
  ADD KEY `victimas_caso_id_index` (`caso_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `casos`
--
ALTER TABLE `casos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `detalle_caso`
--
ALTER TABLE `detalle_caso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `planes_investigacion`
--
ALTER TABLE `planes_investigacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plan_investigacion`
--
ALTER TABLE `plan_investigacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `victimas`
--
ALTER TABLE `victimas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `casos`
--
ALTER TABLE `casos`
  ADD CONSTRAINT `casos_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`);

--
-- Filtros para la tabla `detalle_caso`
--
ALTER TABLE `detalle_caso`
  ADD CONSTRAINT `detalle_caso_ibfk_1` FOREIGN KEY (`caso_id`) REFERENCES `casos` (`id`);

--
-- Filtros para la tabla `planes_investigacion`
--
ALTER TABLE `planes_investigacion`
  ADD CONSTRAINT `fk_plan_caso` FOREIGN KEY (`caso_id`) REFERENCES `casos` (`id`);

--
-- Filtros para la tabla `plan_investigacion`
--
ALTER TABLE `plan_investigacion`
  ADD CONSTRAINT `plan_investigacion_ibfk_1` FOREIGN KEY (`caso_id`) REFERENCES `detalle_caso` (`caso_id`);

--
-- Filtros para la tabla `victimas`
--
ALTER TABLE `victimas`
  ADD CONSTRAINT `victimas_caso_id_fk` FOREIGN KEY (`caso_id`) REFERENCES `casos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
