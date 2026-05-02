-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 01-05-2026 a las 17:47:16
-- Versión del servidor: 8.0.45-cll-lve
-- Versión de PHP: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mascapa2_agendapro_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `accion` varchar(100) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `registro_id` int UNSIGNED DEFAULT NULL,
  `detalles` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `accion`, `tabla_afectada`, `registro_id`, `detalles`, `ip_address`, `user_agent`, `created_at`) VALUES
(2, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 21:16:40'),
(3, 10, 'create_otec', 'otec', 1, 'OTEC creada: Kibernum Capacitación S.A·', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 21:21:12'),
(4, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 23:26:49'),
(5, 10, 'create_course', 'courses', 1, 'Curso creado: Analisis de Datos con Power BI Intermedio', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 23:27:32'),
(6, 10, 'create_course', 'courses', 2, 'Curso creado: Analisis de Datos con Power BI - Intermedio', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 23:28:08'),
(7, 10, 'create_course', 'courses', 3, 'Curso creado: Microsoft Excel Nivel Básico', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 23:29:02'),
(8, 10, 'create_course', 'courses', 4, 'Curso creado: Microsoft Excel - Nivel Básico', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 23:29:22'),
(9, 10, 'create_user', 'users', 11, 'Usuario creado: Monica Ojeda Villamizar ', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 00:49:48'),
(10, 10, 'create_user', 'users', 12, 'Usuario creado: Evelin Rivera', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 00:56:39'),
(11, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 00:58:22'),
(12, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 00:58:31'),
(13, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 01:00:04'),
(14, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 01:11:08'),
(15, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 01:11:33'),
(16, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 01:28:19'),
(17, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 01:52:44'),
(18, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:09:27'),
(19, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:13:23'),
(22, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:24:23'),
(23, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:24:46'),
(24, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:25:47'),
(25, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:26:33'),
(26, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:26:43'),
(27, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:29:49'),
(28, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:31:49'),
(29, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:32:24'),
(30, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 11:32:26'),
(31, 10, 'login', 'users', 10, 'Usuario inició sesión', '186.41.28.51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-04-03 14:53:19'),
(32, 10, 'login', 'users', 10, 'Usuario inició sesión', '191.127.199.240', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 15:53:45'),
(33, 10, 'login', 'users', 10, 'Usuario inició sesión', '191.127.199.240', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-03 15:57:29'),
(34, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 02:22:41'),
(35, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 02:28:34'),
(36, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 02:34:59'),
(37, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 02:35:03'),
(38, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 02:35:17'),
(39, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 02:56:19'),
(40, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 02:59:47'),
(41, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 03:00:00'),
(42, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 03:00:03'),
(43, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 12:52:00'),
(44, 10, 'password_change', 'users', 10, 'Contraseña cambiada', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 12:56:34'),
(45, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 12:56:47'),
(46, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 12:56:54'),
(47, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:00:18'),
(48, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:00:31'),
(49, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:04:59'),
(50, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:06:57'),
(51, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:07:06'),
(52, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:07:19'),
(53, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:18:38'),
(54, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:18:41'),
(55, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:18:47'),
(56, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 13:18:56'),
(57, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:02:52'),
(58, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:08:17'),
(59, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:08:18'),
(60, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:17:22'),
(61, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:25:45'),
(62, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:31:43'),
(63, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:31:54'),
(64, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:40:01'),
(65, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:40:28'),
(66, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 14:40:57'),
(67, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 16:11:14'),
(68, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 16:13:29'),
(69, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 16:15:41'),
(70, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 16:19:15'),
(71, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 16:19:17'),
(72, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:17:09'),
(73, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:17:35'),
(74, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:18:28'),
(75, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:20:40'),
(76, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:21:16'),
(77, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:23:09'),
(78, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:23:29'),
(79, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:25:10'),
(80, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:25:16'),
(81, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:25:49'),
(82, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:25:54'),
(83, 10, 'update_course', 'courses', 3, 'Curso actualizado: Microsoft Excel Nivel Básico', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:31:32'),
(84, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:31:57'),
(85, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:31:59'),
(86, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:34:12'),
(87, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 19:12:55'),
(88, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 19:13:16'),
(89, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 21:08:17'),
(90, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 21:09:08'),
(91, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 21:09:14'),
(92, 12, 'create_booking', 'bookings', 1, 'Reserva creada', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 21:13:38'),
(93, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 21:13:52'),
(94, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 21:13:57'),
(95, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:38:51'),
(96, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:47:54'),
(97, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:48:00'),
(98, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:48:06'),
(99, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:48:31'),
(100, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:55:15'),
(101, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:55:33'),
(102, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:57:07'),
(103, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:57:13'),
(104, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 00:57:40'),
(105, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 01:03:23'),
(106, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 01:17:19'),
(107, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 01:17:24'),
(108, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 01:17:39'),
(109, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 01:17:44'),
(110, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 01:35:24'),
(111, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 01:35:31'),
(112, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 14:29:16'),
(113, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 14:36:34'),
(114, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-05 14:44:13'),
(115, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 17:29:56'),
(116, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 17:42:26'),
(117, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 17:42:29'),
(118, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 17:42:41'),
(119, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 19:09:38'),
(120, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 19:09:59'),
(121, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 19:23:38'),
(122, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 19:23:40'),
(123, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 19:24:12'),
(124, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 19:24:51'),
(125, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 20:05:44'),
(126, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 20:05:49'),
(127, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 20:13:32'),
(128, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 20:17:12'),
(129, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 20:17:18'),
(130, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 20:24:11'),
(131, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 20:25:58'),
(132, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 22:41:48'),
(133, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 23:23:21'),
(134, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 23:23:46'),
(135, 14, 'create_otec', 'otec', 2, 'OTEC creada: Capacita', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 23:31:20'),
(136, 14, 'create_user', 'users', 16, 'Usuario creado: Juanita Gonzalez', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 23:33:44'),
(137, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:24:36'),
(138, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:24:43'),
(139, 16, 'logout', 'users', 16, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:24:55'),
(140, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:25:01'),
(141, 14, 'create_course', 'courses', 5, 'Curso creado: Este es un curso de Prueba', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:28:06'),
(142, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:28:14'),
(143, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:28:19'),
(144, 16, 'logout', 'users', 16, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:30:28'),
(145, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:30:33'),
(146, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:30:41'),
(147, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:30:46'),
(148, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:33:33'),
(149, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:33:37'),
(150, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:34:13'),
(151, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 00:34:18'),
(152, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 11:42:34'),
(153, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 11:43:24'),
(154, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 11:43:30'),
(155, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 11:44:04'),
(156, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 11:44:21'),
(157, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 15:43:55'),
(158, 16, 'logout', 'users', 16, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 16:59:07'),
(159, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 16:59:14'),
(160, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 16:59:22'),
(161, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 16:59:53'),
(162, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:01:24'),
(163, 15, 'login', 'users', 15, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:03:33'),
(164, 15, 'create_otec', 'otec', 3, 'OTEC creada: PlayComp', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:05:43'),
(165, 15, 'logout', 'users', 15, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:40:26'),
(166, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:40:30'),
(167, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:40:43'),
(168, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:40:49'),
(169, 16, 'logout', 'users', 16, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:41:00'),
(170, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:41:06'),
(171, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:41:10'),
(172, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:41:19'),
(173, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:53:40'),
(174, 15, 'login', 'users', 15, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:53:57'),
(175, 15, 'logout', 'users', 15, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 17:54:24'),
(176, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 18:46:53'),
(177, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 18:47:25'),
(178, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 18:47:31'),
(179, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 20:53:47'),
(180, 16, 'logout', 'users', 16, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 20:57:51'),
(181, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 20:57:59'),
(182, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 21:03:18'),
(183, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 21:03:24'),
(184, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 21:10:08'),
(185, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 21:10:15'),
(186, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-06 21:38:28'),
(187, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 00:06:51'),
(188, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 02:21:37'),
(189, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 02:23:05'),
(190, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 02:23:45'),
(191, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 02:43:41'),
(192, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 02:43:47'),
(193, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 14:52:22'),
(194, 10, 'update_booking_status', 'bookings', 1, 'Estado cambiado a: aprobada', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 15:10:42'),
(195, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 23:49:48'),
(196, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 23:53:13'),
(197, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-07 23:56:31'),
(198, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 03:23:15'),
(199, 10, 'update_booking_status', 'bookings', 2, 'Estado cambiado a: aprobada', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 03:29:52'),
(200, 10, 'update_booking_status', 'bookings', 2, 'Estado cambiado a: aprobada', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 03:32:32'),
(201, 10, 'update_booking_status', 'bookings', 2, 'Estado cambiado a: aprobada', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 03:39:26'),
(202, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 03:59:20'),
(203, 10, 'update_booking_status', 'bookings', 2, 'Estado cambiado a: aprobada', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 04:04:59'),
(204, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 13:18:49'),
(205, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 13:33:12'),
(206, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 13:34:52'),
(207, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 13:35:08'),
(208, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 13:35:16'),
(209, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 13:35:49'),
(210, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 13:58:53'),
(211, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 14:03:47'),
(212, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 14:34:54'),
(213, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 14:34:56'),
(214, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 14:35:01'),
(215, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 14:35:10'),
(216, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 14:35:13'),
(217, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 14:35:18'),
(218, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 15:12:01'),
(219, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 15:41:09'),
(220, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 15:43:58'),
(221, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 15:48:29'),
(222, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 15:48:30'),
(223, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:46:33'),
(224, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:48:19'),
(225, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:48:31'),
(226, 12, 'create_booking', 'bookings', 3, 'Reserva creada (pendiente de aprobación)', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:50:48'),
(227, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:51:02'),
(228, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:51:08'),
(229, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:52:45'),
(230, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:52:50'),
(231, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 16:56:22'),
(232, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:20:50'),
(233, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:30:17'),
(234, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:44:20'),
(235, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:44:30'),
(236, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:45:18');
INSERT INTO `activity_logs` (`id`, `user_id`, `accion`, `tabla_afectada`, `registro_id`, `detalles`, `ip_address`, `user_agent`, `created_at`) VALUES
(237, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:45:25'),
(238, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:49:34'),
(239, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 19:50:52'),
(240, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 21:00:02'),
(241, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 21:00:04'),
(242, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-09 16:35:17'),
(243, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-09 16:57:26'),
(244, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-09 17:01:44'),
(245, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-09 17:02:04'),
(246, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-09 17:02:10'),
(247, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-09 17:02:24'),
(248, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-10 15:47:51'),
(249, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-11 05:11:04'),
(250, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:34:47'),
(251, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:35:00'),
(252, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:37:33'),
(253, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-14 01:50:04'),
(254, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 01:05:26'),
(255, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 01:31:00'),
(256, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 01:31:09'),
(257, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 01:31:20'),
(258, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 01:32:14'),
(259, 16, 'login', 'users', 16, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 12:36:11'),
(260, 16, 'logout', 'users', 16, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 12:38:32'),
(261, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 12:39:55'),
(262, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 12:40:35'),
(263, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 12:41:16'),
(264, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 12:50:00'),
(265, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 13:40:18'),
(266, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 13:45:18'),
(267, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 13:49:27'),
(268, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 13:57:38'),
(269, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 13:57:48'),
(270, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 13:57:53'),
(271, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 13:58:07'),
(272, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:11:33'),
(273, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:11:39'),
(274, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:18:16'),
(275, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:18:21'),
(276, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:19:00'),
(277, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:19:05'),
(278, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:20:59'),
(279, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:22:08'),
(280, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:22:13'),
(281, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:26:20'),
(282, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:29:17'),
(283, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:30:25'),
(284, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:31:51'),
(285, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:32:20'),
(286, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:32:23'),
(287, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:33:40'),
(288, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:33:50'),
(289, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:33:55'),
(290, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:34:35'),
(291, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:34:39'),
(292, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:34:44'),
(293, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:34:49'),
(294, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:34:53'),
(295, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:35:50'),
(296, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:37:36'),
(297, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:37:40'),
(298, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:37:46'),
(299, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:37:54'),
(300, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:37:59'),
(301, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:38:07'),
(302, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:38:13'),
(303, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:38:39'),
(304, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:39:16'),
(305, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:39:31'),
(306, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:41:08'),
(307, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:41:32'),
(308, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:41:48'),
(309, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:42:03'),
(310, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:44:26'),
(311, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:45:28'),
(312, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:50:27'),
(313, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:51:13'),
(314, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:55:06'),
(315, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 14:55:12'),
(316, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:04:06'),
(317, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:04:15'),
(318, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:04:19'),
(319, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:04:23'),
(320, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:04:49'),
(321, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:08:25'),
(322, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:08:28'),
(323, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:08:33'),
(324, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:08:37'),
(325, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:08:53'),
(326, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:09:47'),
(327, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:14:23'),
(328, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:17:39'),
(329, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 16:40:28'),
(330, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 17:47:19'),
(331, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 17:56:38'),
(332, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 17:56:40'),
(333, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 18:02:58'),
(334, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 18:03:01'),
(335, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 18:08:10'),
(336, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 18:08:15'),
(337, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 18:08:24'),
(338, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 18:08:30'),
(339, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 22:36:01'),
(340, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 22:36:16'),
(341, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 22:36:22'),
(342, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 23:48:51'),
(343, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 23:49:06'),
(344, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-15 23:49:11'),
(345, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:15:04'),
(346, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:15:09'),
(347, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:17:56'),
(348, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:18:04'),
(349, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:18:13'),
(350, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 00:18:22'),
(351, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:23:42'),
(352, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:23:45'),
(353, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:24:17'),
(354, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:24:25'),
(355, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:24:32'),
(356, 10, 'login', 'users', 10, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:24:37'),
(357, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:25:04'),
(358, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:25:10'),
(359, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:28:19'),
(360, 12, 'login', 'users', 12, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:30:07'),
(361, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:31:59'),
(362, 14, 'login', 'users', 14, 'Usuario inició sesión', '200.104.233.32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-16 01:32:09'),
(363, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 16:31:37'),
(364, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 16:58:13'),
(365, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 16:58:18'),
(366, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 17:44:22'),
(367, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 17:44:31'),
(368, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 17:44:45'),
(369, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 17:44:50'),
(370, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:43:07'),
(371, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:43:26'),
(372, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:43:31'),
(373, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:43:46'),
(374, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:43:51'),
(375, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:49:33'),
(376, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:49:36'),
(377, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:49:46'),
(378, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 19:49:51'),
(379, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:10:50'),
(380, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:10:54'),
(381, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:12:41'),
(382, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:12:46'),
(383, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:16:21'),
(384, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:16:27'),
(385, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:37:51'),
(386, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:37:55'),
(387, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:38:11'),
(388, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-17 20:38:16'),
(389, 10, 'login', 'users', 10, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:09:41'),
(390, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:10:54'),
(391, 14, 'login', 'users', 14, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:10:57'),
(392, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:11:15'),
(393, 12, 'login', 'users', 12, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:11:21'),
(394, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:11:40'),
(395, 12, 'login', 'users', 12, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:14:12'),
(396, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:15:22'),
(397, 12, 'login', 'users', 12, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:27:04'),
(398, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:27:28'),
(399, 14, 'login', 'users', 14, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 01:27:33'),
(400, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 02:01:09'),
(401, 10, 'login', 'users', 10, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 02:01:13'),
(402, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 02:02:46'),
(403, 12, 'login', 'users', 12, 'Usuario inició sesión', '181.43.209.229', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 02:02:51'),
(404, 10, 'login', 'users', 10, 'Usuario inició sesión', '186.41.15.225', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-18 02:26:26'),
(405, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:34:28'),
(406, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:55:12'),
(407, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:55:16'),
(408, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:59:00'),
(409, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 12:59:04'),
(410, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:02:20'),
(411, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:02:23'),
(412, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:05:47'),
(413, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:21:42'),
(414, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:21:47'),
(415, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:22:08'),
(416, 15, 'login', 'users', 15, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:22:55'),
(417, 15, 'login', 'users', 15, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 13:29:59'),
(418, 15, 'logout', 'users', 15, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 14:16:21'),
(419, 15, 'login', 'users', 15, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 14:16:23'),
(420, 15, 'asociar_otec', 'otec_facilitadores', 1, 'Facilitador asociado a OTEC existente', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 14:17:55'),
(421, 15, 'login', 'users', 15, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 15:15:36'),
(422, 15, 'create_user', 'users', 17, 'Ejecutivo creado: Carolina Cabrera Godoy', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 15:18:34'),
(423, 15, 'logout', 'users', 15, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 15:19:01'),
(424, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 15:19:04'),
(425, 14, 'asociar_otec', 'otec_facilitadores', 3, 'Facilitador asociado a OTEC existente', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 15:20:25'),
(426, 10, 'login', 'users', 10, 'Usuario inició sesión', '186.40.118.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-18 18:04:01'),
(427, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:31:51'),
(428, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:32:02'),
(429, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:32:08'),
(430, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:32:21'),
(431, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:32:25'),
(432, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:32:39'),
(433, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:32:48'),
(434, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:36:23'),
(435, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 17:36:30'),
(436, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:51:42'),
(437, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:51:46'),
(438, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:51:49'),
(439, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:51:52'),
(440, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:52:15'),
(441, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:52:26'),
(442, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:53:40'),
(443, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 18:53:44'),
(444, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:39:08'),
(445, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:39:26'),
(446, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:39:30'),
(447, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:46:21'),
(448, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:46:25'),
(449, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:46:50'),
(450, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:46:54'),
(451, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:47:24'),
(452, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:48:09'),
(453, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:56:49'),
(454, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 00:56:53'),
(455, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:10:17'),
(456, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:10:22'),
(457, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:23:21'),
(458, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:23:26'),
(459, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:23:44'),
(460, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:23:53'),
(461, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:27:15'),
(462, 12, 'login', 'users', 12, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:27:20'),
(463, 12, 'logout', 'users', 12, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:28:56'),
(464, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 01:29:00'),
(465, 14, 'login', 'users', 14, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 16:36:39'),
(466, 14, 'logout', 'users', 14, 'Usuario cerró sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 16:44:34'),
(467, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 16:44:39'),
(468, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 21:12:47'),
(469, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 22:16:18'),
(470, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-26 17:44:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `accion`, `tabla_afectada`, `registro_id`, `detalles`, `ip_address`, `user_agent`, `created_at`) VALUES
(471, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.100.176.54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-27 13:17:34'),
(472, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.100.176.54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-27 13:29:01'),
(473, 15, 'login', 'users', 15, 'Usuario inició sesión', '190.100.176.54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-27 13:29:07'),
(474, 15, 'login', 'users', 15, 'Usuario inició sesión', '190.100.176.54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-27 21:34:52'),
(475, 15, 'asociar_otec', 'otec_facilitadores', 2, 'Facilitador asociado a OTEC existente', '190.100.176.54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-27 22:03:27'),
(476, 10, 'login', 'users', 10, 'Usuario inició sesión', '190.161.218.162', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-01 21:06:14'),
(477, 10, 'logout', 'users', 10, 'Usuario cerró sesión', '190.161.218.162', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-01 21:06:52'),
(478, 15, 'login', 'users', 15, 'Usuario inició sesión', '190.161.218.162', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-01 21:06:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `availability`
--

CREATE TABLE `availability` (
  `id` int UNSIGNED NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `estado` enum('disponible','bloqueado') NOT NULL DEFAULT 'disponible',
  `motivo` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blocks`
--

CREATE TABLE `blocks` (
  `id` int NOT NULL,
  `facilitador_id` int DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bookings`
--

CREATE TABLE `bookings` (
  `id` int UNSIGNED NOT NULL,
  `otec_id` int UNSIGNED NOT NULL,
  `curso_id` int UNSIGNED NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `valor_acordado` decimal(10,2) DEFAULT NULL,
  `estado` enum('propuesta','pendiente','aprobada','rechazada','confirmada','finalizada','cancelada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `tipo_calendario` enum('continuo','sesiones') COLLATE utf8mb4_unicode_ci DEFAULT 'continuo',
  `recurrencia_config` text COLLATE utf8mb4_unicode_ci,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `created_by` int UNSIGNED NOT NULL,
  `facilitador_id` int UNSIGNED DEFAULT NULL,
  `approved_by` int UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `duracion_horas` int UNSIGNED NOT NULL,
  `modalidad` enum('online','presencial','hibrido') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `publico` tinyint(1) DEFAULT '1',
  `activo` tinyint(1) DEFAULT '1',
  `created_by` int DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descriptor_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `nombre`, `descripcion`, `duracion_horas`, `modalidad`, `publico`, `activo`, `created_by`, `imagen`, `descriptor_pdf`, `created_at`, `updated_at`) VALUES
(1, 'Análisis de Datos con Power BI Intermedio', 'Curso de Power BI para principiantes', 24, 'online', 1, 1, 10, NULL, NULL, '2026-04-02 23:27:32', '2026-04-16 00:08:49'),
(2, 'Análisis de Datos con Power BI - Intermedio', '', 24, 'presencial', 1, 1, 10, NULL, NULL, '2026-04-02 23:28:08', '2026-04-05 19:11:55'),
(3, 'Microsoft Excel Nivel B', '', 24, 'online', 1, 1, 10, 'uploads/cursos/imagenes/curso_3_1775327492.png', 'uploads/cursos/descriptores/descriptor_3_1775327492.pdf', '2026-04-02 23:29:02', '2026-04-05 19:08:22'),
(4, 'Microsoft Excel - Nivel B', '', 24, 'hibrido', 1, 1, 10, NULL, NULL, '2026-04-02 23:29:22', '2026-04-15 23:50:29'),
(5, 'Este es un curso de Prueba', '', 16, 'hibrido', 1, 1, 10, 'uploads/cursos/imagenes/curso_5_1775435286.png', 'uploads/cursos/descriptores/descriptor_5_1775435286.pdf', '2026-04-06 00:28:06', '2026-04-15 23:51:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('info','success','warning','danger') COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otec`
--

CREATE TABLE `otec` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rut` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `contacto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `imagen_otec` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `otec`
--

INSERT INTO `otec` (`id`, `nombre`, `rut`, `direccion`, `contacto`, `telefono`, `email`, `activo`, `imagen_otec`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Kibernum Capacitacion', '76.088.864-8', 'Hu', 'Lorena Lopez', '227961976', 'lorena.lopez@kibernumacademy.com', 1, 'logo_kibernum.png', 10, '2026-04-02 21:21:12', '2026-04-20 01:02:01'),
(2, 'Capacita', '1-9', '87 Pedro Eleuterio Godoy', 'Pedro Perez', '+569 8829 1695', 'jaime.r.sanmartin@mascapacita2.com', 1, NULL, 14, '2026-04-05 23:31:20', '2026-04-05 23:32:07'),
(3, 'PlayComp', '2-7', 'Av. Providencia 701 Of. 48', 'Jeannette Trivino', '+569 8829 1695', 'contacto@mascapacita2.com', 1, 'logo_playcomp.png', 15, '2026-04-06 17:05:43', '2026-04-20 01:04:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otec_facilitadores`
--

CREATE TABLE `otec_facilitadores` (
  `id` int UNSIGNED NOT NULL,
  `otec_id` int UNSIGNED NOT NULL,
  `facilitador_id` int UNSIGNED NOT NULL,
  `asignado_por` int UNSIGNED NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `otec_facilitadores`
--

INSERT INTO `otec_facilitadores` (`id`, `otec_id`, `facilitador_id`, `asignado_por`, `activo`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 10, 1, '2026-04-05 20:23:28', '2026-04-05 20:23:28'),
(2, 2, 14, 14, 1, '2026-04-06 15:46:27', '2026-04-06 15:46:27'),
(3, 3, 15, 15, 1, '2026-04-06 17:12:39', '2026-04-06 17:12:39'),
(4, 1, 15, 15, 1, '2026-04-18 14:17:55', '2026-04-18 14:17:55'),
(5, 3, 14, 14, 1, '2026-04-18 15:20:25', '2026-04-18 15:20:25'),
(6, 2, 15, 15, 1, '2026-04-27 22:03:27', '2026-04-27 22:03:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones_log`
--

CREATE TABLE `sesiones_log` (
  `id` int NOT NULL,
  `sesion_id` int UNSIGNED NOT NULL,
  `usuario_id` int UNSIGNED NOT NULL,
  `accion` enum('crear','suspender','reagendar','eliminar','reactivar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_anterior` text COLLATE utf8mb4_unicode_ci,
  `valor_nuevo` text COLLATE utf8mb4_unicode_ci,
  `motivo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` int UNSIGNED NOT NULL,
  `booking_id` int UNSIGNED NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `numero_sesion` int DEFAULT NULL,
  `estado` enum('pendiente','confirmada','realizada','cancelada') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `eliminada` tinyint DEFAULT NULL,
  `asistencia` int DEFAULT '0',
  `notas` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_facilitador`
--

CREATE TABLE `solicitudes_facilitador` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rut` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci,
  `estado` enum('pendiente','aprobada','rechazada') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `token_aprobacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solicitudes_facilitador`
--

INSERT INTO `solicitudes_facilitador` (`id`, `nombre`, `email`, `telefono`, `rut`, `empresa`, `mensaje`, `estado`, `token_aprobacion`, `created_at`, `updated_at`) VALUES
(1, 'Gonzalo Andres Perez', 'jaime.r.sanmartin@otlook.com', '+569 4973 1527', '1-9', '', '', 'aprobada', '46dd77d3ac2e6b5a4201aa1c77460559b5467fe14ccbd756b8d5170203430688', '2026-04-03 01:27:17', '2026-04-03 11:09:43'),
(2, 'Gonzalo Andres Perez', 'jaime.r.sanmartin@outlook.com', '+569 4973 1527', '1-9', '', '', 'aprobada', '63ea5f067025640610034027574189aa551047ce1193c15988790c994030f5d6', '2026-04-03 11:25:19', '2026-04-03 11:26:00'),
(3, 'Camila San Martin', 'camila.sanmartin.paz@gmail.com', '+569 72972829', '21.350.841-5', '', 'Hola Papito, aduyame', 'aprobada', 'b86a0a0573635cdfb6fe4852dd137caa93815b5f86384163322207a0a9f00760', '2026-04-04 18:20:06', '2026-04-04 18:23:38'),
(4, 'James Saint Martin Roinski', 'james.saintmartin@gmail.com', '+56912345678', '25.004.004-0', '', 'Toy Mocionao kon too ezto', 'aprobada', 'd304b52d1da078a178796c26376364b48691fd94b35523a0354a4d153dc72c06', '2026-04-22 21:12:19', '2026-04-26 17:57:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('facilitador','ejecutivo','administrador') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'ejecutivo',
  `otec_id` int UNSIGNED DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_by` int DEFAULT NULL,
  `rut` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `email`, `password`, `rol`, `otec_id`, `telefono`, `avatar`, `activo`, `created_by`, `rut`, `last_login`, `reset_token`, `reset_expires`, `created_at`, `updated_at`) VALUES
(10, 'Administrador Sistema', 'admin@agendapro.cl', '$2y$12$feAqIHVz2M3e9h1KMtrku.3SFk0orwGgL1Fe4by91xdD6ayk6IX1e', 'administrador', NULL, '+569 5742 8845', NULL, 1, 10, '', '2026-05-01 17:06:14', NULL, NULL, '2026-04-02 21:14:15', '2026-05-01 21:06:14'),
(11, 'Monica Ojeda Villamizar', 'monica.ojeda@kibernumacademy.com', '$2y$10$nmlPPqf8rnn1cE3qjDb1EuZb4MLNhaxyvpdHctFEtEJNRoJV/0IdO', 'ejecutivo', 1, '+569 4973 1527', NULL, 1, 10, '1-9', NULL, NULL, NULL, '2026-04-03 00:49:48', '2026-04-03 00:54:19'),
(12, 'Evelin Rivera', 'jaime.r.sanmartin@mascapacita2.com', '$2y$10$671Mf0Ep5MzF.cMWtYhUg.Q25L8R6M0lS9TIzl7KcYj.cL3beEOD2', 'ejecutivo', 1, '+569 5688 8792', NULL, 1, 10, '1-9', '2026-04-19 21:27:20', NULL, NULL, '2026-04-03 00:56:39', '2026-04-20 01:27:20'),
(14, 'Gonzalo Andres Perez', 'jaime.r.sanmartin@outlook.com', '$2y$12$0Oj77BPpTb1FW6krFZ/Kdu55GDtqY4n1MYLGOw8LfxXlEK//yz2hi', 'facilitador', NULL, '+569 4973 1527', NULL, 1, 10, '1-9', '2026-04-22 12:36:39', NULL, NULL, '2026-04-03 11:26:00', '2026-04-22 16:36:39'),
(15, 'Camila San Martin', 'camila.sanmartin.paz@gmail.com', '$2y$12$0Oj77BPpTb1FW6krFZ/Kdu55GDtqY4n1MYLGOw8LfxXlEK//yz2hi', 'facilitador', NULL, '+569 72972829', NULL, 1, 10, '21.350.841-5', '2026-05-01 17:06:57', NULL, NULL, '2026-04-04 18:23:38', '2026-05-01 21:06:57'),
(16, 'Juanita Gonzalez', 'administrador@mascapacita2.com', '$2y$10$hfB5jJuIBjq39kEHwPDZZuzppFf2FSkjg8d3VwQasI5mScPno9UgS', 'ejecutivo', 2, '+569 4973 1527', NULL, 1, 14, '1-9', '2026-04-15 08:36:11', NULL, NULL, '2026-04-05 23:33:44', '2026-04-15 12:36:11'),
(17, 'Carolina Cabrera Godoy', 'carolina.a.cabrerag@gmail.com', '$2y$10$6G9BQa7apvk0.y1A.0NyI.b/E15sTlWAb3P1usypBuXkA4sfql/Sm', 'ejecutivo', 3, '976543281', NULL, 1, 15, NULL, NULL, NULL, NULL, '2026-04-18 15:18:34', '2026-04-18 15:18:34'),
(18, 'James Saint Martin Roinski', 'james.saintmartin@gmail.com', '$2y$10$abJJ/cwbBY8rZZ4Kk3k5eeSHA3Rq3NZV2Dm1J97k.jWzcS5bCMS3e', 'facilitador', NULL, '+56912345678', NULL, 1, 10, '25.004.004-0', NULL, NULL, NULL, '2026-04-26 17:57:58', '2026-04-26 17:57:58');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_availability_fechas` (`fecha_inicio`,`fecha_fin`);

--
-- Indices de la tabla `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_bookings_fechas` (`fecha_inicio`,`fecha_fin`),
  ADD KEY `idx_bookings_otec` (`otec_id`),
  ADD KEY `idx_bookings_estado` (`estado`),
  ADD KEY `idx_facilitador` (`facilitador_id`),
  ADD KEY `idx_user_dates` (`facilitador_id`,`fecha_inicio`,`fecha_fin`);

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_read_at` (`read_at`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indices de la tabla `otec`
--
ALTER TABLE `otec`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rut` (`rut`);

--
-- Indices de la tabla `otec_facilitadores`
--
ALTER TABLE `otec_facilitadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_asignacion` (`otec_id`,`facilitador_id`),
  ADD KEY `idx_otec` (`otec_id`),
  ADD KEY `idx_facilitador` (`facilitador_id`),
  ADD KEY `idx_asignado_por` (`asignado_por`);

--
-- Indices de la tabla `sesiones_log`
--
ALTER TABLE `sesiones_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sesion_id` (`sesion_id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_accion` (`accion`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking` (`booking_id`),
  ADD KEY `idx_fecha` (`fecha_inicio`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_sessions_eliminada` (`eliminada`);

--
-- Indices de la tabla `solicitudes_facilitador`
--
ALTER TABLE `solicitudes_facilitador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `otec_id` (`otec_id`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=479;

--
-- AUTO_INCREMENT de la tabla `availability`
--
ALTER TABLE `availability`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `otec`
--
ALTER TABLE `otec`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `otec_facilitadores`
--
ALTER TABLE `otec_facilitadores`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sesiones_log`
--
ALTER TABLE `sesiones_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `solicitudes_facilitador`
--
ALTER TABLE `solicitudes_facilitador`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`otec_id`) REFERENCES `otec` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_bookings_facilitador` FOREIGN KEY (`facilitador_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `otec_facilitadores`
--
ALTER TABLE `otec_facilitadores`
  ADD CONSTRAINT `fk_otec_facilitadores_asignador` FOREIGN KEY (`asignado_por`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_otec_facilitadores_facilitador` FOREIGN KEY (`facilitador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_otec_facilitadores_otec` FOREIGN KEY (`otec_id`) REFERENCES `otec` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sesiones_log`
--
ALTER TABLE `sesiones_log`
  ADD CONSTRAINT `sesiones_log_ibfk_1` FOREIGN KEY (`sesion_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sesiones_log_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`otec_id`) REFERENCES `otec` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
