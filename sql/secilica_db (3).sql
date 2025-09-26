-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2025 a las 23:47:58
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
  `fecha_cancelacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cancelaciones`
--

INSERT INTO `cancelaciones` (`id`, `cita_id`, `motivo`, `fecha_cancelacion`) VALUES
(1, 1, 'siniestro del vehiculo', '2025-09-18 02:28:52'),
(2, 2, 'siniestro vehiculo perdida total', '2025-09-19 23:31:04'),
(3, 4, 'cancelacion cliente', '2025-09-20 15:33:55'),
(4, 3, 'sinistro vehiculo', '2025-09-20 15:35:25'),
(5, 5, 'cancela cliente', '2025-09-22 21:35:39');

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
(1, 'jhonn', 'romero', 'CC', '1026553655', 'jhon@romero.com', '3008765432', '2025-09-19', 'KXM42D', 'Sistema Electrónico y eléctrico', '', 'cancelada', '2025-09-18 02:19:38'),
(2, 'jhonn', 'romero', 'CC', '1206553655', 'jhonromero@gmail.com', '3004834321', '2025-09-15', 'KXM43A', 'Sincronización', '', 'cancelada', '2025-09-19 23:27:58'),
(3, 'juan', 'peña', 'CC', '101499207', 'juan@gmail.com', '3009876543', '2025-09-19', 'LSD56T', 'Sistema Lubricación', '', 'cancelada', '2025-09-19 23:51:23'),
(4, 'pedro', 'jaimes', 'CC', '12345678900', 'pedrojaimes@gmail.com', '3214567800', '2025-09-18', 'SLO32S', 'Reparación Motor', '', 'cancelada', '2025-09-20 00:04:26'),
(5, 'citaid', 'emphtml', 'CC', '10002345678', 'citaid@emphtml.com', '7031809', '2025-09-20', 'SND84B', 'Sistema Eléctrico', '', 'cancelada', '2025-09-20 12:33:32'),
(6, 'jhonn', 'romero', 'CC', '1026553655', 'jhonnromero@gmail.com', '3507348436', '2025-09-23', 'MSR67W', 'Sincronización', '', 'pendiente', '2025-09-22 21:22:07'),
(7, 'cliente formulario', 'cliente html', 'CC', '123456789', 'cliente@clientehtml.com', '30001234567', '2025-09-25', 'NSA84D', 'Mecánica Rápida', 'cita cliente', 'pendiente', '2025-09-24 13:06:10');

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
(4, 14, 'Mantenimiento general', 80000.00, 15200.00, 95200.00, 'pagada', NULL),
(5, 16, 'Sincronización y revisión', 40000.00, 7600.00, 47600.00, 'pendiente', NULL),
(8, 6, 'Reparación general', 80000.00, 15200.00, 95200.00, 'pagada', NULL),
(10, 6, 'Servicio General', 80000.00, 15200.00, 95200.00, 'pagada', NULL),
(11, 5, 'mano de abora y insumos', 1200000.00, 228000.00, 1428000.00, 'pagada', 5),
(15, 5, 'mano de obra', 250000.00, 47500.00, 297500.00, 'pagada', 5),
(16, 6, 'Sincronización', 80000.00, 15200.00, 95200.00, 'pendiente', 6),
(17, 6, 'mano de obra, sincronizacion', 135000.00, 25650.00, 160650.00, 'pagada', 6);

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
(2, 'jhonn romero', 'jhonn@romero.com', '3008765432', 'reclamacion por garantia de trabajo realizado en dias pasados se presenta queja por malos procedimiento', '2025-09-24 16:13:19');

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
(2, 1, 'gracias por tu mensaje, estamos trabajndo para seguir mejorando para sarte una experiencia satifactoria gracias¡', '2025-09-24 17:44:34', 'Empleado');

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
(5, 'jhonn jairo', 'romero gonzalez', 'CC', '1026553000', 'jhonactualizado@romero.com', '3008765432', '$2y$10$dSSnCtletxoQ0tIPL/DCdul3gj5u8KmJC4wqXjvHP1BoC561OD81a', 'cliente', 'activo', '2025-09-01 19:14:59', '2025-09-23 03:58:01'),
(6, 'jhonn actualizado', 'romero admin', 'CC', '123456678900', 'jhonn@romero.com', '30098765534', '$2y$10$BN8mOv1D9zoZZOBCmuyD7.ZsMFHlwvNFyuESacCq9OEMzKUuiS7Ua', 'cliente', 'activo', '2025-09-01 19:29:49', '2025-09-23 04:14:46'),
(14, 'cliente', 'cliente', 'CC', '123456789', 'cliente@clientehtml.com', '30001234567', '$2y$10$46y5uM9Q2QDOpvpsOtV9tu..YXirPO9CU2mHK6vYcXJzJBQITdkUS', 'cliente', 'activo', '2025-09-10 18:47:02', NULL),
(16, 'edison', 'peña', 'CC', '101499207', 'edisoncliente@html.com', '3004833087', '$2y$10$UECWHPbX38CDn/EJeS7Ea.rhVzJ9HTBLPiV1uXbqyo1puqCKODdJa', 'cliente', 'activo', '2025-09-18 06:54:50', NULL),
(24, 'Admin', 'SECLICA', 'CC', '222333444', 'adminhtml@secilica.com', '3000000000', '$2y$10$3F1HQ/0QaImIsY2.QA9dLuycO8i2PtBJ5mDWwT8Mbw2c9vK6A3J/i', 'admin', 'activo', '2025-09-19 03:58:59', NULL),
(26, 'Adminhtml1', 'SECLICA', 'CC', '1234', 'adminhtml1@secilica.com', '3000000000', '$2y$10$6dqHSb9HL9mSd8NHB8gb5.kg8Od2Iw/DK.DX/15jDcQd29VZbWblW', 'admin', 'activo', '2025-09-19 04:37:48', '2025-09-23 04:06:38'),
(27, 'Empleado', 'Prueba', 'CC', '1020546890', 'empleado@seclila.com', '3001234567', '$2y$10$dlWq1iJ/8J8piKz8SG30EOlg3xH6ylPNtKJnePmOFrrv3FvLaTmG.', 'empleado', 'activo', '2025-09-20 02:59:16', '2025-09-24 17:59:55');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `respuestas_contacto`
--
ALTER TABLE `respuestas_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
