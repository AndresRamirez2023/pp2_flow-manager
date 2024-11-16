-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_flowmanager
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  PRIMARY KEY (`Dni`),
  KEY `usuarios_ibfk_1` (`Departamento`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`Departamento`) REFERENCES `departamentos` (`Nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `Nombre` varchar(50) NOT NULL,
  `DirectorACargo` int(11) NOT NULL UNIQUE,
  PRIMARY KEY (`Nombre`),
  KEY `departamentos_ibfk_1` (`DirectorACargo`),
  CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`DirectorACargo`) REFERENCES `usuarios` (`Dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `archivos`
--

DROP TABLE IF EXISTS `archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivos` (
  `Nombre` varchar(50) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `Contenido` blob DEFAULT NULL,
  `DniCreador` int(11) NOT NULL,
  PRIMARY KEY (`Nombre`,`FechaCreacion`)
  CONSTRAINT `fk_mensajes_archivos` FOREIGN KEY (`DniCreador`) REFERENCES `mensajes` (`DniRemitente`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes` (
  `FechaHoraMensaje` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `TituloMensaje` varchar(50) NOT NULL,
  `DniRemitente` int(11) NOT NULL,
  `TipoMensaje` varchar(50) NOT NULL,
  `CuerpoMensaje` varchar(160) DEFAULT NULL,
  `DniReceptor` int(11) NOT NULL,
  PRIMARY KEY (`FechaHoraMensaje`,`DniRemitente`),
  KEY `fk_mensajes_usuarios1` (`DniRemitente`),
  KEY `fk_mensajes_usuarios2` (`DniReceptor`),
  CONSTRAINT `fk_mensajes_usuarios1` FOREIGN KEY (`DniRemitente`) REFERENCES `usuarios` (`Dni`),
  CONSTRAINT `fk_mensajes_usuarios2` FOREIGN KEY (`DniReceptor`) REFERENCES `usuarios` (`Dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reuniones`
--

DROP TABLE IF EXISTS `reuniones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reuniones` (
  `Titulo` varchar(50) NOT NULL,
  `Fecha` date NOT NULL,
  `HoraInicio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `HoraFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Descripcion` varchar(190) NULL,
  PRIMARY KEY (`Fecha`,`HoraInicio`,`HoraFin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_reuniones`
--

DROP TABLE IF EXISTS `usuarios_reuniones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_reuniones` (
  `DniUsuario` int(11) NOT NULL,
  `FechaReunion` date NOT NULL,
  `HoraInicioReunion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `HoraFinReunion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`DniUsuario`,`FechaReunion`,`HoraInicioReunion`,`HoraFinReunion`),
  KEY `fk_usuarios_reuniones1` (`FechaReunion`,`HoraInicioReunion`,`HoraFinReunion`),
  CONSTRAINT `fk_usuarios_reuniones` FOREIGN KEY (`DniUsuario`) REFERENCES `usuarios` (`Dni`),
  CONSTRAINT `fk_usuarios_reuniones1` FOREIGN KEY (`FechaReunion`, `HoraInicioReunion`, `HoraFinReunion`) REFERENCES `reuniones` (`Fecha`, `HoraInicio`, `HoraFin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes` (
  `TipoSolicitud` varchar(50) NOT NULL,
  `DniSolicitante` int(11) NOT NULL,
  `FechaHoraDesde` date DEFAULT NOT NULL,
  `FechaHoraHasta` date DEFAULT NULL,
  PRIMARY KEY (`TipoSolicitud`,`DniSolicitante`, `FechaHoraDesde`),
  KEY `fk_solicitudes_Usuario1` (`DniSolicitante`),
  CONSTRAINT `fk_solicitudes_Usuario1` FOREIGN KEY (`DniSolicitante`) REFERENCES `usuarios` (`Dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-21 22:15:16
