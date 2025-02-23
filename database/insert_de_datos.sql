-- TODO: Usar sólo si no se creó el super_usuario (Lo crea el script de la base)
INSERT INTO super_usuario (username, password)
VALUES ('admin', 'admin');

-- TODO: Usar desde acá para crear datos dummy en la base

--
-- Volcado de datos para la tabla `empresas`
--
INSERT INTO empresas (Nombre, Logo, Fondo, ArchivoInicio1, ArchivoInicio2, UsuarioPrincipal)
VALUES ('FlowManager', '../../uploads/FlowManager/images/logo.png', '../../uploads/FlowManager/images/fondo.jpg', '../../uploads/FlowManager/files/archivoinicio1.pdf', '../../uploads/FlowManager/files/archivoinicio2.pdf', NULL);

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO departamentos (Nombre, Empresa, DirectorACargo)
VALUES ('flowmanager_Recursos Humanos', 'FlowManager', NULL),
       ('flowmanager_Sin asignar', 'FlowManager', NULL),
       ('flowmanager_Desarrollo', 'FlowManager', NULL),
       ('flowmanager_Marketing', 'FlowManager', NULL),
       ('flowmanager_Ventas', 'FlowManager', NULL),
       ('flowmanager_Soporte Técnico', 'FlowManager', NULL);

--
-- Volcado de datos para la tabla `usuarios`
-- TODO: Password flowmanager123
--

INSERT INTO usuarios (Dni, NombreApellido, FechaNacimiento, Domicilio, CorreoElectronico, Telefono, TipoDeUsuario, Departamento, Clave)
VALUES (41754883, 'Carlos Fernández', '1998-05-12', 'Av. Libertador 123, Buenos Aires', 'carlos@FlowManager.com', '1122334455', 'Directivo', 'Recursos Humanos', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41328471, 'María Gómez', '1997-08-22', 'Calle 25 de Mayo 456, Buenos Aires', 'maria@FlowManager.com', '1122446688', 'RRHH', 'Recursos Humanos', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41013852, 'Javier Pérez', '1995-02-15', 'San Martín 789, Córdoba', 'javier@FlowManager.com', '1133557799', 'Directivo', 'Desarrollo', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41019921, 'Lucía Rodríguez', '1998-09-05', 'Mitre 321, Córdoba', 'lucia@FlowManager.com', '1133445566', 'Empleado', 'Desarrollo', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41029986, 'Federico López', '1996-12-01', 'Belgrano 654, Rosario', 'federico@FlowManager.com', '1144778899', 'Directivo', 'Marketing', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41025601, 'Camila Díaz', '1999-06-10', 'Rivadavia 987, Rosario', 'camila@FlowManager.com', '1144889977', 'Empleado', 'Marketing', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41038873, 'Alejandro Sosa', '1994-07-20', 'Sarmiento 159, Mendoza', 'alejandro@FlowManager.com', '1155667788', 'Directivo', 'Ventas', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41033201, 'Valentina Méndez', '2000-03-18', 'Colon 753, Mendoza', 'valentina@FlowManager.com', '1155778899', 'Empleado', 'Ventas', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41040821, 'Martín Torres', '1993-11-11', 'Alem 842, La Plata', 'martin@FlowManager.com', '1166778899', 'Directivo', 'Soporte Técnico', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO'),
       (41140501, 'Sofía Ramírez', '2001-04-25', 'Moreno 369, La Plata', 'sofia@FlowManager.com', '1166889977', 'Empleado', 'Soporte Técnico', '$2y$10$s510S6RjaAvufr5k/fPCy.X5rc4.5FbvFpI7Puc0R35MZmwGJXOLO');

--
-- Volcado de datos para la tabla `solicitudes`
--
INSERT INTO solicitudes (TipoSolicitud, DniSolicitante, FechaHoraDesde, FechaHoraHasta, Estado, id_licencia)
VALUES ('maternidad', 20406012, '2025-01-22', '2025-01-24', 'Rechazada', 1),
       ('vacaciones', 20406012, '2025-01-16', '2025-01-17', 'Pendiente', 2);