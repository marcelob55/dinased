-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-07-2025 a las 02:50:15
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
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos`
--

INSERT INTO `casos` (`id`, `numero_caso`, `label`, `fecha`, `cedula`, `nombre_asociado`, `descripcion`) VALUES
(11, 'Z42025072201', '19-07-2025. M.V DOBLE GUABITO PORTOVIEJO', '2025-07-22', '1802709483', NULL, NULL);

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
  `fecha_hora` datetime DEFAULT NULL,
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
  `reporta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_caso`
--

INSERT INTO `detalle_caso` (`id`, `caso_id`, `verificacion`, `codigo_ecu`, `zona`, `subzona`, `distrito`, `circuito`, `subcircuito`, `espacio`, `area`, `lugar_hecho`, `fecha_hora`, `coordenadas`, `criminalistica`, `tipo_arma`, `indicios`, `tipo_delito`, `motivacion`, `estado_caso`, `justificacion`, `circunstancias`, `entrevistas`, `actividades`, `reporta`) VALUES
(4, 11, 'DOS PERSONAS FALLECIDAS Y OCHO HERIDAS POR ARMA DE FUEGO', '36953', '4', 'Manabí', 'Portoviejo', 'Guabito', 'Guabito 1', 'Público', 'Urbana', 'camino viejo', '2025-07-19 17:35:00', '-1.086015,-80.463217', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Por disposición del SIS ECU-911, personal de la DINASED PORTOVIEJO se traslada el sector del camino viejo del sector del Guabito fin verificar dos personas fallecidas por arma de fuego, una vez constituidos en el lugar de los hechos se verifica sobre la calzada una persona de sexo masculino en posición de cúbito dorsal identificado por sus familiares como (+) Miguel Angel Meza Vera con cc. 1316172301, en el interior de de un domicilio un espacio destinado para portal un cuerpo sin vida en posición de cubito dorsal identificado como Edison Fernando Meza Vera con cc. 1350352660 los cuales en conjunto con personal de criminalística se realizó una inspección visual el cual presenta múltiples heridas por el paso de proyectil de arma de fuego de igual forma se tiene conocimiento que exite nueve personas heridas por armas de fuego las cuale fueron trasladadas por moradores hasta el hospital donde se tomó contacto con los galenos de turno mismo que manifiestan que las personas heridas  presentan heridas por arma de fuego de entrada y salida en diferentes partes del cuerpo  y su estado es estable.\r\n\r\nAl lugar de los hechos acude la unidad de criminalística al mando del Sgos. Edison Taco  mismo que fijo y levanto 48 vainas percutidas calibre 9 mm y una bala deformada.\r\n', 'Se entrevista con moradores del sector a  quien no quieren identificarse por temor a la represalias manifiestan que los jóvenes todos los fines de semana se dedican a jugar fútbol en la cancha de fútbol guiferza posterior a la reunión a beber bebidas alcohólicas y posterior llega tres motocicleta abordo Dos ciudadanos en cada de uno de ellos.', '- Entrevista con moradores \r\n\r\n- Verificación de cámaras de seguridad en la cual se procede a descargar varios video donde se observa la dinámica de los hechos violento.\r\n\r\n- Posterior de aquello  se activa varias Unidades ENTRE ELLAS DGI- GIA MANTA con el fin de dar con el paradero de los Presuntos Víctimarios por lo cual se logró allanar varias viviendas encontrando varios indicios asociativos al hecho: Dos motocicleta que se encuentran reportadas como Robada y 90 moniciones de 9 mm de marca SANTA BÁRBARA, y un casco homologado de motocicleta. | ', 'DINASED SZ MANABÍ-PORTOVIEJO');

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
(10, 'Marcelo', 'Barreno', 'Mateo', '0984693128', '1800976118', '$2y$10$1ArVAtm0rMU7xXnWp9o8eOMQAgwlyxfDAaZRw9MgIkTdpsKE0Xcj6', 'marcelob55@hotmail.com', 'PORTOVIEJO', 'OPERATIVO', NULL, '2025-07-22 00:43:52', 'POR ASIGNAR', 'editor', NULL, NULL);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `detalle_caso`
--
ALTER TABLE `detalle_caso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
