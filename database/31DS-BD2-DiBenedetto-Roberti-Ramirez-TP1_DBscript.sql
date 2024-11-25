-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 14:55:27
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `flow_manager`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos`
--

CREATE TABLE `archivos` (
  `Nombre` varchar(50) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `Contenido` blob DEFAULT NULL,
  `DniCreador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `Nombre` varchar(50) NOT NULL,
  `DirectorACargo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `FechaHoraMensaje` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `TituloMensaje` varchar(50) NOT NULL,
  `DniRemitente` int(11) NOT NULL,
  `TipoMensaje` varchar(50) NOT NULL,
  `CuerpoMensaje` varchar(160) DEFAULT NULL,
  `DniReceptor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reuniones`
--

CREATE TABLE `reuniones` (
  `Titulo` varchar(50) NOT NULL,
  `Fecha` date NOT NULL,
  `HoraInicio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `HoraFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Descripcion` varchar(190) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `TipoSolicitud` varchar(50) NOT NULL,
  `DniSolicitante` int(11) NOT NULL,
  `FechaHoraDesde` date NOT NULL,
  `FechaHoraHasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Dni` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `CorreoElectronico` varchar(100) NOT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `TipoDeUsuario` varchar(50) NOT NULL,
  `Departamento` varchar(50) DEFAULT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Dni`, `Nombre`, `Apellido`, `FechaNacimiento`, `Direccion`, `CorreoElectronico`, `Telefono`, `TipoDeUsuario`, `Departamento`, `clave`) VALUES
(12345678, 'Juan', 'Pérez', '1990-05-15', 'Calle Falsa 123', 'juan.perez@example.com', '555123456', 'Admin', NULL, '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_reuniones`
--

CREATE TABLE `usuarios_reuniones` (
  `DniUsuario` int(11) NOT NULL,
  `FechaReunion` date NOT NULL,
  `HoraInicioReunion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `HoraFinReunion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD PRIMARY KEY (`Nombre`,`FechaCreacion`),
  ADD KEY `fk_mensajes_archivos` (`DniCreador`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`Nombre`),
  ADD UNIQUE KEY `DirectorACargo` (`DirectorACargo`),
  ADD KEY `departamentos_ibfk_1` (`DirectorACargo`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`FechaHoraMensaje`,`DniRemitente`),
  ADD KEY `fk_mensajes_usuarios1` (`DniRemitente`),
  ADD KEY `fk_mensajes_usuarios2` (`DniReceptor`);

--
-- Indices de la tabla `reuniones`
--
ALTER TABLE `reuniones`
  ADD PRIMARY KEY (`Fecha`,`HoraInicio`,`HoraFin`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`TipoSolicitud`,`DniSolicitante`,`FechaHoraDesde`),
  ADD KEY `fk_solicitudes_Usuario1` (`DniSolicitante`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Dni`),
  ADD KEY `usuarios_ibfk_1` (`Departamento`);

--
-- Indices de la tabla `usuarios_reuniones`
--
ALTER TABLE `usuarios_reuniones`
  ADD PRIMARY KEY (`DniUsuario`,`FechaReunion`,`HoraInicioReunion`,`HoraFinReunion`),
  ADD KEY `fk_usuarios_reuniones1` (`FechaReunion`,`HoraInicioReunion`,`HoraFinReunion`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivos`
--
ALTER TABLE `archivos`
  ADD CONSTRAINT `fk_mensajes_archivos` FOREIGN KEY (`DniCreador`) REFERENCES `mensajes` (`DniRemitente`);

--
-- Filtros para la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`DirectorACargo`) REFERENCES `usuarios` (`Dni`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `fk_mensajes_usuarios1` FOREIGN KEY (`DniRemitente`) REFERENCES `usuarios` (`Dni`),
  ADD CONSTRAINT `fk_mensajes_usuarios2` FOREIGN KEY (`DniReceptor`) REFERENCES `usuarios` (`Dni`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `fk_solicitudes_Usuario1` FOREIGN KEY (`DniSolicitante`) REFERENCES `usuarios` (`Dni`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`Departamento`) REFERENCES `departamentos` (`Nombre`);

--
-- Filtros para la tabla `usuarios_reuniones`
--
ALTER TABLE `usuarios_reuniones`
  ADD CONSTRAINT `fk_usuarios_reuniones` FOREIGN KEY (`DniUsuario`) REFERENCES `usuarios` (`Dni`),
  ADD CONSTRAINT `fk_usuarios_reuniones1` FOREIGN KEY (`FechaReunion`,`HoraInicioReunion`,`HoraFinReunion`) REFERENCES `reuniones` (`Fecha`, `HoraInicio`, `HoraFin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
