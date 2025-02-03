-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2025 a las 14:14:48
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

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`Nombre`, `DirectorACargo`) VALUES
('MARIO', 33145232);

--
-- Disparadores `departamentos`
--
DELIMITER $$
CREATE TRIGGER `actualizar_departamento` AFTER INSERT ON `departamentos` FOR EACH ROW BEGIN
    UPDATE usuarios
    SET Departamento = NEW.Nombre
    WHERE Dni = NEW.DirectorACargo;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_departamento_update` AFTER UPDATE ON `departamentos` FOR EACH ROW BEGIN
    UPDATE usuarios
    SET Departamento = NEW.Nombre
    WHERE Dni = NEW.DirectorACargo;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_departamento_usuario` AFTER DELETE ON `departamentos` FOR EACH ROW BEGIN
      IF EXISTS (SELECT 1 FROM usuarios WHERE Departamento = OLD.Nombre) THEN
        -- Actualiza el departamento a "Sin Departamento"
        UPDATE usuarios 
        SET Departamento = 'Sin Departamento'
        WHERE Departamento = OLD.Nombre;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `cambiar_departamento_despues_dni` AFTER UPDATE ON `departamentos` FOR EACH ROW BEGIN
     IF OLD.DirectorACargo <> NEW.DirectorACargo THEN
        -- Actualiza el departamento a "Sin Departamento"
        UPDATE usuarios 
        SET Departamento = 'Sin Departamento'
        WHERE Dni = OLD.DirectorACargo;
    END IF;
END
$$
DELIMITER ;

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
  `FechaHoraHasta` date DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  `id_licencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`TipoSolicitud`, `DniSolicitante`, `FechaHoraDesde`, `FechaHoraHasta`, `Estado`, `id_licencia`) VALUES
('maternidad', 20406012, '2025-01-22', '2025-01-24', 'Rechazada', 1),
('vacaciones', 20406012, '2025-01-16', '2025-01-17', 'Pendiente', 2);

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
  `Departamento` varchar(255) DEFAULT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Dni`, `Nombre`, `Apellido`, `FechaNacimiento`, `Direccion`, `CorreoElectronico`, `Telefono`, `TipoDeUsuario`, `Departamento`, `clave`) VALUES
(0, 'mariano', 'henry', '2006-12-19', 'dwqdwqdqw', 'pepuman2009@hotmail.com', '3415982912', 'Directivo', 'Sin Departamento', '$2y$10$PDJZVxMTDKB0yKcEbZwrFuVs2R729rAZXoGdU5C5GCuvY2dVd7HF.'),
(11100003, 'holaa', 'dwdqd', '2006-12-15', 'dqdwqdwqdqwdqdwdqwwd', 'dwqdwqddwdq@gmail.com', '32132555123', 'Directivo', 'Sin Departamento', '$2y$10$d21.hGI7SKcq7meGVWXBiejg8DK2IDPiF6r2fXqKOJmMBaMNAAPHO'),
(12232324, 'mariano', 'henry', '2006-12-20', 'aaaaaaaaaaaaa', 'aaaa@gmail.com', '3213551232', 'Directivo', 'Sin Departamento', '$2y$10$rAS0F4xXU9rwOX83VjuqgO4/7zepkZ685283yhNZojFpC8DhckkVu'),
(20406012, 'Juan', 'Doe', '1999-01-23', '254 Avenida Principal, Rosario', 'pedroroberti99@gmail.com', '3415596846', 'RRHH', 'Sin Departamento', '$2y$10$z.f4YQN.va0USkeuY2NTDOoCHCNMAZpaHMTaZRE3S8EC/Ix8DDFKm'),
(32132131, 'pedro', 'roberti', '2006-12-20', 'dqdwqdwqdqwdqdwdqwwd', 'dwqdwqddwq@gmail.com', '32131232132', 'Empleado', 'Sin Departamento', '$2y$10$6dj7zHQ0zyCsEPXb0nR/xeyhqIuKSZWbop.xx9iJEF1/1flIB0x/6'),
(33145232, 'matias', 'reinati', '2006-12-20', 'dwqdwqdqw', 'silviamcrescente@gmail.com', '32131232132', 'Directivo', 'Sin Departamento', '$2y$10$ED4403SQeCkF1wweBYGR/uIHWWtIInBedF0Nx9ZP2hrjRrYrt0RWy');

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
  ADD UNIQUE KEY `id_licencia` (`id_licencia`),
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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_licencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`DirectorACargo`) REFERENCES `usuarios` (`Dni`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `usuarios_reuniones`
--
ALTER TABLE `usuarios_reuniones`
  ADD CONSTRAINT `fk_usuarios_reuniones` FOREIGN KEY (`DniUsuario`) REFERENCES `usuarios` (`Dni`),
  ADD CONSTRAINT `fk_usuarios_reuniones1` FOREIGN KEY (`FechaReunion`,`HoraInicioReunion`,`HoraFinReunion`) REFERENCES `reuniones` (`Fecha`, `HoraInicio`, `HoraFin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
