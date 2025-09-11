-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-09-2025 a las 20:56:58
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
-- Base de datos: `aulacontrol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambiente`
--

CREATE TABLE `ambiente` (
  `idAmbiente` int(11) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `estado` enum('Disponible','Ocupada') NOT NULL DEFAULT 'Disponible',
  `afluencia` int(3) NOT NULL DEFAULT 0,
  `idCentro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambiente`
--

INSERT INTO `ambiente` (`idAmbiente`, `numero`, `estado`, `afluencia`, `idCentro`) VALUES
(1, '201a', 'Disponible', 0, 1),
(2, '201b', 'Disponible', 0, 1),
(3, '202', 'Disponible', 0, 3),
(4, '301', 'Disponible', 0, 2),
(5, '302', 'Disponible', 0, 2),
(6, '303', 'Disponible', 0, 2),
(7, '101b', 'Disponible', 0, 1),
(8, '105', 'Disponible', 0, 3),
(9, '201', 'Disponible', 0, 3),
(11, '101a', 'Disponible', 0, 1),
(12, '102', 'Disponible', 0, 1),
(14, '304', 'Disponible', 0, 2),
(15, '103', 'Disponible', 0, 1),
(18, '305', 'Disponible', 0, 2),
(19, '203', 'Disponible', 0, 3),
(34, '808', 'Disponible', 0, 1),
(35, 'Mesa Ayuda', 'Disponible', 0, 1),
(36, 'Mesa Ayuda', 'Disponible', 0, 2),
(37, 'Mesa Ayuda', 'Disponible', 0, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aprendices`
--

CREATE TABLE `aprendices` (
  `idAprendices` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idFicha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aprendices`
--

INSERT INTO `aprendices` (`idAprendices`, `idUsuario`, `idFicha`) VALUES
(1, 3, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `idAsistencia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `idFicha` int(11) NOT NULL,
  `idInstructor` int(11) NOT NULL,
  `idAmbiente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `idCargo` int(11) NOT NULL,
  `detalle` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`idCargo`, `detalle`) VALUES
(1, 'Administrador'),
(2, 'Instructor'),
(3, 'Aprendiz'),
(4, 'Vigilante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro`
--

CREATE TABLE `centro` (
  `idCentro` int(11) NOT NULL,
  `detalle` varchar(80) NOT NULL,
  `siglas` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `centro`
--

INSERT INTO `centro` (`idCentro`, `detalle`, `siglas`) VALUES
(1, 'Centro del Diseño y Manufactura del Cuero', 'CDMC'),
(2, 'Centro Tecnológico del Mobiliario', 'CTM'),
(3, 'Centro de Formación en Diseño Confección y Moda', 'CFDCM'),
(4, 'Porteria', 'PORT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `computador`
--

CREATE TABLE `computador` (
  `idComputador` int(11) NOT NULL,
  `ref` varchar(20) NOT NULL,
  `marca` varchar(20) NOT NULL,
  `estado` enum('Disponible','Ocupado') NOT NULL,
  `imagenQr` varchar(40) NOT NULL,
  `idAmbiente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `computador`
--

INSERT INTO `computador` (`idComputador`, `ref`, `marca`, `estado`, `imagenQr`, `idAmbiente`) VALUES
(3, '88888', 'MAC', 'Ocupado', '', 18),
(4, '434883', 'LENOVO', 'Disponible', '', 3),
(5, '349292', 'DELL', 'Disponible', '', 19),
(6, '3994821', 'HP', 'Disponible', '', 35),
(7, '400092', 'ASUS', 'Disponible', '', 36),
(8, '667444', 'LENOVO', 'Ocupado', '', 37),
(9, '4000032', 'HP', 'Disponible', '', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha`
--

CREATE TABLE `ficha` (
  `idFicha` int(11) NOT NULL,
  `ficha` int(11) NOT NULL,
  `detalle` varchar(70) NOT NULL,
  `estado` enum('Activa','Inactiva') NOT NULL,
  `aprendices` int(11) DEFAULT 0,
  `idCentro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ficha`
--

INSERT INTO `ficha` (`idFicha`, `ficha`, `detalle`, `estado`, `aprendices`, `idCentro`) VALUES
(1, 2617416, 'Análisis y Desarrollo de Software', 'Activa', 0, 1),
(2, 2617417, 'Gestión de Seguridad y Salud en el Trabajo', 'Activa', 0, 2),
(3, 2800523, 'Análisis de Materiales para la Industria', 'Activa', 0, 3),
(6, 2617416, 'Análisis y Desarrollo de Software', 'Activa', 0, 2),
(12, 2617418, 'Marroquineria', 'Activa', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objetos`
--

CREATE TABLE `objetos` (
  `idObjeto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `color` varchar(30) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL,
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objetos`
--

INSERT INTO `objetos` (`idObjeto`, `descripcion`, `color`, `estado`, `idUsuario`) VALUES
(21, 'PORTATIL ASUS GEN 6', 'gris con rojo', 'Inactivo', 1),
(24, 'PORTATIL HP', 'Blanco', 'Inactivo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_ambiente`
--

CREATE TABLE `registro_ambiente` (
  `idRegistro` int(11) NOT NULL,
  `inicio` timestamp NULL DEFAULT NULL,
  `fin` timestamp NULL DEFAULT NULL,
  `llaves` tinyint(4) NOT NULL,
  `controlTv` tinyint(4) NOT NULL,
  `controlAire` tinyint(4) NOT NULL,
  `idInstructor` int(11) NOT NULL,
  `idAmbiente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_ambiente`
--

INSERT INTO `registro_ambiente` (`idRegistro`, `inicio`, `fin`, `llaves`, `controlTv`, `controlAire`, `idInstructor`, `idAmbiente`) VALUES
(1, '2024-05-02 02:01:39', '2024-05-02 02:02:38', 1, 1, 1, 2, 11),
(2, '2024-05-02 02:10:39', '2024-05-02 02:10:58', 1, 1, 1, 6, 11),
(3, '2024-05-04 02:24:37', '2024-05-04 02:26:27', 1, 1, 1, 6, 3),
(4, '2024-05-04 02:43:24', '2024-05-04 02:54:31', 1, 0, 0, 2, 5),
(5, '2024-05-04 05:58:08', '2024-05-04 06:00:22', 1, 0, 1, 2, 5),
(73, '2024-04-03 15:00:00', '2024-04-03 16:00:00', 1, 0, 1, 2, 1),
(74, '2024-04-04 14:30:00', '2024-04-04 17:00:00', 0, 1, 0, 6, 2),
(75, '2024-04-05 16:00:00', '2024-04-05 17:30:00', 1, 1, 1, 2, 3),
(76, '2024-04-06 19:00:00', '2024-04-06 20:30:00', 0, 0, 1, 6, 4),
(77, '2024-04-07 18:30:00', '2024-04-07 21:00:00', 1, 1, 0, 2, 5),
(78, '2024-04-08 13:00:00', '2024-04-08 14:30:00', 0, 0, 1, 6, 6),
(79, '2024-04-09 15:30:00', '2024-04-09 17:00:00', 1, 1, 0, 2, 7),
(80, '2024-04-10 14:00:00', '2024-04-10 15:30:00', 0, 1, 1, 6, 8),
(81, '2024-04-11 17:00:00', '2024-04-11 18:30:00', 1, 0, 0, 2, 9),
(82, '2024-04-12 20:30:00', '2024-04-12 22:00:00', 0, 1, 1, 6, 11),
(83, '2024-04-13 16:30:00', '2024-04-13 18:00:00', 1, 0, 0, 2, 12),
(84, '2024-04-14 19:00:00', '2024-04-14 20:30:00', 0, 1, 1, 6, 14),
(85, '2024-04-15 14:30:00', '2024-04-15 16:00:00', 1, 0, 0, 2, 15),
(86, '2024-04-16 13:00:00', '2024-04-16 14:30:00', 0, 1, 1, 6, 18),
(87, '2024-04-17 15:30:00', '2024-04-17 17:00:00', 1, 0, 1, 2, 19),
(88, '2024-04-18 19:00:00', '2024-04-18 20:30:00', 0, 1, 0, 6, 1),
(89, '2024-04-19 18:30:00', '2024-04-19 21:00:00', 1, 1, 1, 2, 2),
(90, '2024-04-20 13:00:00', '2024-04-20 14:30:00', 0, 0, 1, 6, 3),
(91, '2024-04-21 14:30:00', '2024-04-21 16:00:00', 1, 1, 0, 2, 4),
(92, '2024-04-22 17:00:00', '2024-04-22 18:30:00', 0, 0, 1, 6, 5),
(93, '2024-04-23 20:30:00', '2024-04-23 22:00:00', 1, 1, 0, 2, 6),
(94, '2024-04-24 16:30:00', '2024-04-24 18:00:00', 0, 0, 1, 6, 7),
(95, '2024-04-25 19:00:00', '2024-04-25 20:30:00', 1, 1, 0, 2, 8),
(96, '2024-04-26 14:30:00', '2024-04-26 16:00:00', 0, 0, 1, 6, 9),
(97, '2024-04-27 13:00:00', '2024-04-27 14:30:00', 1, 1, 0, 2, 11),
(98, '2024-04-28 15:30:00', '2024-04-28 17:00:00', 0, 1, 1, 6, 12),
(99, '2024-04-29 17:00:00', '2024-04-29 18:30:00', 1, 0, 0, 2, 14),
(100, '2024-04-30 20:30:00', '2024-04-30 22:00:00', 0, 1, 1, 6, 15),
(101, '2024-05-01 16:30:00', '2024-05-01 18:00:00', 1, 0, 0, 2, 18),
(102, '2024-05-02 19:00:00', '2024-05-02 20:30:00', 0, 1, 1, 6, 19),
(103, '2024-05-03 14:30:00', '2024-05-03 16:00:00', 1, 0, 0, 2, 1),
(104, '2024-05-04 17:00:00', '2024-05-04 18:30:00', 0, 1, 1, 6, 2),
(105, '2024-05-05 20:30:00', '2024-05-05 22:00:00', 1, 0, 0, 2, 3),
(106, '2024-05-06 16:30:00', '2024-05-06 18:00:00', 0, 0, 1, 6, 4),
(107, '2024-05-07 19:00:00', '2024-05-07 20:30:00', 1, 0, 0, 2, 5),
(108, '2024-05-08 14:30:00', '2024-05-08 16:00:00', 0, 1, 1, 6, 6),
(109, '2024-05-09 13:00:00', '2024-05-09 14:30:00', 1, 0, 0, 2, 7),
(110, '2024-05-10 15:30:00', '2024-05-10 17:00:00', 0, 1, 1, 6, 8),
(111, '2024-05-11 17:00:00', '2024-05-11 18:30:00', 1, 0, 0, 2, 9),
(112, '2024-05-12 20:30:00', '2024-05-12 22:00:00', 0, 1, 1, 6, 11),
(113, '2024-05-13 16:30:00', '2024-05-13 18:00:00', 1, 0, 0, 2, 12),
(114, '2024-05-14 19:00:00', '2024-05-14 20:30:00', 0, 1, 1, 6, 14),
(115, '2024-05-15 14:30:00', '2024-05-15 16:00:00', 1, 0, 0, 2, 15),
(116, '2024-05-16 13:00:00', '2024-05-16 14:30:00', 0, 1, 1, 6, 18),
(117, '2024-05-17 15:30:00', '2024-05-17 17:00:00', 1, 0, 1, 2, 19),
(118, '2024-05-18 19:00:00', '2024-05-18 20:30:00', 0, 1, 0, 6, 1),
(119, '2024-05-19 18:30:00', '2024-05-19 21:00:00', 1, 1, 1, 2, 2),
(120, '2024-05-20 13:00:00', '2024-05-20 14:30:00', 0, 0, 1, 6, 3),
(121, '2024-05-21 14:30:00', '2024-05-21 16:00:00', 1, 1, 0, 2, 4),
(122, '2024-05-22 17:00:00', '2024-05-22 18:30:00', 0, 0, 1, 6, 5),
(123, '2024-05-23 20:30:00', '2024-05-23 22:00:00', 1, 1, 0, 2, 6),
(124, '2024-05-24 16:30:00', '2024-05-24 18:00:00', 0, 0, 1, 6, 7),
(125, '2024-05-25 19:00:00', '2024-05-25 20:30:00', 1, 1, 0, 2, 8),
(126, '2024-05-26 14:30:00', '2024-05-26 16:00:00', 0, 0, 1, 6, 9),
(127, '2024-05-27 13:00:00', '2024-05-27 14:30:00', 1, 1, 0, 2, 11),
(128, '2024-05-28 15:30:00', '2024-05-28 17:00:00', 0, 1, 1, 6, 12),
(129, '2024-05-29 17:00:00', '2024-05-29 18:30:00', 1, 0, 0, 2, 14),
(130, '2024-05-30 20:30:00', '2024-05-30 22:00:00', 0, 1, 1, 6, 15),
(131, '2024-05-31 16:30:00', '2024-05-31 18:00:00', 1, 0, 0, 2, 18),
(132, '2024-06-01 19:00:00', '2024-06-01 21:00:00', 0, 1, 1, 6, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_asistencia`
--

CREATE TABLE `registro_asistencia` (
  `idRegistro` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `IdAsistencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_computador`
--

CREATE TABLE `registro_computador` (
  `idRegistro` int(11) NOT NULL,
  `inicio` timestamp NULL DEFAULT NULL,
  `fin` timestamp NULL DEFAULT NULL,
  `idUsuario` int(11) NOT NULL,
  `idComputador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_computador`
--

INSERT INTO `registro_computador` (`idRegistro`, `inicio`, `fin`, `idUsuario`, `idComputador`) VALUES
(2, '2024-07-05 12:00:36', '2024-07-05 17:52:36', 6, 7),
(3, '2024-07-05 13:56:44', '2024-07-05 12:56:44', 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_objeto`
--

CREATE TABLE `registro_objeto` (
  `idRegistro` int(11) NOT NULL,
  `inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fin` timestamp NULL DEFAULT NULL,
  `idObjeto` int(11) NOT NULL,
  `idCentro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_objeto`
--

INSERT INTO `registro_objeto` (`idRegistro`, `inicio`, `fin`, `idObjeto`, `idCentro`) VALUES
(3, '2024-07-09 02:20:31', '2024-07-09 03:04:23', 21, 1),
(8, '2024-07-09 03:07:29', '2024-07-21 02:57:51', 21, 1),
(10, '2024-07-21 02:28:58', '2024-07-21 02:29:04', 24, 1),
(11, '2024-07-21 02:58:28', '2024-07-21 02:58:59', 24, 1),
(12, '2024-07-21 02:58:43', '2024-07-21 02:58:55', 21, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `documento` int(11) NOT NULL,
  `contrasena` varchar(40) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `token` varchar(200) DEFAULT NULL,
  `olvideContra` enum('Si','No') DEFAULT 'No',
  `nuevo` enum('Si','No') NOT NULL DEFAULT 'Si',
  `idCargo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `documento`, `contrasena`, `estado`, `token`, `olvideContra`, `nuevo`, `idCargo`) VALUES
(1, 100000, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Activo', NULL, 'No', 'No', 1),
(2, 100001, '66eafce88f4193989c11197e15d548786eaac5df', 'Activo', NULL, 'No', 'Si', 2),
(3, 100002, '1748b719c8374a73ff10d8da0f95745164db28e5', 'Activo', NULL, 'No', 'Si', 3),
(5, 100004, '0c1516d4373e2b03e7ede13e66ec738367e7970c', 'Activo', NULL, 'No', 'Si', 3),
(6, 100005, 'b4db53fd7fb2479106a072c5ba4b36c878984cf2', 'Activo', NULL, 'No', 'Si', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_detalle`
--

CREATE TABLE `usuario_detalle` (
  `idDetalle` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `imagen` varchar(30) NOT NULL,
  `imagenQr` varchar(30) NOT NULL,
  `nacimiento` date NOT NULL,
  `genero` varchar(15) NOT NULL,
  `idCentro` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_detalle`
--

INSERT INTO `usuario_detalle` (`idDetalle`, `nombre`, `correo`, `imagen`, `imagenQr`, `nacimiento`, `genero`, `idCentro`, `idUsuario`) VALUES
(1, 'Cristian David', 'crdavip@gmail.com', './view/img/users/1-01.jpg', './view/img/users/qr-100000.png', '1995-01-01', 'Masculino', 1, 1),
(2, 'David Calderon', 'david@correo.com', './view/img/users/default.jpg', './view/img/users/qr-100002.png', '2000-04-12', 'Hombre', 1, 3),
(4, 'John Doe', 'john@correo.com', './view/img/users/default.jpg', './view/img/users/qr-100004.png', '2000-04-12', 'Hombre', 2, 5),
(5, 'Guillermo De La Peña', 'guillo@correo.com', './view/img/users/88-01.jpg', './view/img/users/qr-100001.png', '1970-04-12', 'Hombre', 1, 2),
(6, 'Luis Alfonso Becerra', 'becerra@correo.com', './view/img/users/default.jpg', './view/img/users/qr-100005.png', '1974-04-12', 'Hombre', 1, 6);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ambiente`
--
ALTER TABLE `ambiente`
  ADD PRIMARY KEY (`idAmbiente`),
  ADD KEY `amb_cen_FK` (`idCentro`);

--
-- Indices de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  ADD PRIMARY KEY (`idAprendices`),
  ADD KEY `apr_usu_FK` (`idUsuario`),
  ADD KEY `apr_fic_FK` (`idFicha`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idAsistencia`),
  ADD KEY `asi_fic_FK` (`idFicha`),
  ADD KEY `asi_usu_FK` (`idInstructor`),
  ADD KEY `asi_amb_FK` (`idAmbiente`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`idCargo`);

--
-- Indices de la tabla `centro`
--
ALTER TABLE `centro`
  ADD PRIMARY KEY (`idCentro`);

--
-- Indices de la tabla `computador`
--
ALTER TABLE `computador`
  ADD PRIMARY KEY (`idComputador`),
  ADD UNIQUE KEY `ref` (`ref`),
  ADD KEY `com_amb_FK` (`idAmbiente`);

--
-- Indices de la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD PRIMARY KEY (`idFicha`),
  ADD KEY `fic_cen_FK` (`idCentro`);

--
-- Indices de la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD PRIMARY KEY (`idObjeto`),
  ADD KEY `obj_usu_FK` (`idUsuario`);

--
-- Indices de la tabla `registro_ambiente`
--
ALTER TABLE `registro_ambiente`
  ADD PRIMARY KEY (`idRegistro`),
  ADD KEY `r_amb_usu_FK` (`idInstructor`),
  ADD KEY `r_amb_a,b_FK` (`idAmbiente`);

--
-- Indices de la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  ADD PRIMARY KEY (`idRegistro`),
  ADD KEY `r_as_usu_FK` (`idUsuario`),
  ADD KEY `r_as_asis_FK` (`IdAsistencia`);

--
-- Indices de la tabla `registro_computador`
--
ALTER TABLE `registro_computador`
  ADD PRIMARY KEY (`idRegistro`),
  ADD KEY `r_com_usu_FK` (`idUsuario`),
  ADD KEY `r_com_com_FK` (`idComputador`);

--
-- Indices de la tabla `registro_objeto`
--
ALTER TABLE `registro_objeto`
  ADD PRIMARY KEY (`idRegistro`),
  ADD KEY `r_obj_obj_FK` (`idObjeto`) USING BTREE,
  ADD KEY `ro_cent_FK` (`idCentro`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD KEY `usu_rol_FK` (`idCargo`);

--
-- Indices de la tabla `usuario_detalle`
--
ALTER TABLE `usuario_detalle`
  ADD PRIMARY KEY (`idDetalle`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `ud_usu_FK` (`idUsuario`),
  ADD KEY `ud_cen_FK` (`idCentro`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ambiente`
--
ALTER TABLE `ambiente`
  MODIFY `idAmbiente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  MODIFY `idAprendices` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idAsistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `idCargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `centro`
--
ALTER TABLE `centro`
  MODIFY `idCentro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `computador`
--
ALTER TABLE `computador`
  MODIFY `idComputador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ficha`
--
ALTER TABLE `ficha`
  MODIFY `idFicha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `objetos`
--
ALTER TABLE `objetos`
  MODIFY `idObjeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `registro_ambiente`
--
ALTER TABLE `registro_ambiente`
  MODIFY `idRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  MODIFY `idRegistro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registro_computador`
--
ALTER TABLE `registro_computador`
  MODIFY `idRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `registro_objeto`
--
ALTER TABLE `registro_objeto`
  MODIFY `idRegistro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuario_detalle`
--
ALTER TABLE `usuario_detalle`
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ambiente`
--
ALTER TABLE `ambiente`
  ADD CONSTRAINT `amb_cen_FK` FOREIGN KEY (`idCentro`) REFERENCES `centro` (`idCentro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `aprendices`
--
ALTER TABLE `aprendices`
  ADD CONSTRAINT `apr_fic_FK` FOREIGN KEY (`idFicha`) REFERENCES `ficha` (`idFicha`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `apr_usu_FK` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asi_amb_FK` FOREIGN KEY (`idAmbiente`) REFERENCES `ambiente` (`idAmbiente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asi_fic_FK` FOREIGN KEY (`idFicha`) REFERENCES `ficha` (`idFicha`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asi_usu_FK` FOREIGN KEY (`idInstructor`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `computador`
--
ALTER TABLE `computador`
  ADD CONSTRAINT `com_amb_FK` FOREIGN KEY (`idAmbiente`) REFERENCES `ambiente` (`idAmbiente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD CONSTRAINT `fic_cen_FK` FOREIGN KEY (`idCentro`) REFERENCES `centro` (`idCentro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `objetos`
--
ALTER TABLE `objetos`
  ADD CONSTRAINT `obj_usu_FK` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_ambiente`
--
ALTER TABLE `registro_ambiente`
  ADD CONSTRAINT `r_amb_a,b_FK` FOREIGN KEY (`idAmbiente`) REFERENCES `ambiente` (`idAmbiente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_amb_usu_FK` FOREIGN KEY (`idInstructor`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  ADD CONSTRAINT `r_as_asis_FK` FOREIGN KEY (`IdAsistencia`) REFERENCES `asistencia` (`idAsistencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_as_usu_FK` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_computador`
--
ALTER TABLE `registro_computador`
  ADD CONSTRAINT `r_com_com_FK` FOREIGN KEY (`idComputador`) REFERENCES `computador` (`idComputador`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_com_usu_FK` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_objeto`
--
ALTER TABLE `registro_objeto`
  ADD CONSTRAINT `r_obj_obj_FK` FOREIGN KEY (`idObjeto`) REFERENCES `objetos` (`idObjeto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ro_cent_FK` FOREIGN KEY (`idCentro`) REFERENCES `centro` (`idCentro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usu_rol_FK` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_detalle`
--
ALTER TABLE `usuario_detalle`
  ADD CONSTRAINT `ud_cen_FK` FOREIGN KEY (`idCentro`) REFERENCES `centro` (`idCentro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ud_usu_FK` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
