-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-10-2025 a las 10:19:18
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
-- Base de datos: `secilica_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancelaciones`
--

CREATE TABLE `cancelaciones` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `motivo` text NOT NULL,
  `cancelado_por` varchar(60) DEFAULT NULL,
  `fecha_cancelacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cancelaciones`
--

INSERT INTO `cancelaciones` (`id`, `cita_id`, `motivo`, `cancelado_por`, `fecha_cancelacion`) VALUES
(26, 42, 'personales', 'dider romero', '2025-10-07 20:11:40'),
(27, 41, 'prueba', 'dider romero', '2025-10-07 20:15:12'),
(28, 46, 'Cliente no puede asistir', 'empleado', '2025-10-17 03:59:08'),
(29, 47, 'Cancelación por parte del cliente', 'empleado', '2025-10-18 00:22:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `tipo_documento` varchar(30) NOT NULL,
  `numero_documento` varchar(30) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `placa_vehiculo` varchar(20) NOT NULL,
  `servicio` varchar(100) NOT NULL,
  `descripcion_adicional` text DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'pendiente',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `nombre`, `apellido`, `tipo_documento`, `numero_documento`, `correo`, `telefono`, `fecha`, `placa_vehiculo`, `servicio`, `descripcion_adicional`, `estado`, `created_at`) VALUES
(41, 'dider', 'romero', 'CC', '1023676453', 'didier@romero.com', '3227655432', '2025-10-08', 'KKK21A', 'Scanner OBD1/OBD2', 'preuba formualrio citas usuario....', 'cancelada', '2025-10-07 19:52:38'),
(42, 'dider', 'romero', 'CC', '1023676453', 'didier@romero.com', '3227655432', '2025-10-08', 'JJJ21A', 'Otros', 'cita modulo empleado seccion citas usuarios', 'cancelada', '2025-10-07 19:55:07'),
(43, 'dider', 'romero', 'CC', '1023676453', 'didier@romero.com', '3227655432', '2025-10-08', 'SST09B', 'Reparación Motor', 'CITA CLIENTE', 'pendiente', '2025-10-07 20:22:33'),
(44, 'dider', 'romero', 'CC', '1023676453', 'didier@romero.com', '3227655432', '2025-10-08', 'SSS21A', 'Sincronización', 'preuba cita empleado', 'pendiente', '2025-10-07 20:31:45'),
(45, 'Admin', 'SECLICA', 'CC', '1234567890', 'admin@secllica.com', '3000000000', '2025-10-14', 'JJJ222', 'Sincronización', 'Agendada por empleado', 'pendiente', '2025-10-13 22:31:08'),
(46, 'Juan', 'Pérez', 'CC', '1122334455', 'juanperez@email.com', '301234567', '2025-10-25', 'JPZ001', '2', 'Ajuste y engrase general', 'cancelada', '2025-10-17 03:55:29'),
(47, 'Carlos', 'Pérez', 'CC', '1122331100', 'carlosprueba@email1.com', '3211234567', '2025-10-20', 'XYZ12C', 'Mantenimiento preventivo', 'prueba postman', 'cancelada', '2025-10-18 00:14:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `iva` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `cita_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `cliente_id`, `descripcion`, `subtotal`, `iva`, `total`, `estado`, `cita_id`) VALUES
(89, 49, 'mano de obra', 150000.00, 28500.00, 178500.00, 'pagada', 42),
(90, 49, 'mano de obra', 125000.00, 23750.00, 148750.00, 'pagada', 43),
(91, 49, 'mano de abra', 125000.00, 23750.00, 148750.00, 'pagada', 44),
(92, 37, 'Sincronización general del sistema', 150000.00, 28500.00, 178500.00, 'pagada', 45);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `correo`, `telefono`, `mensaje`, `fecha_envio`) VALUES
(1, 'jhonn romero', 'jhonnhtml@contacto.com', '3005768790', 'felitaciones este proyecto esta tomando forma...', '2025-09-18 03:00:05'),
(2, 'jhonn romero', 'jhonn@romero.com', '3008765432', 'reclamacion por garantia de trabajo realizado en dias pasados se presenta queja por malos procedimiento', '2025-09-24 16:13:19'),
(3, 'clinete mensaje prueba', 'clienteprueba@mensaje.com', '30007654311', 'mesaje prueba sistema seclica recepcion en bandeja mensajes de bd seclica y modulos admin y empleado.', '2025-09-25 19:19:46'),
(4, 'prueba html ccontacto', 'prueba@htmlccontacto.com', '3001122222222', 'prueba de mansaje formulariohtml', '2025-09-30 03:55:53'),
(5, 'fgdfgdfg', 'dfdfdf@jfhfjdh.com', '84387474359845', 'kdjdchdbbbvbcvbhchvbcbv', '2025-10-07 13:29:48'),
(6, 'Juan Perez', 'juanperez@email.com', '3001234567', '¡Buen servicio, felicitaciones![mensaje desde postman]', '2025-10-18 00:34:02'),
(7, 'Juan Perez', 'juanperez@email.com', '3001234567', '¡Buen servicio, felicitaciones![mensaje desde wen htm]', '2025-10-18 00:36:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_contacto`
--

CREATE TABLE `respuestas_contacto` (
  `id` int(11) NOT NULL,
  `mensaje_id` int(11) NOT NULL,
  `respuesta` text NOT NULL,
  `fecha_respuesta` datetime NOT NULL DEFAULT current_timestamp(),
  `respondido_por` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_contacto`
--

INSERT INTO `respuestas_contacto` (`id`, `mensaje_id`, `respuesta`, `fecha_respuesta`, `respondido_por`) VALUES
(1, 2, 'lo sentimos en este moneto no podemos responder esa solicitud graciias por comunicarte con nosotro hasta pronto¡¡', '2025-09-24 17:38:27', 'Empleado'),
(2, 1, 'gracias por tu mensaje, estamos trabajndo para seguir mejorando para sarte una experiencia satifactoria gracias¡', '2025-09-24 17:44:34', 'Empleado'),
(3, 3, 'mesaje prueba sistema seclica recepcion en bandeja mensajes de bd seclica y modulo empleado.respuesta', '2025-10-02 22:14:04', 'Empleado'),
(4, 5, 'Muchas gracias por contactarnos, su solicitud fue atendida.', '2025-10-11 04:37:06', 'Jhonn Edison'),
(5, 4, 'Gracias por contactarnos, responderemos pronto.', '2025-10-17 04:36:34', 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` enum('CC','CE','Pasaporte') NOT NULL,
  `identificacion` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `rol` enum('cliente','empleado','admin') NOT NULL DEFAULT 'cliente',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `tipo_documento`, `identificacion`, `email`, `telefono`, `password_hash`, `rol`, `estado`, `created_at`, `updated_at`) VALUES
(6, 'jhonn actualizado', 'romero admin', 'CC', '123456678900', 'jhonn@romero.com', '30098765534', '$2y$10$ec5rgeZOx71beexv19J8Yerk/4NJpjdax3nM6raR1g5iHm9AtIBbS', 'cliente', 'activo', '2025-09-01 19:29:49', '2025-10-18 05:04:44'),
(14, 'cliente', 'cliente', 'CC', '123456789', 'cliente@clientehtml.com', '30001234567', '$2y$10$wR485Mwd7Gp7eIQWuZdaiOaYh/K1AEanLRVY0mBS.Y.Tso8lXvoci', 'cliente', 'activo', '2025-09-10 18:47:02', '2025-10-18 05:04:50'),
(16, 'edison', 'peña', 'CC', '101499207', 'edisoncliente@html.com', '3004833087', '$2y$10$MiGIXT/cQUl2ZXx.ZVsRpOW2zE4A00o5EGvUFrphREsIiR1V.5Aru', 'cliente', 'activo', '2025-09-18 06:54:50', '2025-10-18 05:05:01'),
(24, 'Admin', 'SECLICA', 'CC', '222333444', 'adminhtml@secilica.com', '3000000000', '$2y$10$3F1HQ/0QaImIsY2.QA9dLuycO8i2PtBJ5mDWwT8Mbw2c9vK6A3J/i', 'admin', 'activo', '2025-09-19 03:58:59', NULL),
(27, 'Empleado', 'Prueba', 'CC', '1020546890', 'empleado@seclila.com', '3001234567', '$2y$10$xIPvZWLH.HUinWdrsDVbi.vQ6iODA57axZIIsfzVWYZrrKCkO0Im6', 'empleado', 'activo', '2025-09-20 02:59:16', '2025-10-17 09:58:11'),
(31, 'yarledys', 'paternina', 'CC', '1000111444777', 'yarledys@gmail.com', '3013458709', '$2y$10$/3g/tDujXPT5kTssnbwapupw45KopWP1Atl8duSGs4i6/Y34g6qlu', 'empleado', 'activo', '2025-09-25 23:22:55', NULL),
(34, 'agendar cita2', 'empleado2', 'CC', '12003300888', 'agendarcita2@empleado2.com', '3210003217', '$2y$10$t7lKbnDdBnzdKl8MiwAx9exs5Hi4NJsnavshjoeRP/jQNd24kmuZa', 'cliente', 'activo', '2025-09-30 08:17:17', '2025-10-18 05:05:06'),
(37, 'Admin', 'SECLICA', 'CC', '1234567890', 'admin@secllica.com', '3000000000', '$2y$10$gQNV8lo8FM0qgJyrl4D9X.t9qxZHIBnD8P03PyOMqGOsB5kp3T1jK', 'admin', 'activo', '2025-09-30 09:12:28', '2025-10-03 01:50:43'),
(39, 'Admin', 'SECLICA', 'CC', '9999999888', 'admin3@secllica.com', '3000000022', '$2y$10$VpI7yGNeONga06SwKSe4HeZ2qrUXZ6KHOc/ekk5gV6XTWotS3dy2S', 'admin', 'activo', '2025-09-30 09:19:32', '2025-10-03 01:50:32'),
(42, 'Jhonn', 'Admin', 'CC', '1234567111', 'jhonn@gmail.com', '3001234567', '$2y$10$9/fgHODuplXalJ0qqmzZ0ewbaSE0UPTWEqOhGWeSiQmIYuxu9GmB2', 'admin', 'activo', '2025-10-01 19:18:30', '2025-10-03 01:50:56'),
(45, 'Jhonn', 'Admin', 'CC', '12345110045', 'jhonnromero@gmail.com', '3509897654', '$2y$10$UXbn83SqzTHDMUw4R99xOOyFQbg.abva8w.SH2vvxjgvlZyw79PtK', 'admin', 'activo', '2025-10-03 01:43:41', NULL),
(48, 'cita4', 'modulo empleado', 'CC', '1000444333000', 'cita4@moduloempleado.com', '30090080091', '$2y$10$RsYU.kaulHdPYjbhYsMayOIIG9fusuqKSNI0isFvrgj.YDcT1cVsS', 'cliente', 'activo', '2025-10-03 03:16:12', '2025-10-18 05:05:11'),
(49, 'dider', 'romero', 'CC', '1023676453', 'didier@romero.com', '3227655432', '$2y$10$IDT2wWi2WE49CaK0nRIhU.l4tKFwtHGNXFO.y9faafdtfYUrCnq2i', 'cliente', 'activo', '2025-10-03 03:53:36', '2025-10-18 05:05:18'),
(53, 'Juan', 'Pérez', 'CC', '1122334455', 'juanperez@email.com', '3001234567', '$2y$10$cIG5P9pCfAgreOViRLeSuuyZ27b4FmpngbfV5rXMLhcPNOWZOp.xu', 'cliente', 'activo', '2025-10-11 10:15:26', '2025-10-18 05:05:23'),
(54, 'empleado@seclica.com', 'empleado@seclica.com', 'CC', '234167890', 'empleado@seclica.com', '32100982345', '$2y$10$Gj6OpoDWWVss.IYQPO0sWuLWgL09KkRmFwUP7w9YXv3IhNFDztDdS', 'empleado', 'activo', '2025-10-17 09:59:30', NULL),
(56, 'Carlos', 'Pérez', 'CC', '1122331100', 'carlosprueba@email.com', '3211234567', '$2y$10$4DHtbSbULV.DBVFlWGfKN.YRVmkI4DgWn/Xt22addJ0vh9JaLm84a', 'cliente', 'activo', '2025-10-18 04:48:47', '2025-10-18 05:05:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancelaciones`
--
ALTER TABLE `cancelaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cita_id` (`cita_id`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `respuestas_contacto`
--
ALTER TABLE `respuestas_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mensaje_id` (`mensaje_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_doc` (`tipo_documento`,`identificacion`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cancelaciones`
--
ALTER TABLE `cancelaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `respuestas_contacto`
--
ALTER TABLE `respuestas_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cancelaciones`
--
ALTER TABLE `cancelaciones`
  ADD CONSTRAINT `cancelaciones_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `respuestas_contacto`
--
ALTER TABLE `respuestas_contacto`
  ADD CONSTRAINT `respuestas_contacto_ibfk_1` FOREIGN KEY (`mensaje_id`) REFERENCES `mensajes_contacto` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
