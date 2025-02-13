--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`Nombre`, `DirectorACargo`) VALUES
('MARIO', 33145232);

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`TipoSolicitud`, `DniSolicitante`, `FechaHoraDesde`, `FechaHoraHasta`, `Estado`, `id_licencia`) VALUES
('maternidad', 20406012, '2025-01-22', '2025-01-24', 'Rechazada', 1),
('vacaciones', 20406012, '2025-01-16', '2025-01-17', 'Pendiente', 2);


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


INSERT INTO `super_usuario` (`username`, `password`) VALUES
('admin', 'admin');
