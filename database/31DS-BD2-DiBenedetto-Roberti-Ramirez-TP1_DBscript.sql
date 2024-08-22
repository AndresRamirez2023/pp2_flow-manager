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
  `Nombre` varchar(50) DEFAULT NULL,
  `Apellido` varchar(50) DEFAULT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `CorreoElectronico` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `TipoDeUsuario` varchar(50) NOT NULL DEFAULT 'Empleado',
  `Departamento` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Dni`),
  KEY `usuarios_ibfk_1` (`Departamento`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`Departamento`) REFERENCES `departamentos` (`Nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (42178619,'Juan','Perez','1980-01-01','Calle 123','juan.perez@ejemplo.com','123456789','Director','Ventas'),(42178620,'Maria','Gomez','1985-02-02','Calle 456','maria.gomez@ejemplo.com','234567890','Director','Marketing'),(42178621,'Pedro','Lopez','1990-03-03','Calle 789','pedro.lopez@ejemplo.com','345678901','Director','Recursos Humanos'),(42178622,'Ana','Martinez','1995-04-04','Calle 012','ana.martinez@ejemplo.com','456789012','Director','IT'),(42178623,'Luis','Garcia','2000-05-05','Calle 345','luis.garcia@ejemplo.com','567890123','Director','Finanzas'),(42178624,'Laura','Rodriguez','1975-06-06','Calle 678','laura.rodriguez@ejemplo.com','678901234','Director','Logística'),(42178625,'Carlos','Hernandez','1988-07-07','Calle 901','carlos.hernandez@ejemplo.com','789012345','Director','Compras'),(42178626,'Lucia','Fernandez','1992-08-08','Calle 234','lucia.fernandez@ejemplo.com','890123456','Director','Producción'),(42178627,'Jose','Ramirez','1979-09-09','Calle 567','jose.ramirez@ejemplo.com','901234567','Director','Calidad'),(42178628,'Marta','Sanchez','1983-10-10','Calle 890','marta.sanchez@ejemplo.com','012345678','Director','Mantenimiento'),(42178629,'Sofia','Ruiz','1982-11-11','Calle 101','sofia.ruiz@example.com','098765432','Empleado','Ventas'),(42178630,'Diego','Mendoza','1987-12-12','Calle 202','diego.mendoza@example.com','109876543','Empleado','Marketing'),(42178631,'Paula','Diaz','1993-01-13','Calle 303','paula.diaz@example.com','210987654','Empleado','Recursos Humanos'),(42178632,'Miguel','Ortiz','1998-02-14','Calle 404','miguel.ortiz@example.com','321098765','Empleado','IT'),(42178633,'Elena','Sosa','1974-03-15','Calle 505','elena.sosa@example.com','432109876','Empleado','Finanzas'),(42178634,'Alejandro','Gutierrez','1984-04-16','Calle 606','alejandro.gutierrez@ejemplo.com','543210987','Empleado','Logística'),(42178635,'Adriana','Rojas','1989-05-17','Calle 707','adriana.rojas@ejemplo.com','654321098','Empleado','Compras'),(42178636,'Ricardo','Alvarez','1994-06-18','Calle 808','ricardo.alvarez@ejemplo.com','765432109','Empleado','Producción'),(42178637,'Isabel','Torres','1978-07-19','Calle 909','isabel.torres@ejemplo.com','876543210','Empleado','Calidad'),(42178638,'Roberto','Nunez','1986-08-20','Calle 0123','roberto.nunez@ejemplo.com','987654321','Empleado','Mantenimiento'),(42178639,'Ana','Luna','1991-09-21','Calle 1234','ana.luna@ejemplo.com','098765432','Empleado','Ventas'),(42178640,'Javier','Santos','1996-10-22','Calle 2345','javier.santos@ejemplo.com','109876543','Empleado','Marketing'),(42178641,'Lucas','Castro','1977-11-23','Calle 3456','lucas.castro@ejemplo.com','210987654','Empleado','Recursos Humanos'),(42178642,'Carolina','Flores','1981-12-24','Calle 4567','carolina.flores@ejemplo.com','321098765','Empleado','IT'),(42178643,'Gustavo','Dominguez','1983-01-25','Calle 5678','gustavo.dominguez@ejemplo.com','432109876','Empleado','Finanzas'),(42178644,'Marina','Cabrera','1985-02-26','Calle 6789','marina.cabrera@ejemplo.com','543210987','Empleado','Logística'),(42178645,'Fernando','Molina','1990-03-27','Calle 7890','fernando.molina@ejemplo.com','654321098','Empleado','Compras'),(42178646,'Victoria','Garcia','1995-04-28','Calle 8901','victoria.garcia@ejemplo.com','765432109','Empleado','Producción'),(42178647,'Gabriel','Perez','2000-05-29','Calle 9012','gabriel.perez@ejemplo.com','876543210','Empleado','Calidad'),(42178648,'Valentina','Sanchez','1976-06-30','Calle 01234','valentina.sanchez@ejemplo.com','987654321','Empleado','Mantenimiento');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES ('Ventas',42178619),('Marketing',42178620),('Recursos Humanos',42178621),('IT',42178622),('Finanzas',42178623),('Logística',42178624),('Compras',42178625),('Producción',42178626),('Calidad',42178627),('Mantenimiento',42178628);
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archivos`
--

DROP TABLE IF EXISTS `archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivos` (
  `TipoArchivo` varchar(50) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `FechaCreacion` date NOT NULL,
  `Contenido` blob DEFAULT NULL,
  PRIMARY KEY (`FechaCreacion`,`TipoArchivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archivos`
--

LOCK TABLES `archivos` WRITE;
/*!40000 ALTER TABLE `archivos` DISABLE KEYS */;
INSERT INTO `archivos` VALUES ('PDF','documento4.pdf','2024-05-13',_binary 'Privado'),('Audio','audio3.mp3','2024-05-14',_binary 'Privado'),('Imagen','imagen3.jpg','2024-05-15',_binary 'Publico'),('PDF','documento3.pdf','2024-05-16',_binary 'Privado'),('Audio','audio2.mp3','2024-05-17',_binary 'Privado'),('Imagen','imagen2.jpg','2024-05-18',_binary 'Publico'),('PDF','documento2.pdf','2024-05-19',_binary 'Privado'),('Audio','audio1.mp3','2024-05-20',_binary 'Privado'),('Imagen','imagen1.jpg','2024-05-21',_binary 'Publico'),('PDF','documento1.pdf','2024-05-22',_binary 'Privado');
/*!40000 ALTER TABLE `archivos` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
INSERT INTO `mensajes` (`FechaHoraMensaje`, `TituloMensaje`, `DniRemitente`, `TipoMensaje`, `CuerpoMensaje`, `DniReceptor`) VALUES 
('2024-05-13 20:00:00', 'Aviso Importante', 42178619, 'Aviso', 'Recuerda la reunión mañana.', 42178629),
('2024-05-14 19:00:00', 'Consulta sobre el Proyecto', 42178620, 'Consulta', '¿Tienes alguna duda sobre el proyecto?', 42178630),
('2024-05-15 18:00:00', 'Mensaje de Agradecimiento', 42178621, 'Mensaje', 'Gracias por tu ayuda en el proyecto.', 42178631),
('2024-05-16 17:00:00', 'Recibo de Sueldo', 42178621, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178632),
('2024-05-17 16:00:00', 'Solicitud de Revisión', 42178622, 'Documentación', 'Por favor, revisa y aprueba este documento.', 42178633),
('2024-05-18 15:00:00', 'Actualización de Contrato', 42178623, 'Documentación', 'Adjunto la última versión del contrato.', 42178634),
('2024-05-19 14:00:00', 'Recordatorio de Reunión', 42178624, 'Aviso', 'Recordatorio: Reunión con el cliente a las 11:00 AM.', 42178635),
('2024-05-20 13:00:00', 'Felicitaciones', 42178625, 'Mensaje', '¡Enhorabuena por tu excelente trabajo en el proyecto!', 42178636),
('2024-05-21 12:00:00', 'Informe de Progreso', 42178626, 'Documentación', 'Adjunto el informe de progreso del proyecto.', 42178637),
('2024-05-22 11:00:00', 'Reunión de Planificación', 42178627, 'Aviso', 'Recordatorio: Reunión de planificación a las 9:00 AM.', 42178638),

('2024-05-23 10:00:00', 'Recibo de Sueldo', 42178621, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178639),
('2024-05-24 09:00:00', 'Consulta Técnica', 42178622, 'Consulta', '¿Cómo puedo acceder al servidor?', 42178640),
('2024-05-25 08:00:00', 'Mensaje de Bienvenida', 42178623, 'Mensaje', 'Bienvenido al equipo, esperamos que tengas una excelente experiencia.', 42178641),
('2024-05-26 07:00:00', 'Recibo de Sueldo', 42178631, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178642),
('2024-05-27 06:00:00', 'Recibo de Sueldo', 42178631, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178643),
('2024-05-28 05:00:00', 'Recibo de Sueldo', 42178631, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178644),
('2024-05-29 04:00:00', 'Recibo de Sueldo', 42178641, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178645),
('2024-05-30 03:00:00', 'Recibo de Sueldo', 42178641, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178646),
('2024-05-31 02:00:00', 'Recibo de Sueldo', 42178641, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178647),
('2024-06-01 01:00:00', 'Reunión de Planificación', 42178628, 'Aviso', 'Recordatorio: Reunión de planificación a las 9:00 AM.', 42178648),

('2024-06-02 14:00:00', 'Aviso de Actualización', 42178619, 'Aviso', 'Se realizará una actualización del sistema mañana.', 42178629),
('2024-06-03 13:00:00', 'Consulta de Disponibilidad', 42178620, 'Consulta', '¿Estarás disponible para una reunión el viernes?', 42178630),
('2024-06-04 12:00:00', 'Mensaje de Agradecimiento', 42178621, 'Mensaje', 'Gracias por tu ayuda en el proyecto.', 42178631),
('2024-06-05 11:00:00', 'Recibo de Sueldo', 42178621, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178632),
('2024-06-06 10:00:00', 'Solicitud de Revisión', 42178622, 'Documentación', 'Por favor, revisa este documento antes del viernes.', 42178633),
('2024-06-07 09:00:00', 'Actualización de Plan', 42178623, 'Documentación', 'Adjunto la última versión del plan del proyecto.', 42178634),
('2024-06-08 08:00:00', 'Recordatorio de Entrega', 42178624, 'Aviso', 'Recuerda entregar el informe antes del jueves.', 42178635),
('2024-06-09 07:00:00', 'Felicitaciones', 42178625, 'Mensaje', '¡Felicidades por completar el proyecto a tiempo!', 42178636),
('2024-06-10 06:00:00', 'Informe de Resultados', 42178626, 'Documentación', 'Adjunto el informe de resultados del último trimestre.', 42178637),
('2024-06-11 05:00:00', 'Reunión de Seguimiento', 42178627, 'Aviso', 'Recordatorio: Reunión de seguimiento a las 10:00 AM.', 42178638),

('2024-06-12 16:00:00', 'Recibo de Sueldo', 42178621, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178639),
('2024-06-13 15:00:00', 'Consulta de Proyecto', 42178622, 'Consulta', '¿Cuándo estará listo el informe del proyecto?', 42178640),
('2024-06-14 14:00:00', 'Mensaje de Confirmación', 42178623, 'Mensaje', 'Confirmo la recepción del documento.', 42178641),
('2024-06-15 13:00:00', 'Recibo de Sueldo', 42178631, 'Recibo', 'Adjunto tu recibo de sueldo del mes.', 42178642),
('2024-06-16 12:00:00', 'Solicitud de Aprobación', 42178631, 'Documentación', 'Necesito tu aprobación para este presupuesto.', 42178643),
('2024-06-17 11:00:00', 'Actualización de Política', 42178631, 'Documentación', 'Adjunto la nueva política de seguridad.', 42178644),
('2024-06-18 10:00:00', 'Recordatorio de Tarea', 42178641, 'Aviso', 'Recuerda completar la tarea asignada.', 42178645),
('2024-06-19 09:00:00', 'Felicitaciones', 42178641, 'Mensaje', '¡Excelente trabajo en la presentación!', 42178646),
('2024-06-20 08:00:00', 'Informe de Actividades', 42178641, 'Documentación', 'Adjunto el informe de actividades del mes.', 42178647),
('2024-06-21 07:00:00', 'Reunión de Coordinación', 42178628, 'Aviso', 'Recordatorio: Reunión de coordinación a las 9:00 AM.', 42178648);
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes_archivos`
--

DROP TABLE IF EXISTS `mensajes_archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes_archivos` (
  `FechaHoraMensaje` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DniRemitente` int(11) NOT NULL,
  `TipoArchivo` varchar(50) NOT NULL,
  `FechaHoraArchivo` date NOT NULL,
  PRIMARY KEY (`FechaHoraMensaje`,`DniRemitente`,`TipoArchivo`,`FechaHoraArchivo`),
  KEY `fk_mensajes_archivos1` (`FechaHoraArchivo`,`TipoArchivo`),
  KEY `fk_mensajes_archivos2` (`FechaHoraMensaje`,`DniRemitente`),
  CONSTRAINT `fk_mensajes_archivos1` FOREIGN KEY (`FechaHoraArchivo`, `TipoArchivo`) REFERENCES `archivos` (`FechaCreacion`, `TipoArchivo`),
  CONSTRAINT `fk_mensajes_archivos2` FOREIGN KEY (`FechaHoraMensaje`, `DniRemitente`) REFERENCES `mensajes` (`FechaHoraMensaje`, `DniRemitente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes_archivos`
--

LOCK TABLES `mensajes_archivos` WRITE;
/*!40000 ALTER TABLE `mensajes_archivos` DISABLE KEYS */;
INSERT INTO `mensajes_archivos` VALUES ('2024-05-13 20:00:00',42178628,'PDF','2024-05-13'),('2024-05-14 19:00:00',42178627,'Audio','2024-05-14'),('2024-05-15 18:00:00',42178626,'Imagen','2024-05-15'),('2024-05-16 17:00:00',42178625,'PDF','2024-05-16'),('2024-05-17 16:00:00',42178624,'Audio','2024-05-17'),('2024-05-18 15:00:00',42178623,'Imagen','2024-05-18'),('2024-05-19 14:00:00',42178622,'PDF','2024-05-19'),('2024-05-20 13:00:00',42178621,'Audio','2024-05-20'),('2024-05-21 12:00:00',42178620,'Imagen','2024-05-21'),('2024-05-22 11:00:00',42178619,'PDF','2024-05-22');
/*!40000 ALTER TABLE `mensajes_archivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reuniones`
--

DROP TABLE IF EXISTS `reuniones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reuniones` (
  `Tema` varchar(50) NOT NULL,
  `Fecha` date NOT NULL,
  `HoraInicio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `HoraFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Descripcion` varchar(190) NOT NULL,
  PRIMARY KEY (`Fecha`,`HoraInicio`,`HoraFin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reuniones`
--

LOCK TABLES `reuniones` WRITE;
/*!40000 ALTER TABLE `reuniones` DISABLE KEYS */;
INSERT INTO `reuniones` VALUES ('Entrevista de Evaluación','2024-05-28','2024-05-28 17:00:00','2024-05-28 18:00:00','Entrevista de evaluación de desempeño.'),('Reunión de Proyectos','2024-06-01','2024-06-01 12:00:00','2024-06-01 14:00:00','Discusión sobre avance de proyectos.'),('Reunión de Ventas','2024-06-05','2024-06-05 13:00:00','2024-06-05 15:00:00','Revisión de estrategias de ventas.'),('Reunión de Finanzas','2024-06-15','2024-06-15 14:00:00','2024-06-15 15:30:00','Presentación de informes financieros y presupuesto.'),('Reunión de IT','2024-07-02','2024-07-02 18:00:00','2024-07-02 20:00:00','Planificación de mejoras en infraestructura tecnológica.'),('Entrevista de Contratación','2024-07-10','2024-07-10 13:00:00','2024-07-10 14:30:00','Entrevista para posición de gerente de ventas.'),('Reunión de Calidad','2024-07-25','2024-07-25 16:00:00','2024-07-25 18:00:00','Evaluación de procesos y calidad de productos.'),('Reunión de Producción','2024-08-05','2024-08-05 11:30:00','2024-08-05 13:30:00','Revisión de procesos y mejoras en producción.'),('Reunión de Mantenimiento','2024-08-20','2024-08-20 12:30:00','2024-08-20 14:30:00','Planificación de mantenimiento preventivo y correctivo.'),('Reunión de Marketing','2024-09-05','2024-09-05 17:00:00','2024-09-05 19:00:00','Presentación de estrategias de marketing para próximo trimestre.');
/*!40000 ALTER TABLE `reuniones` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `usuarios_reuniones`
--

LOCK TABLES `usuarios_reuniones` WRITE;
/*!40000 ALTER TABLE `usuarios_reuniones` DISABLE KEYS */;
INSERT INTO `usuarios_reuniones` VALUES (42178620,'2024-05-28','2024-05-28 17:00:00','2024-05-28 18:00:00'),(42178621,'2024-06-01','2024-06-01 12:00:00','2024-06-01 14:00:00'),(42178619,'2024-06-05','2024-06-05 13:00:00','2024-06-05 15:00:00'),(42178623,'2024-06-15','2024-06-15 14:00:00','2024-06-15 15:30:00'),(42178622,'2024-07-02','2024-07-02 18:00:00','2024-07-02 20:00:00'),(42178624,'2024-07-10','2024-07-10 13:00:00','2024-07-10 14:30:00'),(42178626,'2024-07-25','2024-07-25 16:00:00','2024-07-25 18:00:00'),(42178625,'2024-08-05','2024-08-05 11:30:00','2024-08-05 13:30:00'),(42178627,'2024-08-20','2024-08-20 12:30:00','2024-08-20 14:30:00'),(42178628,'2024-09-05','2024-09-05 17:00:00','2024-09-05 19:00:00');
/*!40000 ALTER TABLE `usuarios_reuniones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes` (
  `TipoSolicitud` varchar(50) NOT NULL,
  `DniSolicitante` int(11) NOT NULL,
  `FechaDesde` date DEFAULT NULL,
  `FechaHasta` date DEFAULT NULL,
  PRIMARY KEY (`TipoSolicitud`,`DniSolicitante`),
  KEY `fk_solicitudes_Usuario1` (`DniSolicitante`),
  CONSTRAINT `fk_solicitudes_Usuario1` FOREIGN KEY (`DniSolicitante`) REFERENCES `usuarios` (`Dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes`
--

LOCK TABLES `solicitudes` WRITE;
/*!40000 ALTER TABLE `solicitudes` DISABLE KEYS */;
INSERT INTO `solicitudes` VALUES ('Licencia dia de estudio',42178620,'2024-05-25','2024-05-26'),('Permiso',42178621,'2024-05-30','2024-06-02'),('Permiso',42178628,'2024-09-20','2024-09-21'),('Permiso por Asuntos Personales',42178627,'2024-08-10','2024-08-11'),('Permiso por Duelo o Luto',42178625,'2024-07-20','2024-07-21'),('Permiso por Enfermedad o Salud',42178623,'2024-06-10','2024-06-11'),('Vacaciones',42178619,'2024-06-01','2024-06-10'),('Vacaciones',42178622,'2024-07-01','2024-07-15'),('Vacaciones',42178624,'2024-08-01','2024-08-15'),('Vacaciones',42178626,'2024-09-01','2024-09-15');
/*!40000 ALTER TABLE `solicitudes` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-21 22:15:16
