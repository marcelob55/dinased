-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-09-2025 a las 17:30:59
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

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
