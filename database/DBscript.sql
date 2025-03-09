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
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Dni` int(11) NOT NULL,
  `NombreApellido` varchar(100) NOT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Domicilio` varchar(100) DEFAULT NULL,
  `CorreoElectronico` varchar(100) NOT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `TipoDeUsuario` varchar(50) NOT NULL,
  `Departamento` varchar(255) NOT NULL,
  `Clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers de `usuarios`
--

DELIMITER $$
CREATE TRIGGER `before_insert_usuario` BEFORE INSERT ON `usuarios`
FOR EACH ROW
BEGIN
    DECLARE director_actual INT;

    IF NOT EXISTS (SELECT 1 FROM departamentos WHERE Nombre = NEW.Departamento) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se puede asignar un usuario a un departamento inexistente';
    END IF;

    IF NEW.TipoDeUsuario = 'Directivo' THEN
        SELECT DirectorACargo INTO director_actual
        FROM departamentos
        WHERE Nombre = NEW.Departamento;

        IF director_actual IS NOT NULL THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Error: El departamento ya tiene un director asignado.';
        END IF;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_insert_usuario AFTER INSERT ON usuarios
FOR EACH ROW 
BEGIN
    DECLARE empresa_nombre VARCHAR(100);
    DECLARE usuario_actual INT;
    DECLARE director_actual INT;

    -- Obtener la empresa del usuario
    SELECT Empresa INTO empresa_nombre
    FROM departamentos 
    WHERE Nombre = NEW.Departamento
    LIMIT 1;

    IF NEW.TipoDeUsuario = 'RRHH' THEN
        SELECT UsuarioPrincipal INTO usuario_actual
        FROM empresas
        WHERE Nombre = empresa_nombre;

        IF usuario_actual IS NULL THEN
            UPDATE empresas
            SET UsuarioPrincipal = NEW.Dni
            WHERE Nombre = empresa_nombre;
        END IF;
    END IF;

    IF NEW.TipoDeUsuario = 'Directivo' AND NOT NEW.Departamento LIKE CONCAT(empresa_nombre, '_Sin asignar') THEN
        SELECT DirectorACargo INTO director_actual
        FROM departamentos
        WHERE Nombre = NEW.Departamento;

        IF director_actual IS NULL THEN
              UPDATE departamentos
              SET DirectorACargo = NEW.Dni
              WHERE Nombre = NEW.Departamento;
        END IF;
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `before_delete_usuario` BEFORE DELETE ON `usuarios`
FOR EACH ROW
BEGIN
    UPDATE empresas
    SET UsuarioPrincipal = NULL
    WHERE UsuarioPrincipal = OLD.Dni;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `after_delete_usuario` AFTER DELETE ON `usuarios`
FOR EACH ROW 
BEGIN
    DECLARE empresa_nombre VARCHAR(100);
    DECLARE nuevo_usuario INT;

    SELECT Empresa INTO empresa_nombre
    FROM departamentos
    WHERE Nombre = OLD.Departamento
    LIMIT 1;

    IF (SELECT UsuarioPrincipal FROM empresas WHERE Nombre = empresa_nombre) = OLD.Dni THEN
        SELECT Dni INTO nuevo_usuario
        FROM usuarios 
        WHERE TipoDeUsuario = 'Directivo' 
        AND Departamento = 'Recursos Humanos'
        ORDER BY Dni LIMIT 1;

        IF nuevo_usuario IS NULL THEN
            SELECT Dni INTO nuevo_usuario
            FROM usuarios 
            WHERE TipoDeUsuario = 'RRHH' 
            AND Departamento = 'Recursos Humanos'
            ORDER BY Dni LIMIT 1;
        END IF;

        UPDATE empresas 
        SET UsuarioPrincipal = nuevo_usuario
        WHERE Nombre = empresa_nombre;
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `before_update_usuario` BEFORE UPDATE ON `usuarios`
FOR EACH ROW 
BEGIN
    DECLARE director_actual INT;
    
    IF NEW.TipoDeUsuario = 'RRHH' AND OLD.TipoDeUsuario <> 'RRHH' THEN
        SET NEW.Departamento = 'Recursos Humanos';
    END IF;

    IF NEW.TipoDeUsuario = 'Empleado' AND NEW.Departamento = 'Recursos Humanos' THEN
        SET NEW.TipoDeUsuario = 'RRHH';
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `after_update_usuario` AFTER UPDATE ON `usuarios`
FOR EACH ROW 
BEGIN
    DECLARE empresa_nombre VARCHAR(100);
    DECLARE director_actual INT;

    SELECT DirectorACargo INTO director_actual
    FROM departamentos
    WHERE Nombre = NEW.Departamento;

    -- Obtener la empresa del usuario
    SELECT Empresa INTO empresa_nombre
    FROM departamentos 
    WHERE Nombre = NEW.Departamento
    LIMIT 1;

    IF OLD.TipoDeUsuario = 'Directivo' AND NEW.TipoDeUsuario <> 'Directivo' THEN
        UPDATE departamentos
        SET DirectorACargo = NULL
        WHERE DirectorACargo = OLD.Dni;
    END IF;
END $$
DELIMITER ;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresas` (
  `Nombre` varchar(100) NOT NULL,
  `Logo` varchar(255) DEFAULT NULL,
  `Fondo` varchar(255) DEFAULT NULL,
  `ArchivoInicio1` varchar(255) DEFAULT NULL,
  `ArchivoInicio2` varchar(255) DEFAULT NULL,
  `UsuarioPrincipal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers de `empresas`
--

DELIMITER $$
CREATE TRIGGER `before_delete_empresa` 
BEFORE DELETE ON `empresas`
FOR EACH ROW
BEGIN
    DELETE FROM usuarios 
    WHERE Departamento IN (SELECT Nombre FROM departamentos WHERE Empresa = OLD.Nombre);

    DELETE FROM departamentos 
    WHERE Empresa = OLD.Nombre;
END$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `Nombre` varchar(50) NOT NULL,
  `Empresa` varchar(100) NOT NULL,
  `DirectorACargo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers de `departamentos`
--
DELIMITER $$
CREATE TRIGGER `before_insert_departamento` BEFORE INSERT ON `departamentos`
FOR EACH ROW
BEGIN

    IF NOT EXISTS (SELECT 1 FROM empresas WHERE Nombre = NEW.Empresa) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se puede crear un departamento para una empresa inexistente';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `before_delete_departamento` 
BEFORE DELETE ON `departamentos`
FOR EACH ROW 
BEGIN
    DECLARE departamento_sin_asignar VARCHAR(255);

    SELECT Nombre INTO departamento_sin_asignar 
    FROM departamentos 
    WHERE Empresa = OLD.Empresa AND Nombre LIKE '%_Sin asignar'
    LIMIT 1;

    IF departamento_sin_asignar IS NOT NULL THEN
        UPDATE usuarios 
        SET Departamento = departamento_sin_asignar 
        WHERE Departamento = OLD.Nombre;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede eliminar el departamento porque no existe un departamento "Sin asignar" en esta empresa.';
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `after_insert_departamento` AFTER INSERT ON `departamentos`
FOR EACH ROW
BEGIN

    IF NEW.DirectorACargo IS NOT NULL THEN
        UPDATE usuarios
        SET Departamento = NEW.Nombre
        WHERE Dni = NEW.DirectorACargo;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `after_update_director_departamento` AFTER UPDATE ON `departamentos`
FOR EACH ROW
BEGIN

    IF OLD.DirectorACargo <> NEW.DirectorACargo THEN
        UPDATE usuarios 
        SET Departamento = CONCAT(OLD.Empresa, '_Sin asignar')
        WHERE Dni = OLD.DirectorACargo;
        IF NEW.DirectorACargo IS NOT NULL THEN
            UPDATE usuarios
            SET Departamento = NEW.Nombre
            WHERE Dni = NEW.DirectorACargo;
        END IF;
    END IF;

END$$
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
  `Estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `super_usuario`
--

CREATE TABLE `super_usuario` (
  `Username` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `super_usuario` (`username`, `password`) VALUES
('admin', '$2y$10$9OBk3EmUy2wOxqMPXNRARe2iG1cuyu2qY1PwWtiDbtsio99PoRMUW');


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
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`Nombre`);

--
-- Indices de la tabla `super_usuario`
--
ALTER TABLE `super_usuario`
  ADD PRIMARY KEY (`username`);

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
-- Filtros para la tabla `empresas`
-- 
ALTER TABLE `empresas`
  ADD CONSTRAINT `empresas_ibfk_1` FOREIGN KEY (`UsuarioPrincipal`) REFERENCES `usuarios` (`Dni`);

--
-- Filtros para la tabla `departamentos`
--
ALTER TABLE `departamentos` 
  ADD CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`DirectorACargo`) REFERENCES `usuarios` (`Dni`) ON DELETE SET NULL,
  ADD CONSTRAINT `departamentos_ibfk_2` FOREIGN KEY (`Empresa`) REFERENCES `empresas` (`Nombre`);

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
