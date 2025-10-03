-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-10-2025 a las 03:56:59
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
-- Base de datos: `expediente_ies`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sistema-academico-ies-cache-46407086|127.0.0.1', 'i:1;', 1759323032),
('sistema-academico-ies-cache-46407086|127.0.0.1:timer', 'i:1759323032;', 1759323032);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursar_agosto_2_semestre`
--

CREATE TABLE `cursar_agosto_2_semestre` (
  `id` int(11) NOT NULL,
  `alumno` varchar(255) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(3) DEFAULT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `fecha_cancelacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `cursar_agosto_2_semestre`
--

INSERT INTO `cursar_agosto_2_semestre` (`id`, `alumno`, `materia`, `fecha`, `estado`, `observacion`, `fecha_cancelacion`) VALUES
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_agosoto_2_llamado`
--

CREATE TABLE `mesa_agosoto_2_llamado` (
  `id` int(11) NOT NULL,
  `alumno` varchar(255) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(3) DEFAULT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `fecha_cancelacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `mesa_agosoto_2_llamado`
--

INSERT INTO `mesa_agosoto_2_llamado` (`id`, `alumno`, `materia`, `fecha`, `estado`, `observacion`, `fecha_cancelacion`) VALUES
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_agosto_1_llamado`
--

CREATE TABLE `mesa_agosto_1_llamado` (
  `id` int(11) NOT NULL,
  `alumno` varchar(255) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(3) DEFAULT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `fecha_cancelacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `mesa_agosto_1_llamado`
--

INSERT INTO `mesa_agosto_1_llamado` (`id`, `alumno`, `materia`, `fecha`, `estado`, `observacion`, `fecha_cancelacion`) VALUES
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL),
(2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL),
(3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL),
(4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL),
(5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL),
(6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL),
(7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL),
(10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL),
(11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_30_151018_crear_tabla_reglas_correlativas', 2),
(6, '2025_09_30_153039_create_personal_access_tokens_table', 3),
(7, '2025_09_30_202358_add_dni_to_users_table', 3),
(8, '2025_10_01_020019_create_tbl_periodos_inscripcion_table', 4),
(9, '2025_10_01_153302_add_descripcion_personalizada_to_alumnos', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reglas_correlativas`
--

CREATE TABLE `reglas_correlativas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `materia_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la materia (FK a tbl_materias)',
  `carrera_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la carrera (FK a tbl_carreras)',
  `tipo` enum('cursar','rendir') NOT NULL COMMENT 'Tipo de validación',
  `correlativa_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la materia correlativa',
  `estado_requerido` enum('regular','aprobada') NOT NULL COMMENT 'Estado que debe tener la correlativa',
  `es_activa` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Permite activar/desactivar regla',
  `excepciones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Casos especiales en formato JSON' CHECK (json_valid(`excepciones`)),
  `observaciones` text DEFAULT NULL COMMENT 'Notas adicionales sobre la regla',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ChPmJaTymRjDrZPTHgo8ODpM6YCTj8iT3sAXXwKu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQTJ1MlN2NXdVazBkVHdUM2JldkhaMnNoWFRzSXR1WTdxZkVvTTBpTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2luc2NyaXBjaW9uZXMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2luc2NyaXBjaW9uZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1759322950),
('DXaJnAJvA1a9IdsCldC8CE1sOcsKWueckYdv9Tlm', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidVdnVmYwMHRUS2I4eHlic0hOdHdydW82NVd2UHcwajFTeEVmajhUUSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvaW5zY3JpcGNpb25lcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1759291528),
('i1DeRUCRkb8ZStqQ8ELC7cyOxsuLxuqYdEMYIdr5', NULL, '127.0.0.1', 'curl/8.12.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTGNzMFRlQWczZUlJUWQwa2FTa1RKUjNGdzE4NFNYWWlnaVd6bmFqYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2luc2NyaXBjaW9uZXMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2luc2NyaXBjaW9uZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1759251138),
('jG476sXPBwW1HohBTIxhixKbsDwng0yUzn7ZYxEO', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaWIydG55d2xhcUF6TUhHd2x6ZUVKMVNNQUVrUWJZZTdSWnE2aUtOZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTc7fQ==', 1759339868),
('WFc6PmbxfpZWyCGO2cEqB2rxFvieEhidkKICCdhA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEdFVEdIZXcwSWljTkNpYzIyanNKUWZ5UlFFOFJLbUpzaDdCS3g2aiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1759263863);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_alumnos`
--

CREATE TABLE `tbl_alumnos` (
  `id` int(11) NOT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `curso` int(11) NOT NULL,
  `division` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `legajo` varchar(255) DEFAULT NULL,
  `descripcion_personalizada` text DEFAULT NULL,
  `anno` varchar(255) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `materia` int(11) NOT NULL DEFAULT 0,
  `turno` int(11) NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_alumnos`
--

INSERT INTO `tbl_alumnos` (`id`, `dni`, `apellido`, `nombre`, `curso`, `division`, `email`, `telefono`, `celular`, `legajo`, `descripcion_personalizada`, `anno`, `carrera`, `materia`, `turno`, `fecha`) VALUES
(1, '20829266', 'Quiroga', 'Carlos Eduardo', 0, 2, 'quiedu@gmail.com', '+542644001533', '+542644001533', NULL, 'Casi listo para cambiar el mundo con código', '2022', 1, 0, 0, '2023-07-26 09:08:49'),
(2, '42809162', 'Moran ', 'Federico ', 0, 1, 'federicojmoran@gmail.com', '-', '2644534735', NULL, 'Casi listo para cambiar el mundo con código', '2021', 1, 0, 0, '2023-07-26 09:13:57'),
(3, '45473369', 'Burgoa', 'Rosana', 0, 2, 'rosanaburgoa123@gmail.com', '2644398404', '2644398404', NULL, 'La tecnología espera tus ideas', '2', 1, 0, 0, '2023-07-26 09:55:04'),
(4, '44316616', 'Echevarria ', 'Carlos Leandro JesÃºs ', 0, 2, 'leandroechevarria0107@gmail.com', '', '2644054399', NULL, 'La tecnología espera tus ideas', '2023', 1, 0, 0, '2023-07-26 10:34:56'),
(5, '29694457', 'Peralta CÃ¡ceres ', 'Omar Ariel ', 0, 1, 'omarperalta1408@gmail.com', '', '2644819470', NULL, 'Estás a punto de alcanzar tu meta', '2023', 1, 0, 0, '2023-07-26 10:38:35'),
(6, '40591552', 'AgÃ¼ero ', 'Eugenio Ismael ', 0, 2, 'ismael8aguero@gmail.com', '', '2645718934', NULL, 'El conocimiento es tu mejor inversión', '2023', 1, 0, 0, '2023-07-26 10:51:09'),
(7, '46726829', 'Godoy', 'AgustÃ­n ', 0, 1, 'godoyagustin802@gmail.com', '', '2645482409', NULL, 'Cada bug resuelto es un paso adelante', '2023', 1, 0, 0, '2023-07-26 11:47:21'),
(8, '40823054', 'MuÃ±oz', 'Angela Cecilia', 0, 2, 'angiemunoz432@gmail.com', '2644845105', '2644845105', NULL, 'El primer paso es el más importante', '2022', 1, 0, 0, '2023-07-26 12:10:27'),
(9, '46407762', 'AgÃ¼ero', 'Yamil', 0, 1, 'yaguerogil@gmail.com', '2644434072', '2644434072', NULL, 'Estás construyendo las bases de tu éxito', '2023', 1, 0, 0, '2023-07-26 12:46:10'),
(10, '35852955', 'ArgaÃ±araz Diaz', 'Eliana Gabriela ', 0, 2, 'Elianaargdiaz@gmail.com', '', '2646039934', NULL, 'Cada línea de código te acerca a tus sueños', '2023', 1, 0, 0, '2023-07-26 13:18:05'),
(11, '43690003', 'Reyes ', 'Mateo ', 0, 2, 'reyesmateo988@gmail.com', '2644046033', '2644046033', NULL, 'Estás construyendo las bases de tu éxito', '2023', 1, 0, 0, '2023-07-26 13:37:41'),
(12, '42712320', 'Fonzalida ', 'Melina ', 0, 1, 'fonzalidamelina1@gmail.com', '', '2644720921', NULL, 'El aprendizaje constante es tu superpoder', '2023', 1, 0, 0, '2023-07-26 14:32:09'),
(13, '43488986', 'MuÃ±oz', 'Fernando', 0, 1, 'munozfernando264@gmail.com', '', '2645259424', NULL, 'El mundo tech necesita tu talento', '2022', 1, 0, 0, '2023-07-26 15:56:32'),
(14, '43641793', 'Balmaceda', 'Karen', 0, 2, 'balmacedaemilce290@gmail.com', '', '2645882800', NULL, 'Tus habilidades crecen con cada desafío', '2022', 1, 0, 0, '2023-07-26 18:10:24'),
(15, '40086620', 'Simeoni', 'Facundo', 0, 1, 'facundosimeoni1@gmail.com', '2646293463', '2646293463', NULL, 'La tecnología espera tus ideas', '2019', 1, 0, 0, '2023-07-26 18:12:53'),
(16, '46180633', 'Areyuna', 'Ramon', 3, 1, 'ramonareyuna09@gmail.com', '2645839327', '2645839327', NULL, 'La tecnología espera tus ideas', '3', 1, 0, 0, '2023-07-26 19:45:06'),
(17, '43689779', 'Molina ', 'Ricardo Nahuel', 0, 1, 'nahuelmoli270@gmail.com', '', '2646222825', NULL, 'Cada proyecto te hace más fuerte', '2023', 1, 0, 0, '2023-07-26 19:48:07'),
(18, '44062183', 'Sarmiento', 'Mathias', 0, 1, 'mathisarmiento6@gmail.com', '2645677254', '2645677254', NULL, 'La persistencia es tu mejor algoritmo', '2023', 1, 0, 0, '2023-07-26 19:48:21'),
(19, '45473305', 'Garibay', 'Ramiro', 0, 2, 'ramirogaribay69@gmail.com', '', '2645769672', NULL, 'Casi listo para cambiar el mundo con código', '2023', 1, 0, 0, '2023-07-26 19:48:50'),
(20, '44018317', 'Castro', 'Exequiel', 0, 1, 'exeloc3@gmail.com', '(264) 577-0761', '2645770761', NULL, 'El mundo tech necesita tu talento', '2022', 1, 0, 0, '2023-07-26 19:49:58'),
(21, '45634862', 'Diaz', 'Ariana', 0, 2, 'ad421637@gmail.com', '265457202', '2645457202', NULL, 'Aprende los fundamentos, dominarás el futuro', '2023', 1, 0, 0, '2023-07-26 19:50:51'),
(22, '34100722', 'Garcia Martin ', 'Victor Ariel', 0, 2, 'victorarielgarciamartin@hotmail.com', '2645150526', '2645150526', NULL, 'El primer paso es el más importante', '2023', 1, 0, 0, '2023-07-26 19:55:08'),
(23, '45473301', 'Fernandez', 'Amilcar Jose Leonel', 0, 1, 'amilcarlfernandez310@gmail.com', '', '2645787558', NULL, 'Cada bug resuelto es un paso adelante', '2023', 1, 0, 0, '2023-07-26 20:01:18'),
(24, '45635063', 'Aballay Sepeda', 'Eli Valentina', 0, 1, 'eliaballay534@gmail.com', '2645117870', '2645117870', NULL, 'La tecnología espera tus ideas', '2023', 1, 0, 0, '2023-07-26 20:10:46'),
(25, '44634748', 'Gomez Gimenez ', 'Luis Gabriel ', 0, 1, 'luisgomez98654@gmail.com', '', '2646234290', NULL, 'Tus habilidades crecen con cada desafío', '2023', 1, 0, 0, '2023-07-26 23:37:27'),
(26, '46070642', 'Vieyra', 'Matias', 0, 1, 'matiashidalgo147@gmail.com', '2644361541', '2644361541', NULL, 'El primer paso es el más importante', '2023', 1, 0, 0, '2023-07-26 23:42:12'),
(27, '42081141', 'Gimenez', 'Leandro Albano', 0, 2, 'albanogimenez062@gmail.com', '', '2644715192', NULL, 'Aprende los fundamentos, dominarás el futuro', '2020', 1, 0, 0, '2023-07-27 00:30:32'),
(28, '45212175', 'Bustos', 'Juan', 0, 1, 'juanixd873@gmail.com', '2644893444', '2644893444', NULL, 'El conocimiento es tu mejor inversión', '2023', 1, 0, 0, '2023-07-27 08:04:34'),
(29, '4521175', 'Bustos', 'Juan', 0, 1, 'juanixd873@gmail.com', '2644893444', '2644893444', NULL, 'Tu código puede cambiar el mundo', '2023', 1, 0, 0, '2023-07-27 08:05:44'),
(30, '41641247', 'Ferreyra', 'Yamila', 0, 1, 'Yamilaferreyra444@gmail.com', '', '2645892359', NULL, 'Cada bug resuelto es un paso adelante', '1', 1, 0, 0, '2023-07-27 09:19:24'),
(31, '35850632', 'Ramirez', 'Emiliano,emanuel', 0, 1, 'emilianoramirez2091@gmail.com', '', '2646268639', NULL, 'El primer paso es el más importante', '2023', 1, 0, 0, '2023-07-27 11:33:07'),
(32, '44674217', 'rodriguez', 'alan', 0, 2, 'rodriguezalanm731@gmail.com', '02645296845', '2645263370', NULL, 'La tecnología espera tus ideas', '1', 1, 0, 0, '2023-07-27 12:21:37'),
(33, '42516938', 'Guaquinchay ', 'AyelÃ©n RocÃ­o ', 0, 1, 'ayelen.guaquinchay09@gmail.com', '', '2644681308', NULL, 'Sigue así, lo estás haciendo muy bien', '2023', 1, 0, 0, '2023-07-27 12:46:19'),
(34, '33836289', 'Navarro ', 'Maximiliano ', 0, 2, 'sirmaximilian1@hotmail.com', '2644144421', '2644144421', NULL, 'Estás construyendo las bases de tu éxito', '2020', 1, 0, 0, '2023-07-27 14:07:42'),
(35, '34609402', 'Carrizo ', 'MatÃ­as Gabriel ', 0, 2, 'matias.g.carrizo11@gmail.com', '', '2644813560', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2023', 1, 0, 0, '2023-07-27 15:25:32'),
(36, '42005854', 'Cordoba', 'Maximiliano ', 0, 1, 'maxcordoba100@gmail.com', '2646619081', '2646619081', NULL, 'Cada bug resuelto es un paso adelante', '2022', 1, 0, 0, '2023-07-27 17:37:20'),
(37, '45634685', 'Garcia ', 'Dayana', 0, 2, 'dayimarilau@gmail.com', '', '2644411948', NULL, 'Sigue así, lo estás haciendo muy bien', '2023', 1, 0, 0, '2023-07-27 18:03:57'),
(38, '43952965', 'Toledo', 'SebastiÃ¡n', 0, 2, 'sebatoledo.sdt@gmail.com', '2646720761', '2646720761', NULL, 'Cada bug resuelto es un paso adelante', '2023', 1, 0, 0, '2023-07-27 18:07:46'),
(39, '42187883', 'Uribi', 'Antonio ', 0, 2, 'uribeantonio078@gmail.com', '', '2645710511', NULL, 'El primer paso es el más importante', '2023', 1, 0, 0, '2023-07-27 18:12:06'),
(40, '46544506', 'Moyano', 'Braian', 0, 1, 'braiankevinmoyano@gmail.com', '2644893834', '2644893834', NULL, 'Cada línea de código te acerca a tus sueños', '2023', 1, 0, 0, '2023-07-27 18:13:14'),
(41, '43952026', 'Montenegro', 'Nara Fabiana', 0, 1, 'nara74578@gmail.com', '2645796815', '2645796815', NULL, 'El primer paso es el más importante', '2023', 1, 0, 0, '2023-07-27 18:50:36'),
(42, '42356568', 'Castillo', 'Rodrigo', 0, 1, 'rofcastillo@gmail.com', '4315523', '2645450921', NULL, 'La tecnología espera tus ideas', '2022', 1, 0, 0, '2023-07-27 19:52:18'),
(68, '44527380', 'Fiorelli', 'Santino', 0, 2, 'santifiorelli27@gmail.com', '4963227', '2644622255', NULL, 'Cada proyecto te hace más fuerte', '2022', 1, 0, 0, '2023-08-08 11:35:44'),
(69, '42081141', 'Gimenez', 'Leandro Albano', 0, 2, 'albanogimenez062@gmail.com', '', '2644715495', NULL, 'Ya recorriste la mitad del camino', '2020', 1, 0, 0, '2023-08-08 11:48:17'),
(70, '40823054', 'MuÃ±oz', 'Angela Cecilia', 0, 2, 'angiemunoz432@gmail.com', '2644845105', '2644845105', NULL, 'Casi listo para cambiar el mundo con código', '2022', 1, 0, 0, '2023-08-08 12:00:13'),
(73, '44018519', 'Palta', 'Luciano', 0, 2, 'Lucianoalejandropalta@gmail.com', '4963930', '2645790362', NULL, 'Cada proyecto te hace más fuerte', '2023', 1, 0, 0, '2023-08-08 15:24:52'),
(74, '44248877', 'Saavedra ', 'Jeremias Gerardo', 0, 2, 'Jeremiassaavedra99@gmail.com', '2645726540', '2645726540', NULL, 'Cada línea de código te acerca a tus sueños', '2023', 1, 0, 0, '2023-08-08 15:35:03'),
(75, '25590289', 'Aguero', 'Jorge Luis', 0, 1, 'flacobass@gmail.com', '02644963279', '2646238119', NULL, 'El mundo tech necesita tu talento', '2021', 1, 0, 0, '2023-08-08 16:05:23'),
(76, '45635742', 'Orosco', 'Matias ', 0, 2, 'oroscon35@gmail.com', '', '2645769084', NULL, 'La meta está cerca, no te rindas ahora', '2022', 1, 0, 0, '2023-08-08 16:34:17'),
(77, '42334964', 'Paredes', 'Tomas', 0, 0, 'tomasparedes764@gmail.com', '', '+5492644699952', NULL, 'El primer paso es el más importante', '2022', 1, 0, 0, '2023-08-08 19:05:19'),
(82, '44527338', 'GarcÃ­a ', 'Daniel ', 0, 2, 'daniestebangarciasj@gmail.com', '', '2644831555', NULL, 'La tecnología espera tus ideas', '2022', 1, 0, 0, '2023-08-09 10:50:14'),
(84, '41122633', 'Sirerol', 'Mateo', 0, 1, 'mateo.sirerolsanchez9@gmail.com', '2644962105', '2645404301', NULL, 'Tus habilidades crecen con cada desafío', '2020', 1, 0, 0, '2023-08-09 13:46:32'),
(86, '41814431', 'Ozan Pastran ', 'Facundo Emiliano ', 0, 1, 'ozanfacundo13@gmail.com', '', '2645406894', NULL, 'La persistencia es tu mejor algoritmo', '2023', 1, 0, 0, '2023-08-09 16:29:52'),
(89, '45212726', 'Cortez', 'Fabrizio ', 0, 1, 'fabriziocortez98325@gmail.com', '2644858479', '2644858479', NULL, 'La persistencia es tu mejor algoritmo', '2023', 1, 0, 0, '2023-08-09 17:00:00'),
(90, '37609402', 'Carrizo ', 'MatÃ­as ', 0, 2, 'matias.g.carrizo11@gmail.com', '', '2644813560', NULL, 'Tu código puede cambiar el mundo', '2023', 1, 0, 0, '2023-08-09 17:32:43'),
(91, '46476829', 'Godoy', 'Agustin', 0, 1, 'godoyagustin802@gmail.com', '2645482409', '2645482409', NULL, 'Cada línea de código te acerca a tus sueños', '2023', 1, 0, 0, '2023-08-09 17:35:16'),
(92, '46258855', 'Antunez ', 'Fabricio ', 0, 1, 'fabriciomaximiliano.68@gmail.com', '2644671539', '2644671539', NULL, 'El conocimiento es tu mejor inversión', '2023', 1, 0, 0, '2023-08-09 17:44:36'),
(94, '38458530', 'Jorquera', 'Daniel', 0, 2, 'danieljorqueraa130@yahoo.com', '2646271346', '2646271346', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2023', 1, 0, 0, '2023-08-09 17:50:03'),
(96, '45634862', 'Diaz Correa', 'Ariana', 0, 2, 'ad421637@gmail.com', '2645457202', '2645457202', NULL, 'El conocimiento es tu mejor inversión', '2023', 1, 0, 0, '2023-08-09 21:14:29'),
(127, '40273506', 'Ocaranza ', 'Natalia ocaranza ', 0, 0, 'nataliaocaranza89@gmail.com', '', '2646232163', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 09:17:51'),
(128, '25573246', 'Fkores', 'JosÃ© RamÃ³n ', 0, 0, 'jrf19760@gmail.com', '', '2645724820', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 09:19:50'),
(129, '45212796', 'Ruarte Maldonado ', 'Ana Paula ', 0, 0, 'ruarteanapaula@gmail.com', '2644767095', '2644764095', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 09:32:44'),
(130, '45264102', 'Garcia ', 'VerÃ³nica ', 0, 0, 'vero.garcia5ph@gmail.com', '2646723931', '2646723931', NULL, NULL, 'Primer aÃ±o', 5, 0, 0, '2023-09-18 09:50:49'),
(131, '32353013', 'Ge pereyra ', 'Liliana natali ', 0, 0, 'liliananatalioge@gmail.com', '2646611092', '2646611092', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 10:08:56'),
(132, '40367829', 'Becerra Veragua ', 'MarÃ­a del RocÃ­o ', 0, 0, 'rousagi2015@gmail.com', '', '2645017511', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 18:19:51'),
(133, '37833893', 'Becerra ', 'Celeste', 0, 0, 'celestebecerraveragua@gmail.com', '2644655345', '2644655345', NULL, NULL, '2023', 0, 0, 0, '2023-09-18 18:27:39'),
(134, '39011548', 'Gutierrez', 'Waldo', 0, 0, 'waldoguti.wg@gmail.com', '2645070681', '2645070681', NULL, NULL, '1', 5, 0, 0, '2023-09-18 18:51:12'),
(135, '43689762', 'AgÃ¼ero ', 'Milagros ', 0, 0, 'miliaguero24@gmail.com', '2644432291', '2644432291', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 18:52:41'),
(136, '42334976', 'Aciar Sanchez ', 'Evy Lourdes', 0, 0, 'evyaciar21@gmail.com', '4963735', '2644627361', NULL, NULL, '1ro', 5, 0, 0, '2023-09-18 22:46:43'),
(137, '41909088', 'Mercado', 'Pamela ', 0, 0, 'pamela07mercado@gmail.com', '2645266911', '2645266911', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 22:56:00'),
(138, '44730818', 'Rivero', 'Exequiel', 0, 0, 'riverohoracio531@gmail.com', '2645892104', '2645892104', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 23:38:55'),
(139, '32624461', 'Quiroga', 'Jesica Noelia', 0, 0, 'quirogayesicanoelia@gmail.com', '+542645663124', '2645663124', NULL, NULL, '2023', 5, 0, 0, '2023-09-18 23:43:52'),
(140, '45473342', 'Aballay', 'Maite', 0, 0, 'maiteaballay1@gmail.com', '2644104964', '2644104965', NULL, NULL, '2023', 5, 0, 0, '2023-09-19 00:02:03'),
(141, '46726275', 'Segura', 'Delfina ', 0, 0, 'delfinaasegura14@gmail.com', '2644186953', '2644186953', NULL, NULL, '2023', 5, 0, 0, '2023-09-19 02:06:36'),
(142, '41957748', 'Bustos', 'Gimena', 0, 0, 'bustosgimena23@gmail.com', '', '2645717687', NULL, NULL, '2023', 5, 0, 0, '2023-09-19 10:54:33'),
(143, '32353041', 'Torres', 'Julieta yanina', 0, 0, 'torresjuli57@gmail.com', '02644676136', '02644676136', NULL, NULL, '2023', 5, 0, 0, '2023-09-19 13:44:46'),
(144, '40779372', 'VelÃ¡zquez zalazar ', 'Maria del valle ', 0, 0, 'mariazalazar119@gmail.com', '4961488', '2645727227', NULL, NULL, '2023', 5, 0, 0, '2023-09-19 17:23:46'),
(145, '46408459', 'Chavez', 'Milagros Gilda', 0, 0, 'milagroschavez1536@gmail.com', '2644129775', '2644129775', NULL, NULL, '2023', 5, 0, 0, '2023-09-19 19:13:28'),
(159, '46407086', 'aguero godoy', 'facundo alejandro', 0, 1, 'a@gmail.com', '', '2644123456', NULL, 'Estás construyendo las bases de tu éxito', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(162, '43689758', 'Aguero', 'Emanuel Alejandro', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'La persistencia es tu mejor algoritmo', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(163, '45472928', 'Alamo', 'Franco Adrian Jesus', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada proyecto te hace más fuerte', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(164, '46407026', 'Arredondo Aranda', 'Luciano Francisco', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Sigue así, lo estás haciendo muy bien', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(165, '46933134', 'Correa Velardez', 'Matias Luciano', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Aprende los fundamentos, dominarás el futuro', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(166, '46544178', 'Cortes Rodriguez', 'Enzo David', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'El primer paso es el más importante', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(167, '44062491', 'Diaz Rodriguez', 'Enzo Santiago', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'El conocimiento es tu mejor inversión', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(168, '43555663', 'Dominguez Heredia', 'Hector Santiago', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Aprende los fundamentos, dominarás el futuro', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(169, '44060993', 'Farias', 'Luis', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(170, '35850538', 'Lucero Bustos', 'Ricardo Matias', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(171, '46805928', 'Maradona', 'Juan Carlos', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Tu código puede cambiar el mundo', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(172, '47046928', 'Medina Aguero', 'Martina', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'El primer paso es el más importante', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(173, '46071794', 'Moran Castro', 'Florencia Dayana', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'El primer paso es el más importante', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(174, '46616541', 'Ogas Rivero', 'Efrain Moises', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'El conocimiento es tu mejor inversión', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(175, '46407083', 'Olmos Duran', 'Bruno Ivan', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'Sigue así, lo estás haciendo muy bien', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(176, '44991810', 'Pereyra Gimenez', 'Pablo Manuel', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'El primer paso es el más importante', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(177, '33460738', 'Rojas Torres', 'Johana Silvana', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'El primer paso es el más importante', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(178, '44018109', 'Saavedra Oropel', 'Gian Franco', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'Sigue así, lo estás haciendo muy bien', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(180, '46484581', 'Ucciardelo Pagliari', 'Lautaro', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'El conocimiento es tu mejor inversión', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(181, '47046535', 'Yubelmacitusa Amir', 'Eduardo Daniel', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, 'Tu código puede cambiar el mundo', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(182, '46803652', 'Antunez Saavedra ', 'Jenifer Aida', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Sigue así, lo estás haciendo muy bien', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(183, '33059379', 'Balmaceda', 'Eduardo Emmanuel', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Estás construyendo las bases de tu éxito', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(184, '45981179', 'Chadi PeÃ±aloza', 'Hector Miguel Ivan', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada proyecto te hace más fuerte', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(185, '45635418', 'Escudero Oviedo', 'Estela Virginia', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(186, '46544590', 'Funes Leyes', 'Maximiliano Alexis', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada proyecto te hace más fuerte', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(187, '42909475', 'Gomez Heredia', 'Julieta Edith', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(188, '44844864', 'Martinez Caravajal', 'Mauricio Adrian', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'El primer paso es el más importante', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(189, '47370452', 'Pereyra Nieva', 'Jesus Mauricio Fernando', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Estás construyendo las bases de tu éxito', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(190, '43423151', 'Reinoso Atencio ', 'Celina Alexia', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Aprende los fundamentos, dominarás el futuro', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(191, '44018679', 'Rodriguez Castro', 'Franco Javier', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'El primer paso es el más importante', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(192, '45377877', 'Romero Barzola', 'Milton', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'La persistencia es tu mejor algoritmo', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(193, '46485339', 'Silva Aballay', 'Milagros Ayelen', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(194, '47285718', 'Silva Gomez', 'Magali Aldana', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Estás construyendo las bases de tu éxito', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(195, '42081123', 'Suarez', 'Ismael Maximiliano', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Sigue así, lo estás haciendo muy bien', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(196, '42706650', 'Torres', 'Matias Ismael', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Tu código puede cambiar el mundo', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(197, '46408369', 'Videla', 'Juana Elizabeth', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada proyecto te hace más fuerte', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(198, '47286300', 'IbaÃ±ez', 'Abril', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Tu código puede cambiar el mundo', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(199, '46407750', 'Montivero', 'Alejo', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Aprende los fundamentos, dominarás el futuro', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(200, '41531447', 'Endrizzi', 'Juan', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2024', 1, 0, 1, '0000-00-00 00:00:00'),
(202, '42081190', 'GarcÃ­a Salinas', 'Rodrigo Alexis', 0, 1, 'prueba@gmail.com', '', '264400000', NULL, 'La tecnología espera tus ideas', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(203, '43642145', 'Vargas Gutierrez', 'MarÃ­a Florencia', 0, 2, 'g@gmail.com', '26400000', '264400000', NULL, 'Aprende los fundamentos, dominarás el futuro', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(204, '39651990', 'Alvarez Vicentela', 'Pablo Exequiel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'La persistencia es tu mejor algoritmo', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(205, '42168750', 'Asevey Mollo', 'Franco Armando', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada proyecto te hace más fuerte', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(206, '42452396', 'Tejada Godoy', 'Jorge Alexander', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'El conocimiento es tu mejor inversión', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(207, '45981148', 'Gonzalez Suarez', 'Carlos Esteban', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Tu código puede cambiar el mundo', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(208, '49563950', 'Ochoa', 'Luz Milagro del Valle', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'El primer paso es el más importante', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(209, '35850436', 'Reinoso Bustos', 'Jose Dario Jairo', 0, 1, 'xxx@gmail.com', '12234', '12345', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2023', 1, 0, 1, '0000-00-00 00:00:00'),
(210, '33846101', 'Zalazar', 'Gisela AnahÃ­', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Aprende los fundamentos, dominarás el futuro', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(211, '38016491', 'Zalazar', 'BelÃ©n Elizabeth', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(212, '35857033', 'Rosas GarcÃ­a', 'Vicente', 0, 2, 'g@gmail.com', '222', '222', NULL, 'El primer paso es el más importante', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(213, '44527318', 'Riveros Belli', 'Marcelo', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada línea de código te acerca a tus sueños', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(214, '39525909', 'Alonso', 'Santiago David', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'La persistencia es tu mejor algoritmo', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(215, '45884679', 'Castillo', 'Rocio Belen', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(216, '23752197', 'Narvay', 'Estela Alejandra', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada línea de código te acerca a tus sueños', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(217, '43123431', 'Homedes', 'Juan Agustin', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(218, '44730830', 'Luna Bustos', 'Nazareno Nehuen', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(219, '43221132', 'Monardez Furlani', 'Facundo Ismael', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Aprende los fundamentos, dominarás el futuro', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(220, '44730841', 'Arce', 'Caterina Adriana', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Aprende los fundamentos, dominarás el futuro', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(221, '27639445', 'Castro Oviedo', 'MarÃ­a Laura', 0, 2, 'g@gmail.com', '222', '222', NULL, 'El conocimiento es tu mejor inversión', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(222, '43690017', 'Paez Guzman', 'Benjamin', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'La persistencia es tu mejor algoritmo', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(223, '25237008', 'Nievas', 'Cristina Graciela', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Aprende los fundamentos, dominarás el futuro', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(224, '44316644', 'Flores', 'Juan Ignacio', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Ya recorriste la mitad del camino', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(225, '45634679', 'Brizuela RÃ­os', 'Ulises Luciano', 0, 2, 'g@gmail.com', '222', '222', NULL, 'El conocimiento es tu mejor inversión', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(226, '43221182', 'Monardez Furlani', 'Facundo Ismael', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada línea de código te acerca a tus sueños', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(227, '24038819', 'Usair', 'Julio CÃ©sar', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Aprende los fundamentos, dominarás el futuro', '2022', 1, 0, 1, '0000-00-00 00:00:00'),
(228, '44844544', 'Ahumada Lucero', 'Nahuel Nair', 0, 1, 'g@gmail.com', '222', '222', NULL, 'La persistencia es tu mejor algoritmo', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(229, 'Calderon Acosta', 'Dalila BelÃ©n', '39009483', 0, 1, 'g@gmail.com', '222', '222', NULL, 'El primer paso es el más importante', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(230, 'Carrizo', 'Leonardo GastÃ³n', '29226409', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Tu código puede cambiar el mundo', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(231, '43689746', 'Gutierrez Aguero', 'SebastiÃ¡n', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Estás construyendo las bases de tu éxito', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(232, '38078817', 'Leiva Fernandez', 'Alfredo', 0, 1, 'g@gmail.com', '222', '222', NULL, 'La tecnología espera tus ideas', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(233, '35849716', 'Martin Campillay', 'Macarena', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Cada proyecto te hace más fuerte', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(234, '43689707', 'Melian Fernandez', 'Franco Leonel', 0, 1, 'g@gmail.com', '222', '222', NULL, 'La persistencia es tu mejor algoritmo', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(235, '44665720', 'Romero', 'Leandro JesÃºs', 0, 1, 'g@gmail.com', '222', '222', NULL, 'El primer paso es el más importante', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(236, '44248998', 'Carrizo', 'Diego AgustÃ­n', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(237, '35922670', 'Castro', 'GastÃ³n Esteban', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada línea de código te acerca a tus sueños', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(238, '42250428', 'Ganzitano', 'IvÃ¡n David', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Tus habilidades crecen con cada desafío', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(239, '41957684', 'Marti Castro', 'Ignacio ValentÃ­n', 0, 2, 'g@gmail.com', '222', '222', NULL, 'El aprendizaje constante es tu superpoder', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(240, '38595276', 'Montiveros', 'MarÃ­a Ayelen', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Ya recorriste la mitad del camino', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(241, '41776488', 'Morales', 'JesÃºs Facundo Daniel', 0, 2, 'g@gmail.com', '222', '222', NULL, 'La meta está cerca, no te rindas ahora', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(242, '42163173', 'Moralez Moreno', 'MatÃ­as AndrÃ©s', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Casi listo para cambiar el mundo con código', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(243, '43764054', 'Moralez Moreno', 'TomÃ¡s MartÃ­n', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Tu título está más cerca que nunca', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(244, '37833931', 'Quiroga', 'Carina Micaela', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Casi listo para cambiar el mundo con código', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(245, '35852801', 'Quiroga', 'Ruben MatÃ­as', 0, 2, 'g@gmail.com', '222', '222', NULL, 'La persistencia es tu mejor algoritmo', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(246, '43489624', 'Silva', 'David Nahuel', 0, 2, 'g@gmail.com', '222', '222', NULL, 'El primer paso es el más importante', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(247, '40332615', 'Sosa', 'JosÃ© Gabriel', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Casi listo para cambiar el mundo con código', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(248, '44062439', 'Suarez', 'Valeria Juana', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Tu código puede cambiar el mundo', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(249, '24234814', 'Torrente Ariza', 'Silvia Beatriz', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada bug resuelto es un paso adelante', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(250, '44665988', 'Vila MuÃ±oz', 'JosÃ© Gabriel', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2021', 1, 0, 1, '0000-00-00 00:00:00'),
(251, '42250353', 'Alaniz Escudero', 'Tamara Milagros', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Tu código puede cambiar el mundo', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(252, '45635092', 'Carrizo', 'Martin Fernando', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Sigue así, lo estás haciendo muy bien', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(253, '39425454', 'Coria Alaniz', 'Maximiliano Exequiel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'El conocimiento es tu mejor inversión', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(254, '40592489', 'Ferreyra ', 'Marcia Mariana', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'La persistencia es tu mejor algoritmo', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(255, '33759973', 'Garay', 'Mauro David', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'El primer paso es el más importante', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(256, '35510553', 'Garcia Diaz', 'Cecilia Valeria', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'La tecnología espera tus ideas', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(257, '43488625', 'Lucero Olivera', 'Ludmila Milagros', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada proyecto te hace más fuerte', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(258, '39425413', 'Ochoa', 'Melina Gisel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada proyecto te hace más fuerte', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(259, '38592327', 'Oro MuÃ±oz', 'Eric Andres', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'La persistencia es tu mejor algoritmo', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(260, '43221539', 'Rivero', 'Elisa Adriana', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'Sigue así, lo estás haciendo muy bien', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(261, '41321729', 'Yavante Martinez', 'Brian Nahuel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, 'El primer paso es el más importante', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(262, '37924455', 'Alvarez', 'Santiago David', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Ya recorriste la mitad del camino', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(263, '21666862', 'Andrada', 'Alberto Javier', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada bug resuelto es un paso adelante', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(264, '42805632', 'Carmona Paredes', 'Juan Gabriel', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Casi listo para cambiar el mundo con código', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(265, '24426668', 'Pacheco Almarcha', 'Lorena Liliana', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(266, '40939084', 'Riveros Arce', 'Aylen Constanza', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'Cada línea de código te acerca a tus sueños', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(267, '39995228', 'Videla', 'Emanuel Rodrigo', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, 'El conocimiento es tu mejor inversión', '2019', 1, 0, 1, '0000-00-00 00:00:00'),
(268, '41957789', 'IbaÃ±ez Sirerol', 'JosÃ© JuliÃ¡n', 0, 1, 'g@gmail.com', '222', '222', NULL, 'El aprendizaje constante es tu superpoder', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(269, '33846465', 'Molina Delgado', 'MarÃ­a NoemÃ­', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Aprende los fundamentos, dominarás el futuro', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(270, '42516940', 'MuÃ±oz Mercado', 'Ian Braian', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Tu código puede cambiar el mundo', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(271, '41531416', 'PeÃ±aloza', 'Micaela Liseth', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Cada gran desarrollador comenzó donde estás ahora', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(272, '29270300', 'Ramos', 'Ivana Graciela', 0, 1, 'g@gmail.com', '222', '222', NULL, 'Aprende los fundamentos, dominarás el futuro', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(273, '43952481', 'Sirerol Almarcha', 'Marco NicolÃ¡s', 0, 1, 'g@gmail.com', '222', '222', NULL, 'El primer paso es el más importante', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(274, '', '', '', 0, 0, '', '', '', NULL, NULL, '', 0, 0, 1, '0000-00-00 00:00:00'),
(275, '38216902', 'Cortez', 'Tatiana Erica', 0, 2, 'g@gmail.com', '22', '222', NULL, 'Aprende los fundamentos, dominarás el futuro', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(276, '42516951', 'Elizondo', 'Franco SebastiÃ¡n', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Cada proyecto te hace más fuerte', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(277, '42005554', 'Gomez Platero', 'Mauricio AndrÃ©s', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Tu título está más cerca que nunca', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(278, '42711754', 'Guaquinchay Moran', 'Kevin Antonio', 0, 2, 'g@gmail.com', '222', '222', NULL, 'El primer paso es el más importante', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(279, '42250342', 'Guzman Gomez', 'JuliÃ¡n Gabriel', 0, 2, 'g@gmail.com', '222', '222', NULL, 'La tecnología espera tus ideas', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(280, '42805636', 'Malla', 'Franco Nahuel', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Tu título está más cerca que nunca', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(281, '32653148', 'Morrone Espinosa', 'Pablo Javier', 0, 2, 'g@gmail.com', '222', '222', NULL, 'Sigue así, lo estás haciendo muy bien', '2020', 1, 0, 1, '0000-00-00 00:00:00'),
(282, '12345678', 'Alumno', 'Prueba', 2, 1, NULL, NULL, NULL, 'LEG-12345678', 'El conocimiento es tu mejor inversión', '2023', 1, 0, 1, '2025-10-01 14:23:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_alumnos_cursa`
--

CREATE TABLE `tbl_alumnos_cursa` (
  `id` int(11) NOT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `legajo` varchar(255) DEFAULT NULL,
  `anno` varchar(255) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `materia` int(11) NOT NULL DEFAULT 0,
  `turno` int(11) NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `curso` int(11) NOT NULL,
  `division` int(11) NOT NULL,
  `cursado` varchar(255) NOT NULL,
  `mesa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_alumnos_materias`
--

CREATE TABLE `tbl_alumnos_materias` (
  `Id` int(11) NOT NULL,
  `alumno` int(11) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `nota` varchar(255) DEFAULT NULL,
  `cursada` varchar(255) DEFAULT NULL,
  `rendida` varchar(255) DEFAULT NULL,
  `equivalencia` int(11) DEFAULT NULL,
  `libre` int(11) DEFAULT NULL,
  `libro` varchar(255) DEFAULT NULL,
  `folio` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `calificacion-cursada` varchar(255) DEFAULT NULL,
  `calificacion_rendida` varchar(255) DEFAULT NULL,
  `libro_corte` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_alumnos_materias`
--

INSERT INTO `tbl_alumnos_materias` (`Id`, `alumno`, `carrera`, `materia`, `nota`, `cursada`, `rendida`, `equivalencia`, `libre`, `libro`, `folio`, `fecha`, `calificacion-cursada`, `calificacion_rendida`, `libro_corte`) VALUES
(8, 159, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(9, 159, 1, 2, '6', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '1'),
(10, 159, 1, 3, '10', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(11, 159, 1, 4, '7', '1', '0', 0, 0, '4', '34', '2024-09-17', NULL, NULL, '1'),
(12, 159, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(13, 159, 1, 7, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(14, 159, 1, 9, '8', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(15, 159, 1, 10, '7', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(16, 159, 1, 11, '8', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1'),
(17, 162, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(18, 162, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(19, 162, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(20, 162, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(21, 162, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-13', NULL, NULL, '1'),
(22, 162, 1, 6, '7', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1'),
(23, 162, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(24, 162, 1, 8, '7', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1'),
(25, 162, 1, 9, '8', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(26, 162, 1, 10, '9', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(27, 162, 1, 11, '8', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1'),
(28, 163, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(29, 163, 1, 2, '4', '1', '0', 0, 0, '4', '20', '2024-07-30', NULL, NULL, '1'),
(30, 163, 1, 3, '8', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(31, 163, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(32, 163, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(33, 163, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1'),
(34, 163, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(35, 163, 1, 8, '9', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1'),
(36, 163, 1, 9, '10', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(37, 163, 1, 10, '8', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(38, 163, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1'),
(39, 164, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(40, 164, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(41, 164, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(42, 164, 1, 4, '9', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(43, 164, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(44, 164, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1'),
(45, 164, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(46, 164, 1, 8, '7', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1'),
(47, 164, 1, 9, '9', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(48, 164, 1, 10, '8', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(49, 164, 1, 11, '8', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1'),
(50, 165, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(51, 166, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(52, 166, 1, 3, '8', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(53, 167, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(54, 167, 1, 2, '4', '1', '0', 0, 0, '4', '20', '2024-07-30', NULL, NULL, '1'),
(55, 167, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(56, 167, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(57, 167, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(58, 167, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1'),
(59, 167, 1, 7, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(60, 167, 1, 8, '7', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1'),
(61, 167, 1, 9, '8', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(62, 167, 1, 10, '9', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(63, 167, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1'),
(64, 168, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(65, 168, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(66, 168, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(67, 169, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(68, 169, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(69, 169, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(70, 170, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(71, 170, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(72, 171, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(73, 171, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(74, 171, 1, 4, '8', '1', '0', 0, 0, '4', '34', '2024-09-17', NULL, NULL, '1'),
(75, 171, 1, 7, '9', '1', '0', 0, 0, '4', '31', '2024-08-14', NULL, NULL, '1'),
(76, 172, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(77, 172, 1, 2, '5', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '1'),
(78, 172, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(79, 172, 1, 4, '9', '1', '0', 0, 0, '4', '40', '2024-12-04', NULL, NULL, '1'),
(80, 172, 1, 5, '7', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(81, 172, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(82, 172, 1, 9, '10', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(83, 172, 1, 11, '8', '1', '0', 0, 0, '4', '49', '2024-12-18', NULL, NULL, '1'),
(84, 173, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(85, 173, 1, 4, '9', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(86, 173, 1, 5, '7', '0', '1', 0, 0, '5', '33', '2024-11-15', NULL, NULL, '1'),
(87, 173, 1, 7, '6', '1', '0', 0, 0, '4', '36', '2024-12-02', NULL, NULL, '1'),
(88, 173, 1, 9, '9', '1', '0', 0, 0, '5', '2', '2025-03-10', NULL, NULL, '1'),
(89, 173, 1, 11, '4', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1'),
(90, 174, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(91, 174, 1, 3, '10', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(92, 174, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(93, 174, 1, 5, '7', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(94, 174, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(95, 174, 1, 10, '8', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(96, 175, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(97, 175, 1, 2, '7', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(98, 175, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(99, 175, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(100, 175, 1, 5, '7', '0', '1', 0, 0, '5', '38', '2024-11-13', NULL, NULL, '1'),
(101, 175, 1, 6, '9', '1', '0', 0, 0, '4', '23', '2024-07-30', NULL, NULL, '1'),
(102, 175, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(103, 175, 1, 8, '8', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1'),
(104, 175, 1, 9, '9', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(105, 175, 1, 10, '10', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(106, 175, 1, 11, '7', '0', '0', 0, 1, '2', '39', '2024-12-18', NULL, NULL, '1'),
(107, 176, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(108, 176, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(109, 177, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(110, 177, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(111, 178, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(112, 178, 1, 2, '5', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '1'),
(113, 178, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(114, 178, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(115, 178, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(116, 178, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1'),
(117, 178, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(118, 178, 1, 8, '8', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1'),
(119, 178, 1, 9, '9', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1'),
(120, 178, 1, 10, '10', '0', '1', 0, 0, '5', '40', '2024-01-14', NULL, NULL, '1'),
(121, 178, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1'),
(122, 180, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(123, 180, 1, 2, '6', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(124, 180, 1, 3, '8', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(125, 180, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1'),
(126, 180, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1'),
(127, 180, 1, 6, '8', '0', '1', 0, 0, '5', '23', '2024-06-28', NULL, NULL, '1'),
(128, 180, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1'),
(129, 180, 1, 8, '9', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1'),
(130, 180, 1, 9, '9', '1', '0', 0, 0, '4', '47', '2024-12-17', NULL, NULL, '1'),
(131, 180, 1, 10, '10', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1'),
(132, 180, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1'),
(133, 181, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1'),
(134, 181, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1'),
(135, 182, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(136, 182, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(137, 182, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(138, 182, 1, 5, '10', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(139, 182, 1, 6, '7', '1', '0', 0, 0, '4', '51', '2024-12-20', NULL, NULL, '1'),
(140, 182, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(141, 182, 1, 8, '6', '1', '0', 0, 0, '5', '5', '2025-03-21', NULL, NULL, '1'),
(142, 182, 1, 10, '10', '0', '1', 0, 0, '5', '35', '2024-11-14', NULL, NULL, '1'),
(143, 182, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(144, 183, 1, 1, '10', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(145, 183, 1, 2, '9', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(146, 183, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(147, 183, 1, 4, '10', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(148, 183, 1, 5, '9', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(149, 183, 1, 6, '9', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1'),
(150, 183, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(151, 183, 1, 8, '7', '0', '1', 0, 0, '5', '32', '2024-11-11', NULL, NULL, '1'),
(152, 183, 1, 9, '9', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '1'),
(153, 183, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1'),
(154, 183, 1, 11, '9', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(155, 184, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(156, 184, 1, 2, '4', '1', '0', 0, 0, '4', '35', '2024-09-20', NULL, NULL, '1'),
(157, 184, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(158, 184, 1, 4, '7', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(159, 184, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(160, 184, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1'),
(161, 184, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(162, 184, 1, 8, '6', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1'),
(163, 184, 1, 9, '9', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '1'),
(164, 184, 1, 10, '8', '0', '1', 0, 0, '5', '35', '2024-11-14', NULL, NULL, '1'),
(165, 184, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(166, 185, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(167, 186, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(168, 186, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(169, 186, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(170, 186, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(171, 186, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1'),
(172, 186, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(173, 186, 1, 10, '7', '0', '1', 0, 0, '5', '35', '2024-11-14', NULL, NULL, '1'),
(174, 186, 1, 11, '9', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(175, 187, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(176, 187, 1, 3, '7', '0', '0', 0, 1, '2', '34', '2024-12-03', NULL, NULL, '1'),
(177, 187, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(178, 187, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(179, 188, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(180, 188, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(181, 188, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(182, 188, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(183, 188, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(184, 189, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(185, 189, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(186, 189, 1, 3, '9', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(187, 189, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(188, 189, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(189, 189, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1'),
(190, 189, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(191, 189, 1, 9, '8', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '1'),
(192, 189, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(193, 190, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(194, 190, 1, 3, '7', '0', '0', 0, 1, '2', '34', '2024-12-03', NULL, NULL, '1'),
(195, 190, 1, 4, '10', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(196, 190, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(197, 190, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(198, 191, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(199, 191, 1, 5, '9', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(200, 191, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(201, 191, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(202, 192, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(203, 192, 1, 3, '10', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(204, 192, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(205, 192, 1, 5, '9', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(206, 192, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1'),
(207, 192, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(208, 192, 1, 10, '7', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1'),
(209, 192, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(210, 193, 1, 1, '7', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(211, 193, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(212, 193, 1, 4, '7', '1', '0', 0, 0, '4', '32', '2024-08-14', NULL, NULL, '1'),
(213, 193, 1, 5, '7', '1', '0', 0, 0, '4', '43', '2024-12-06', NULL, NULL, '1'),
(214, 193, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(215, 193, 1, 10, '8', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1'),
(216, 193, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(217, 194, 1, 1, '10', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(218, 194, 1, 2, '7', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(219, 194, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(220, 194, 1, 4, '10', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(221, 194, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(222, 194, 1, 6, '9', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1'),
(223, 194, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(224, 194, 1, 8, '8', '0', '1', 0, 0, '5', '32', '2024-11-11', NULL, NULL, '1'),
(225, 194, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1'),
(226, 194, 1, 11, '9', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(227, 195, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(228, 195, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(229, 195, 1, 4, '8', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(230, 195, 1, 5, '10', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(231, 195, 1, 6, '8', '1', '0', 0, 0, '4', '27', '2024-08-12', NULL, NULL, '1'),
(232, 195, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(233, 195, 1, 8, '7', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1'),
(234, 195, 1, 10, '9', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1'),
(235, 195, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(236, 196, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(237, 196, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(238, 196, 1, 4, '8', '1', '0', 0, 0, '4', '32', '2024-08-14', NULL, NULL, '1'),
(239, 196, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(240, 196, 1, 6, '4', '1', '0', 0, 0, '4', '27', '2024-08-12', NULL, NULL, '1'),
(241, 196, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-06-01', NULL, NULL, '1'),
(242, 197, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(243, 197, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1'),
(244, 197, 1, 3, '9', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1'),
(245, 197, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(246, 197, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(247, 197, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(248, 197, 1, 10, '7', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1'),
(249, 197, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(250, 198, 1, 1, '6', '1', '0', 0, 0, '4', '29', '2024-08-13', NULL, NULL, '1'),
(251, 198, 1, 2, '4', '1', '0', 0, 0, '4', '35', '2024-09-20', NULL, NULL, '1'),
(252, 198, 1, 7, '8', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(253, 199, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1'),
(254, 199, 1, 4, '7', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(255, 199, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1'),
(256, 200, 1, 1, '8', '1', '0', 0, 0, '4', '21', '2024-07-30', NULL, NULL, '1'),
(257, 200, 1, 4, '7', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1'),
(258, 200, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1'),
(259, 200, 1, 6, '5', '1', '0', 0, 0, '4', '23', '2024-07-30', NULL, NULL, '1'),
(260, 200, 1, 8, '6', '1', '0', 0, 0, '4', '52', '2024-12-20', NULL, NULL, '1'),
(261, 200, 1, 10, '9', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1'),
(262, 200, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1'),
(263, 6, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(264, 6, 1, 2, '5', '0', '1', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(265, 6, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(266, 6, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(267, 6, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(268, 6, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(269, 6, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(270, 6, 1, 8, '8', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2'),
(271, 6, 1, 9, '8', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2'),
(272, 6, 1, 10, '10', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(273, 6, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(274, 10, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-08-22', NULL, NULL, '2'),
(275, 10, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(276, 10, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(277, 10, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(278, 10, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(279, 90, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(280, 90, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(281, 90, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(282, 90, 1, 6, '4', '1', '0', 0, 0, '3', '32', '2023-08-01', NULL, NULL, '2'),
(283, 90, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(284, 96, 1, 1, '7', '0', '1', 0, 0, '3', '4', '2023-06-28', NULL, NULL, '2'),
(285, 96, 1, 2, '7', '1', '0', 0, 0, '4', '8', '2023-12-20', NULL, NULL, '2'),
(286, 96, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(287, 96, 1, 5, '8', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(288, 96, 1, 6, '6', '1', '0', 0, 0, '3', '32', '2023-08-01', NULL, NULL, '2'),
(289, 96, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(290, 96, 1, 8, '7', '1', '0', 0, 0, '4', '22', '2024-07-30', NULL, NULL, '2'),
(291, 96, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '2'),
(292, 96, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(293, 96, 1, 12, '7', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2'),
(294, 96, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2'),
(295, 96, 1, 15, '9', '1', '0', 0, 0, '2', '49', '2024-08-13', NULL, NULL, '2'),
(296, 96, 1, 16, '8', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(297, 96, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '2'),
(298, 96, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(299, 96, 1, 20, '7', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2'),
(300, 4, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(301, 4, 1, 2, '7', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(302, 4, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(303, 4, 1, 4, '9', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(304, 4, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(305, 4, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(306, 4, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(307, 4, 1, 8, '9', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2'),
(308, 4, 1, 10, '10', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(309, 4, 1, 11, '9', '0', '1', 0, 0, '5', '10', '2023-11-15', NULL, NULL, '2'),
(310, 4, 1, 12, '10', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2'),
(311, 4, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2'),
(312, 4, 1, 14, '7', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2'),
(313, 4, 1, 15, '10', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2'),
(314, 4, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(315, 4, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2'),
(316, 4, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(317, 4, 1, 20, '10', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2'),
(318, 37, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(319, 37, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(320, 37, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(321, 37, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(322, 37, 1, 6, '4', '1', '0', 0, 0, '3', '40', '2023-09-25', NULL, NULL, '2'),
(323, 37, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(324, 37, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(325, 202, 1, 1, '8', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(326, 202, 1, 2, '10', '1', '0', 0, 0, '4', '13', '2023-03-06', NULL, NULL, '2'),
(327, 202, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(328, 202, 1, 4, '9', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(329, 202, 1, 5, '10', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(330, 202, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(331, 202, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(332, 202, 1, 8, '9', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2'),
(333, 202, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '2'),
(334, 202, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(335, 202, 1, 12, '9', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2'),
(336, 202, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2'),
(337, 202, 1, 15, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2'),
(338, 202, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(339, 202, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '2'),
(340, 202, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(341, 202, 1, 20, '9', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2'),
(382, 24, 1, 1, '10', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(383, 24, 1, 2, '4', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(384, 24, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(385, 24, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(386, 24, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(387, 24, 1, 6, '9', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(388, 24, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2'),
(389, 24, 1, 8, '8', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(390, 24, 1, 9, '8', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(391, 24, 1, 10, '10', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(392, 24, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(393, 24, 1, 12, '9', '0', '1', 0, 0, '5', '18', '2024-04-26', NULL, NULL, '2'),
(394, 24, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(395, 24, 1, 14, '9', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2'),
(396, 24, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2'),
(397, 24, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(398, 24, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(399, 24, 1, 18, '9', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(400, 24, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(401, 24, 1, 20, '9', '1', '0', 0, 0, '3', '2', '2024-09-18', NULL, NULL, '2'),
(402, 24, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(403, 9, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(404, 9, 1, 2, '5', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '2'),
(405, 9, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(406, 9, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(407, 9, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(408, 9, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(409, 9, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(410, 9, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(411, 204, 1, 1, '8', '1', '0', 0, 0, '4', '2', '2023-12-06', NULL, NULL, '2'),
(412, 204, 1, 2, '4', '1', '0', 0, 0, '3', '36', '0000-00-00', NULL, NULL, '2'),
(413, 204, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(414, 204, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(415, 204, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(416, 204, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(417, 204, 1, 7, '9', '1', '0', 0, 0, '4', '7', '2023-12-19', NULL, NULL, '2'),
(418, 204, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(419, 204, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(420, 204, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(421, 204, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(422, 204, 1, 16, '10', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(423, 204, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(424, 92, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(425, 92, 1, 2, '5', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2'),
(426, 92, 1, 3, '4', '0', '0', 0, 1, '2', '10', '2023-09-26', NULL, NULL, '2'),
(427, 92, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(428, 92, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(429, 92, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(430, 92, 1, 7, '7', '1', '0', 0, 0, '4', '7', '2023-12-19', NULL, NULL, '2'),
(431, 92, 1, 8, '4', '0', '0', 0, 1, '2', '21', '2023-12-21', NULL, NULL, '2'),
(432, 92, 1, 10, '7', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(433, 92, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(434, 92, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(435, 92, 1, 13, '8', '0', '0', 0, 1, '2', '40', '2024-12-18', NULL, NULL, '2'),
(436, 92, 1, 15, '9', '1', '0', 0, 0, '3', '7', '2024-12-06', NULL, NULL, '2'),
(437, 92, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(438, 92, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(439, 92, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(440, 92, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2'),
(441, 92, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(442, 16, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(443, 16, 1, 2, '4', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(444, 16, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(445, 16, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(446, 16, 1, 5, '8', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(447, 16, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(448, 16, 1, 7, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2'),
(449, 16, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(450, 16, 1, 9, '7', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(451, 16, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(452, 16, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(453, 16, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(454, 16, 1, 14, '10', '0', '1', 0, 0, '5', '29', '2024-11-11', NULL, NULL, '2'),
(455, 16, 1, 15, '9', '1', '0', 0, 0, '2', '49', '2024-08-12', NULL, NULL, '2'),
(456, 16, 1, 16, '10', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(457, 16, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(458, 16, 1, 18, '7', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(459, 16, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(460, 16, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2'),
(461, 16, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(462, 205, 1, 1, '6', '0', '0', 0, 1, '2', '14', '2023-12-06', NULL, NULL, '2'),
(463, 205, 1, 2, '8', '0', '0', 0, 1, '2', '19', '2023-12-20', NULL, NULL, '2'),
(464, 205, 1, 3, '5', '0', '0', 0, 1, '2', '10', '2023-09-26', NULL, NULL, '2'),
(465, 205, 1, 4, '9', '0', '0', 0, 1, '2', '12', '2023-12-04', NULL, NULL, '2'),
(466, 205, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(467, 205, 1, 6, '4', '0', '0', 0, 1, '2', '13', '2023-12-05', NULL, NULL, '2'),
(468, 205, 1, 7, '9', '0', '0', 0, 1, '2', '4', '2023-08-16', NULL, NULL, '2'),
(469, 205, 1, 8, '6', '0', '0', 0, 1, '2', '27', '2024-03-21', NULL, NULL, '2'),
(470, 205, 1, 9, '9', '1', '0', 0, 0, '5', '49', '2023-12-05', NULL, NULL, '2'),
(471, 205, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(472, 205, 1, 11, '7', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(473, 205, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(474, 205, 1, 13, '7', '0', '0', 0, 1, '2', '29', '2024-07-31', NULL, NULL, '2'),
(475, 205, 1, 14, '10', '0', '1', 0, 0, '5', '29', '2024-11-11', NULL, NULL, '2'),
(476, 205, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2'),
(477, 205, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(478, 205, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(479, 205, 1, 18, '7', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(480, 205, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(481, 205, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2'),
(482, 205, 1, 21, '10', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(483, 28, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(484, 28, 1, 2, '6', '0', '0', 0, 1, '2', '2', '2023-09-26', NULL, NULL, '2'),
(485, 28, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(486, 28, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(487, 28, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(488, 28, 1, 6, '8', '0', '0', 0, 1, '1', '53', '2023-08-01', NULL, NULL, '2'),
(489, 28, 1, 7, '6', '1', '0', 0, 0, '3', '39', '2023-08-16', NULL, NULL, '2'),
(490, 28, 1, 8, '4', '0', '0', 0, 1, '2', '42', '2024-12-20', NULL, NULL, '2'),
(491, 28, 1, 9, '8', '1', '0', 0, 0, '4', '6', '2023-12-19', NULL, NULL, '2'),
(492, 28, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(493, 28, 1, 11, '7', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(494, 28, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(495, 28, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(496, 28, 1, 14, '7', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2'),
(497, 28, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(498, 28, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(499, 28, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(500, 28, 1, 20, '5', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2'),
(501, 89, 1, 1, '7', '1', '0', 0, 0, '3', '38', '2023-08-16', NULL, NULL, '2'),
(502, 89, 1, 2, '4', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2'),
(503, 89, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(504, 89, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(505, 89, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(506, 89, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2'),
(507, 89, 1, 13, '6', '0', '0', 0, 1, '2', '33', '2024-09-20', NULL, NULL, '2'),
(508, 89, 1, 16, '7', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(509, 89, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(510, 23, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(511, 23, 1, 2, '5', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(512, 23, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(513, 23, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(514, 23, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(515, 23, 1, 6, '9', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(516, 23, 1, 7, '9', '1', '0', 0, 0, '4', '7', '2023-12-19', NULL, NULL, '2'),
(517, 23, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(518, 23, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(519, 23, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(520, 23, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(521, 23, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(522, 23, 1, 13, '9', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(523, 23, 1, 15, '10', '1', '0', 0, 0, '2', '49', '2024-08-13', NULL, NULL, '2'),
(524, 23, 1, 16, '10', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(525, 23, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(526, 23, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(527, 23, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(528, 23, 1, 20, '9', '1', '0', 0, 0, '3', '15', '2025-03-20', NULL, NULL, '2'),
(529, 23, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(530, 30, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(531, 30, 1, 2, '5', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(532, 30, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(533, 30, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(534, 30, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(535, 30, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(536, 30, 1, 7, '10', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2'),
(537, 30, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(538, 30, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(539, 30, 1, 10, '10', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(540, 30, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(541, 30, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(542, 30, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(543, 30, 1, 14, '10', '1', '0', 0, 0, '3', '9', '2024-12-17', NULL, NULL, '2'),
(544, 30, 1, 15, '8', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2'),
(545, 30, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(546, 30, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(547, 30, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(548, 30, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(549, 30, 1, 20, '9', '1', '0', 0, 0, '2', '31', '2024-08-15', NULL, NULL, '2'),
(550, 30, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(551, 12, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(552, 12, 1, 2, '9', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(553, 12, 1, 3, '4', '0', '0', 0, 1, '2', '26', '2024-03-19', NULL, NULL, '2'),
(554, 12, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(555, 12, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(556, 12, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(557, 12, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2'),
(558, 12, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(559, 12, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(560, 12, 1, 10, '10', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(561, 12, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(562, 12, 1, 12, '9', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(563, 12, 1, 13, '10', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(564, 12, 1, 14, '10', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2'),
(565, 12, 1, 15, '8', '1', '0', 0, 0, '2', '45', '2024-07-30', NULL, NULL, '2'),
(566, 12, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(567, 12, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(568, 12, 1, 18, '9', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(569, 12, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(570, 12, 1, 20, '8', '1', '0', 0, 0, '2', '51', '2024-08-15', NULL, NULL, '2'),
(571, 12, 1, 21, '8', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(572, 7, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(573, 7, 1, 2, '4', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2'),
(574, 7, 1, 3, '9', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(575, 7, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(576, 7, 1, 5, '8', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(577, 7, 1, 6, '5', '1', '0', 0, 0, '3', '37', '2023-08-15', NULL, NULL, '2'),
(578, 7, 1, 7, '10', '1', '0', 0, 0, '3', '35', '2023-08-03', NULL, NULL, '2'),
(579, 7, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(580, 7, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(581, 7, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(582, 7, 1, 11, '7', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(583, 7, 1, 12, '8', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(584, 7, 1, 13, '8', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(585, 7, 1, 14, '7', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2'),
(586, 7, 1, 15, '9', '0', '0', 0, 1, '2', '30', '2024-08-13', NULL, NULL, '2'),
(587, 7, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(588, 7, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(589, 7, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(590, 7, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(591, 25, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(592, 25, 1, 2, '8', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2'),
(593, 25, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(594, 25, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(595, 25, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(596, 25, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(597, 25, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2'),
(598, 25, 1, 8, '10', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2'),
(599, 25, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(600, 25, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(601, 25, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(602, 25, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(603, 25, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2'),
(604, 25, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(605, 25, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(606, 25, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(607, 25, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(608, 25, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2'),
(609, 25, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(610, 33, 1, 1, '10', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(611, 33, 1, 2, '8', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(612, 33, 1, 3, '8', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(613, 33, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(614, 33, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(615, 33, 1, 6, '9', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2'),
(616, 33, 1, 7, '10', '1', '0', 0, 0, '3', '39', '2023-08-16', NULL, NULL, '2'),
(617, 33, 1, 9, '10', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(618, 33, 1, 10, '10', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(619, 33, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2'),
(620, 86, 1, 1, '8', '1', '0', 0, 0, '4', '9', '2023-12-20', NULL, NULL, '2'),
(621, 86, 1, 2, '4', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(622, 86, 1, 3, '7', '0', '0', 0, 1, '2', '10', '2023-09-26', NULL, NULL, '2'),
(623, 86, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(624, 86, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(625, 86, 1, 6, '4', '0', '0', 0, 1, '2', '41', '2024-12-20', NULL, NULL, '2'),
(626, 86, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2'),
(627, 86, 1, 10, '8', '0', '0', 1, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(628, 86, 1, 11, '6', '1', '0', 0, 0, '4', '5', '2023-12-18', NULL, NULL, '2'),
(629, 86, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(630, 86, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2'),
(631, 86, 1, 14, '8', '1', '0', 0, 0, '3', '9', '2024-12-17', NULL, NULL, '2'),
(632, 86, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(633, 86, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(634, 86, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(635, 31, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2'),
(636, 31, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(637, 31, 1, 6, '7', '0', '0', 0, 1, '2', '2', '2023-08-15', NULL, NULL, '2'),
(638, 18, 1, 1, '7', '1', '0', 0, 0, '3', '34', '2023-08-02', NULL, NULL, '2'),
(639, 18, 1, 2, '8', '0', '0', 0, 1, '2', '16', '2023-12-06', NULL, NULL, '2'),
(640, 18, 1, 4, '8', '1', '0', 0, 0, '3', '43', '2023-09-25', NULL, NULL, '2'),
(641, 18, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(642, 18, 1, 7, '4', '1', '0', 0, 0, '3', '35', '2023-08-03', NULL, NULL, '2'),
(643, 18, 1, 9, '8', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2'),
(644, 18, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(645, 18, 1, 11, '7', '1', '0', 0, 0, '4', '16', '2024-03-18', NULL, NULL, '2'),
(646, 18, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(647, 18, 1, 14, '7', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2'),
(648, 18, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(649, 206, 1, 1, '6', '0', '0', 0, 1, '2', '14', '2023-12-06', NULL, NULL, '2'),
(650, 206, 1, 2, '9', '0', '0', 0, 1, '2', '19', '2023-12-20', NULL, NULL, '2'),
(651, 206, 1, 4, '7', '0', '0', 0, 1, '2', '12', '2023-12-04', NULL, NULL, '2'),
(652, 206, 1, 5, '8', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2'),
(653, 206, 1, 6, '6', '0', '0', 0, 1, '2', '13', '2023-12-05', NULL, NULL, '2'),
(654, 206, 1, 7, '7', '0', '0', 0, 1, '2', '28', '2024-07-31', NULL, NULL, '2'),
(655, 206, 1, 8, '5', '0', '0', 0, 1, '2', '27', '2024-03-21', NULL, NULL, '2'),
(656, 206, 1, 9, '10', '1', '0', 0, 0, '4', '6', '2023-12-19', NULL, NULL, '2'),
(657, 206, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2'),
(658, 206, 1, 11, '6', '1', '0', 0, 0, '3', '45', '2023-12-04', NULL, NULL, '2'),
(659, 206, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2'),
(660, 206, 1, 13, '7', '0', '0', 0, 1, '2', '29', '2024-07-31', NULL, NULL, '2'),
(661, 206, 1, 14, '9', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2'),
(662, 206, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2'),
(663, 206, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2'),
(664, 206, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2'),
(665, 206, 1, 18, '7', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2'),
(666, 206, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2'),
(667, 206, 1, 20, '9', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2'),
(668, 206, 1, 21, '10', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2'),
(669, 26, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(670, 26, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(671, 207, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(672, 207, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(677, 208, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2'),
(678, 209, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2'),
(679, 22, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(680, 22, 1, 2, '8', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(681, 22, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(682, 22, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(683, 22, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(684, 22, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` (`Id`, `alumno`, `carrera`, `materia`, `nota`, `cursada`, `rendida`, `equivalencia`, `libre`, `libro`, `folio`, `fecha`, `calificacion-cursada`, `calificacion_rendida`, `libro_corte`) VALUES
(685, 19, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(686, 19, 1, 2, '5', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(687, 19, 1, 3, '10', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(688, 19, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(689, 19, 1, 5, '10', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(690, 19, 1, 6, '7', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(691, 19, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(692, 19, 1, 8, '7', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2'),
(693, 19, 1, 9, '8', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2'),
(694, 19, 1, 10, '10', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(695, 19, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(696, 19, 1, 12, '9', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2'),
(697, 19, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2'),
(698, 19, 1, 14, '7', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2'),
(699, 19, 1, 15, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2'),
(700, 19, 1, 16, '8', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(701, 19, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2'),
(702, 19, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '2'),
(703, 19, 1, 19, '9', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(704, 19, 1, 20, '9', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2'),
(705, 19, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2'),
(706, 94, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(707, 94, 1, 2, '8', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2'),
(708, 94, 1, 3, '10', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(709, 94, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(710, 94, 1, 6, '10', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(711, 94, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(712, 17, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(713, 17, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(714, 17, 1, 5, '8', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(715, 17, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(716, 17, 1, 10, '7', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(717, 17, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(718, 17, 1, 16, '7', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(719, 17, 1, 17, '7', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2'),
(720, 17, 1, 19, '8', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(721, 41, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(722, 40, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(723, 40, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(724, 73, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(725, 73, 1, 3, '6', '0', '0', 0, 1, '2', '26', '2024-03-19', NULL, NULL, '2'),
(726, 73, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(727, 73, 1, 5, '10', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(728, 73, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(729, 73, 1, 8, '8', '1', '0', 0, 0, '5', '1', '2025-03-07', NULL, NULL, '2'),
(730, 73, 1, 9, '9', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '2'),
(731, 73, 1, 10, '8', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '2'),
(732, 73, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(733, 5, 1, 1, '9', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(734, 5, 1, 2, '7', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(735, 5, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(736, 5, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(737, 5, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(738, 5, 1, 6, '8', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2'),
(739, 5, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(740, 5, 1, 8, '7', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2'),
(741, 5, 1, 9, '8', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2'),
(742, 5, 1, 10, '8', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(743, 5, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(744, 5, 1, 12, '10', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2'),
(745, 5, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2'),
(746, 5, 1, 14, '8', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2'),
(747, 5, 1, 15, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2'),
(748, 5, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(749, 5, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2'),
(750, 5, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '2'),
(751, 5, 1, 19, '9', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(752, 5, 1, 20, '8', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2'),
(753, 5, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2'),
(754, 11, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(755, 11, 1, 2, '6', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2'),
(756, 11, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(757, 11, 1, 4, '9', '1', '0', 0, 0, '5', '46', '2023-12-04', NULL, NULL, '2'),
(758, 11, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(759, 11, 1, 6, '4', '1', '0', 0, 0, '3', '37', '2023-08-15', NULL, NULL, '2'),
(760, 11, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(761, 11, 1, 10, '9', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(762, 11, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(763, 32, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(764, 32, 1, 2, '6', '1', '0', 0, 0, '4', '8', '2023-12-20', NULL, NULL, '2'),
(765, 32, 1, 3, '10', '0', '0', 0, 1, '2', '23', '2024-03-06', NULL, NULL, '2'),
(766, 32, 1, 4, '7', '1', '0', 0, 0, '3', '43', '2023-09-25', NULL, NULL, '2'),
(767, 32, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(768, 32, 1, 6, '4', '1', '0', 0, 0, '4', '1', '2023-12-05', NULL, NULL, '2'),
(769, 32, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(770, 32, 1, 8, '4', '1', '0', 0, 0, '4', '4', '2023-12-07', NULL, NULL, '2'),
(771, 32, 1, 9, '8', '0', '1', 0, 0, '5', '10', '2023-11-21', NULL, NULL, '2'),
(772, 32, 1, 10, '8', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(773, 32, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(774, 32, 1, 12, '8', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2'),
(775, 32, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2'),
(776, 32, 1, 15, '9', '1', '0', 0, 0, '2', '49', '2024-08-13', NULL, NULL, '2'),
(777, 32, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(778, 32, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2'),
(779, 32, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '2'),
(780, 32, 1, 19, '9', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(781, 32, 1, 20, '9', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2'),
(782, 32, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2'),
(783, 38, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(784, 38, 1, 2, '4', '1', '0', 0, 0, '5', '31', '2023-08-01', NULL, NULL, '2'),
(785, 38, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2'),
(786, 38, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(787, 38, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2'),
(788, 38, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2'),
(789, 38, 1, 9, '7', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2'),
(790, 38, 1, 10, '8', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2'),
(791, 38, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2'),
(792, 38, 1, 14, '7', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2'),
(793, 38, 1, 16, '7', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2'),
(794, 38, 1, 17, '8', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2'),
(795, 38, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2'),
(796, 38, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2'),
(803, 203, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(804, 203, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2'),
(805, 203, 1, 5, '8', '0', '1', 0, 0, '5', '11', '2023-12-09', NULL, NULL, '2'),
(806, 74, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2'),
(807, 74, 1, 2, '4', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2'),
(808, 74, 1, 7, '8', '1', '0', 0, 0, '3', '35', '2023-08-03', NULL, NULL, '2'),
(809, 210, 1, 1, '8', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(810, 210, 1, 3, '9', '0', '1', 0, 0, '4', '10', '2022-07-04', NULL, NULL, '3'),
(811, 210, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(812, 210, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(813, 210, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(814, 211, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(815, 211, 1, 3, '8', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(816, 211, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(817, 211, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(818, 211, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(819, 212, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(820, 212, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(821, 213, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(822, 213, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(823, 213, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(824, 213, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(825, 214, 1, 2, '9', '0', '1', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3'),
(826, 214, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '3'),
(827, 214, 1, 4, '9', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(828, 214, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '3'),
(829, 214, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '3'),
(830, 214, 1, 8, '4', '1', '0', 0, 0, '4', '19', '2024-03-21', NULL, NULL, '3'),
(831, 214, 1, 9, '7', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '3'),
(832, 214, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '3'),
(844, 215, 1, 1, '7', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3'),
(845, 215, 1, 4, '8', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(846, 1, 1, 1, '8', '0', '1', 0, 0, '4', '1', '2022-06-27', NULL, NULL, '3'),
(847, 1, 1, 2, '9', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(848, 1, 1, 3, '9', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(849, 1, 1, 4, '9', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(850, 1, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(851, 1, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(852, 1, 1, 7, '10', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(853, 1, 1, 8, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3'),
(854, 1, 1, 9, '10', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(855, 1, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(856, 1, 1, 11, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3'),
(857, 1, 1, 12, '9', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3'),
(858, 1, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '3'),
(859, 1, 1, 14, '10', '0', '1', 0, 0, '5', '10', '2023-11-13', NULL, NULL, '3'),
(860, 1, 1, 16, '9', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3'),
(861, 1, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '3'),
(862, 1, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3'),
(863, 1, 1, 19, '9', '0', '1', 0, 0, '5', '13', '2023-11-29', NULL, NULL, '3'),
(864, 1, 1, 20, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3'),
(865, 1, 1, 21, '9', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '3'),
(866, 42, 1, 1, '8', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3'),
(867, 42, 1, 2, '6', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(868, 42, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-01', NULL, NULL, '3'),
(869, 42, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(870, 42, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3'),
(871, 42, 1, 6, '8', '0', '1', 0, 0, '4', '7', '2022-08-29', NULL, NULL, '3'),
(872, 42, 1, 7, '6', '1', '0', 0, 0, '3', '12', '2022-08-05', NULL, NULL, '3'),
(873, 42, 1, 8, '10', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3'),
(874, 42, 1, 9, '8', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3'),
(875, 42, 1, 10, '9', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(876, 42, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3'),
(877, 42, 1, 12, '7', '0', '1', 0, 0, '5', '3', '2023-06-29', NULL, NULL, '3'),
(878, 42, 1, 15, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '3'),
(879, 42, 1, 16, '9', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3'),
(880, 42, 1, 17, '9', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '3'),
(881, 42, 1, 19, '7', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '3'),
(882, 42, 1, 20, '9', '1', '0', 0, 0, '2', '28', '2023-08-18', NULL, NULL, '3'),
(883, 42, 1, 21, '9', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '3'),
(884, 42, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2024-11-11', NULL, NULL, '3'),
(885, 42, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3'),
(886, 42, 1, 25, '7', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3'),
(919, 76, 1, 1, '7', '0', '1', 0, 0, '4', '1', '2022-06-27', NULL, NULL, '3'),
(920, 76, 1, 2, '8', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(921, 76, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(922, 76, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(923, 76, 1, 5, '9', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(924, 76, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(925, 76, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(926, 76, 1, 8, '7', '0', '1', 0, 0, '3', '30', '2023-03-21', NULL, NULL, '3'),
(927, 76, 1, 9, '7', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(928, 76, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(929, 76, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(930, 76, 1, 12, '7', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3'),
(931, 76, 1, 13, '7', '0', '0', 0, 1, '2', '15', '2023-12-06', NULL, NULL, '3'),
(932, 76, 1, 14, '8', '1', '0', 0, 0, '2', '48', '2024-08-12', NULL, NULL, '3'),
(933, 76, 1, 15, '10', '1', '0', 0, 0, '2', '37', '2023-12-22', NULL, NULL, '3'),
(934, 76, 1, 16, '7', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3'),
(935, 76, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3'),
(936, 76, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '3'),
(937, 76, 1, 19, '8', '1', '0', 0, 0, '2', '35', '2024-03-06', NULL, NULL, '3'),
(938, 76, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3'),
(939, 76, 1, 21, '8', '0', '1', 0, 0, '5', '14', '2023-11-29', NULL, NULL, '3'),
(940, 76, 1, 22, '7', '0', '0', 0, 1, '2', '37', '2024-12-18', NULL, NULL, '3'),
(941, 76, 1, 23, '9', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3'),
(942, 76, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3'),
(943, 76, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2025-07-01', NULL, NULL, '3'),
(944, 76, 1, 26, '5', '0', '0', 0, 1, '2', '45', '2025-03-19', NULL, NULL, '3'),
(945, 76, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '3'),
(946, 20, 1, 1, '7', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3'),
(947, 20, 1, 2, '7', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3'),
(948, 20, 1, 3, '9', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3'),
(949, 20, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(950, 20, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3'),
(951, 20, 1, 6, '9', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3'),
(952, 20, 1, 8, '8', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3'),
(953, 20, 1, 9, '8', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3'),
(954, 20, 1, 10, '10', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3'),
(955, 20, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3'),
(956, 20, 1, 12, '8', '0', '1', 0, 0, '5', '3', '2023-06-29', NULL, NULL, '3'),
(957, 20, 1, 13, '10', '0', '1', 0, 0, '5', '1', '2023-06-28', NULL, NULL, '3'),
(958, 20, 1, 14, '4', '1', '0', 0, 0, '2', '33', '2023-12-19', NULL, NULL, '3'),
(959, 20, 1, 15, '9', '1', '0', 0, 0, '3', '7', '2024-12-06', NULL, NULL, '3'),
(960, 20, 1, 16, '9', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3'),
(961, 20, 1, 17, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '3'),
(962, 20, 1, 18, '9', '1', '0', 0, 0, '2', '30', '2023-12-06', NULL, NULL, '3'),
(963, 20, 1, 19, '9', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '3'),
(964, 20, 1, 20, '8', '1', '0', 0, 0, '2', '28', '2023-08-18', NULL, NULL, '3'),
(965, 20, 1, 21, '9', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '3'),
(966, 20, 1, 22, '10', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3'),
(967, 20, 1, 23, '10', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3'),
(968, 20, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3'),
(969, 20, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3'),
(970, 20, 1, 27, '8', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '3'),
(971, 216, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(972, 216, 1, 2, '4', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3'),
(973, 216, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(974, 216, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(975, 216, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(976, 36, 1, 1, '8', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3'),
(977, 36, 1, 2, '9', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(978, 36, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3'),
(979, 36, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(980, 36, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3'),
(981, 36, 1, 6, '9', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3'),
(982, 36, 1, 7, '8', '0', '1', 0, 0, '4', '5', '2022-06-29', NULL, NULL, '3'),
(983, 36, 1, 8, '8', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3'),
(984, 36, 1, 9, '9', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3'),
(985, 36, 1, 10, '10', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3'),
(986, 36, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3'),
(987, 36, 1, 12, '7', '0', '1', 0, 0, '5', '3', '2023-06-29', NULL, NULL, '3'),
(988, 36, 1, 16, '8', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3'),
(989, 36, 1, 17, '9', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '3'),
(1001, 217, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3'),
(1002, 217, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(1003, 217, 1, 6, '8', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3'),
(1024, 70, 1, 1, '10', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1025, 70, 1, 2, '7', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(1026, 70, 1, 3, '9', '0', '1', 0, 0, '4', '10', '2022-07-04', NULL, NULL, '3'),
(1027, 70, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1028, 70, 1, 5, '9', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1029, 70, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-19', NULL, NULL, '3'),
(1030, 70, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1031, 70, 1, 8, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3'),
(1032, 70, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-14', NULL, NULL, '3'),
(1033, 70, 1, 10, '9', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1034, 70, 1, 11, '9', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1035, 70, 1, 12, '10', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3'),
(1036, 70, 1, 13, '10', '1', '0', 0, 0, '2', '20', '2023-08-01', NULL, NULL, '3'),
(1037, 70, 1, 14, '9', '0', '1', 0, 0, '5', '10', '2023-11-13', NULL, NULL, '3'),
(1038, 70, 1, 16, '9', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3'),
(1039, 70, 1, 17, '9', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3'),
(1040, 70, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3'),
(1041, 70, 1, 19, '9', '0', '1', 0, 0, '5', '13', '2023-11-29', NULL, NULL, '3'),
(1042, 70, 1, 20, '10', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3'),
(1043, 70, 1, 21, '10', '0', '1', 0, 0, '5', '14', '2023-11-29', NULL, NULL, '3'),
(1044, 70, 1, 22, '10', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3'),
(1045, 70, 1, 23, '10', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3'),
(1046, 70, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3'),
(1047, 70, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3'),
(1048, 70, 1, 26, '7', '0', '1', 0, 0, '5', '8', '2024-12-04', NULL, NULL, '3'),
(1049, 218, 1, 1, '5', '1', '0', 0, 0, '3', '14', '2022-08-16', NULL, NULL, '3'),
(1050, 218, 1, 2, '7', '1', '0', 0, 0, '3', '9', '2022-08-02', NULL, NULL, '3'),
(1051, 218, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3'),
(1052, 218, 1, 4, '9', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(1053, 218, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '3'),
(1054, 218, 1, 7, '7', '0', '0', 0, 1, '1', '48', '2022-08-17', NULL, NULL, '3'),
(1055, 218, 1, 10, '8', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3'),
(1056, 218, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3'),
(1057, 218, 1, 13, '9', '1', '0', 0, 0, '3', '10', '2024-12-18', NULL, NULL, '3'),
(1058, 219, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1059, 219, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1060, 219, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1088, 82, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1089, 82, 1, 2, '9', '1', '0', 0, 0, '4', '8', '2023-12-20', NULL, NULL, '3'),
(1090, 82, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1091, 82, 1, 5, '7', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1092, 82, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1093, 82, 1, 7, '9', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1094, 82, 1, 8, '8', '1', '0', 0, 0, '3', '28', '2023-03-07', NULL, NULL, '3'),
(1095, 82, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(1096, 82, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1097, 82, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1098, 82, 1, 12, '7', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3'),
(1099, 82, 1, 13, '9', '1', '0', 0, 0, '3', '10', '2024-12-18', NULL, NULL, '3'),
(1100, 82, 1, 16, '8', '1', '0', 0, 0, '2', '26', '2023-08-16', NULL, NULL, '3'),
(1101, 82, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3'),
(1102, 82, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3'),
(1103, 82, 1, 19, '8', '1', '0', 0, 0, '2', '39', '2024-03-06', NULL, NULL, '3'),
(1104, 82, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3'),
(1105, 82, 1, 23, '8', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3'),
(1106, 82, 1, 25, '8', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3'),
(1107, 220, 1, 1, '9', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1108, 220, 1, 2, '8', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3'),
(1109, 220, 1, 3, '8', '0', '1', 0, 0, '4', '10', '2022-07-04', NULL, NULL, '3'),
(1110, 220, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1111, 220, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1112, 220, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1113, 221, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1114, 221, 1, 3, '10', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(1115, 221, 1, 4, '9', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1116, 221, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1117, 221, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1118, 221, 1, 7, '10', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1119, 221, 1, 8, '8', '0', '1', 0, 0, '3', '18', '0000-00-00', NULL, NULL, '3'),
(1120, 221, 1, 9, '10', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(1121, 221, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1122, 221, 1, 11, '8', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1123, 13, 1, 1, '9', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3'),
(1124, 13, 1, 2, '4', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(1125, 13, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3'),
(1126, 13, 1, 4, '9', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(1127, 13, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3'),
(1128, 13, 1, 6, '9', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3'),
(1129, 13, 1, 7, '9', '1', '0', 0, 0, '3', '16', '2022-08-12', NULL, NULL, '3'),
(1130, 13, 1, 8, '9', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3'),
(1131, 13, 1, 9, '9', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3'),
(1132, 13, 1, 10, '9', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3'),
(1133, 13, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3'),
(1134, 13, 1, 12, '10', '0', '1', 0, 0, '5', '3', '2023-06-21', NULL, NULL, '3'),
(1135, 13, 1, 13, '8', '0', '1', 0, 0, '5', '1', '2023-06-28', NULL, NULL, '3'),
(1136, 13, 1, 14, '8', '1', '0', 0, 0, '2', '33', '2023-12-19', NULL, NULL, '3'),
(1137, 13, 1, 15, '9', '1', '0', 0, 0, '2', '25', '2023-08-15', NULL, NULL, '3'),
(1138, 13, 1, 16, '8', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3'),
(1139, 13, 1, 17, '9', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '3'),
(1140, 13, 1, 18, '9', '1', '0', 0, 0, '2', '30', '2023-12-06', NULL, NULL, '3'),
(1141, 13, 1, 19, '7', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '3'),
(1142, 13, 1, 20, '9', '1', '0', 0, 0, '2', '28', '2023-08-18', NULL, NULL, '3'),
(1143, 13, 1, 21, '9', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '3'),
(1144, 13, 1, 22, '10', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3'),
(1145, 13, 1, 23, '10', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3'),
(1146, 13, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3'),
(1147, 13, 1, 25, '10', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3'),
(1148, 13, 1, 26, '7', '0', '0', 0, 1, '2', '45', '2025-03-19', NULL, NULL, '3'),
(1149, 13, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '3'),
(1150, 222, 1, 1, '6', '1', '0', 0, 0, '3', '14', '2022-08-16', NULL, NULL, '3'),
(1151, 222, 1, 3, '8', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3'),
(1152, 222, 1, 4, '8', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(1153, 222, 1, 5, '8', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3'),
(1154, 222, 1, 6, '7', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3'),
(1155, 222, 1, 7, '6', '0', '0', 0, 1, '1', '48', '2022-08-17', NULL, NULL, '3'),
(1156, 222, 1, 10, '8', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3'),
(1157, 222, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3'),
(1158, 77, 1, 1, '9', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3'),
(1159, 77, 1, 5, '8', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3'),
(1160, 77, 1, 9, '8', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3'),
(1161, 223, 1, 4, '7', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3'),
(1171, 224, 1, 1, '9', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1172, 224, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1173, 224, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1174, 224, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1175, 224, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1176, 224, 1, 8, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3'),
(1177, 224, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(1178, 224, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1179, 224, 1, 11, '8', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1180, 224, 1, 12, '8', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '3'),
(1181, 224, 1, 14, '8', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '3'),
(1182, 224, 1, 15, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '3'),
(1183, 224, 1, 16, '7', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '3'),
(1184, 224, 1, 17, '8', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '3'),
(1185, 224, 1, 19, '8', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '3'),
(1186, 224, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '3'),
(1207, 14, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1208, 14, 1, 2, '4', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3'),
(1209, 14, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1210, 14, 1, 5, '8', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1211, 14, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1212, 14, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1213, 14, 1, 8, '5', '1', '0', 0, 0, '3', '30', '2023-03-21', NULL, NULL, '3'),
(1214, 14, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(1215, 14, 1, 10, '8', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1216, 14, 1, 13, '5', '0', '0', 0, 1, '2', '1', '2023-08-15', NULL, NULL, '3'),
(1217, 14, 1, 16, '8', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3'),
(1218, 14, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3'),
(1219, 14, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3'),
(1220, 14, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3'),
(1221, 14, 1, 21, '8', '0', '1', 0, 0, '5', '14', '2023-11-29', NULL, NULL, '3'),
(1222, 14, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3'),
(1223, 14, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3'),
(1224, 225, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1225, 225, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1226, 225, 1, 5, '7', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1227, 225, 1, 6, '6', '1', '0', 0, 0, '3', '10', '2022-08-01', NULL, NULL, '3'),
(1228, 225, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1229, 225, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1230, 225, 1, 11, '8', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1242, 3, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1243, 3, 1, 2, '6', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(1244, 3, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(1245, 3, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1246, 3, 1, 5, '7', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1247, 3, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1248, 3, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1249, 3, 1, 8, '7', '1', '0', 0, 0, '3', '25', '2022-12-13', NULL, NULL, '3'),
(1250, 3, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(1251, 3, 1, 10, '9', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1252, 3, 1, 11, '9', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1253, 3, 1, 12, '7', '0', '1', 0, 0, '2', '27', '2023-08-16', NULL, NULL, '3'),
(1254, 3, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '3'),
(1255, 3, 1, 16, '7', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3'),
(1256, 3, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3'),
(1257, 3, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3'),
(1258, 3, 1, 19, '9', '0', '1', 0, 0, '2', '35', '2023-12-20', NULL, NULL, '3'),
(1259, 3, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3'),
(1260, 3, 1, 23, '7', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3'),
(1261, 3, 1, 25, '8', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3'),
(1262, 3, 1, 26, '6', '1', '0', 0, 0, '1', '40', '2024-12-04', NULL, NULL, '3'),
(1263, 68, 1, 1, '9', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3'),
(1264, 68, 1, 2, '5', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3'),
(1265, 68, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(1266, 68, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1267, 68, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3'),
(1268, 68, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1269, 68, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1270, 68, 1, 8, '9', '1', '0', 0, 0, '3', '28', '2022-03-07', NULL, NULL, '3'),
(1271, 68, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3'),
(1272, 68, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3'),
(1273, 68, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1274, 68, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3'),
(1275, 68, 1, 19, '8', '0', '1', 0, 0, '2', '39', '2024-03-06', NULL, NULL, '3'),
(1276, 226, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1277, 226, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3'),
(1278, 226, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1279, 227, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3'),
(1280, 227, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3'),
(1281, 227, 1, 6, '4', '1', '0', 0, 0, '3', '10', '2022-08-01', NULL, NULL, '3'),
(1282, 227, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3'),
(1283, 227, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3'),
(1316, 75, 1, 1, '8', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1317, 75, 1, 2, '10', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1318, 75, 1, 3, '10', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4'),
(1319, 75, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1320, 75, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4'),
(1321, 75, 1, 6, '10', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4'),
(1322, 75, 1, 7, '10', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1323, 75, 1, 8, '9', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4'),
(1324, 75, 1, 9, '10', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4'),
(1325, 75, 1, 10, '10', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1326, 75, 1, 11, '10', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1327, 75, 1, 12, '8', '0', '1', 0, 0, '4', '23', '2022-07-04', NULL, NULL, '4'),
(1328, 75, 1, 13, '8', '0', '1', 0, 0, '4', '22', '2022-07-04', NULL, NULL, '4'),
(1329, 75, 1, 14, '10', '0', '1', 0, 0, '4', '32', '2022-03-04', NULL, NULL, '4'),
(1330, 75, 1, 15, '10', '1', '0', 0, 0, '2', '11', '2022-08-16', NULL, NULL, '4'),
(1331, 75, 1, 16, '9', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4'),
(1332, 75, 1, 17, '9', '0', '1', 0, 0, '4', '19', '2022-07-04', NULL, NULL, '4'),
(1333, 75, 1, 18, '10', '1', '0', 0, 0, '2', '34', '2023-12-20', NULL, NULL, '4'),
(1334, 75, 1, 19, '9', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '4'),
(1335, 75, 1, 20, '9', '1', '0', 0, 0, '2', '29', '2023-08-18', NULL, NULL, '4'),
(1336, 75, 1, 21, '8', '0', '1', 0, 0, '4', '28', '2022-11-14', NULL, NULL, '4'),
(1337, 75, 1, 22, '8', '0', '1', 0, 0, '5', '2', '2023-07-23', NULL, NULL, '4'),
(1338, 75, 1, 23, '10', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1339, 75, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(1340, 75, 1, 25, '9', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1341, 75, 1, 26, '7', '0', '1', 0, 0, '5', '4', '2023-11-15', NULL, NULL, '4'),
(1342, 75, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4'),
(1353, 229, 1, 3, '7', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4'),
(1354, 229, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1355, 229, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1356, 229, 1, 10, '8', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1357, 229, 1, 11, '8', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1361, 231, 1, 2, '7', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1362, 231, 1, 3, '7', '1', '0', 0, 0, '2', '35', '2021-11-30', NULL, NULL, '4'),
(1363, 231, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1364, 231, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4'),
(1365, 231, 1, 6, '8', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4'),
(1366, 231, 1, 7, '9', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1367, 231, 1, 8, '8', '1', '0', 0, 0, '2', '44', '2021-12-15', NULL, NULL, '4'),
(1368, 231, 1, 10, '8', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1369, 231, 1, 11, '8', '0', '1', 0, 0, '2', '52', '2021-11-17', NULL, NULL, '4'),
(1370, 231, 1, 13, '7', '0', '1', 0, 0, '4', '22', '2022-07-04', NULL, NULL, '4'),
(1371, 231, 1, 16, '8', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4'),
(1414, 233, 1, 1, '9', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1415, 233, 1, 2, '6', '1', '0', 0, 0, '2', '32', '2021-08-17', NULL, NULL, '4'),
(1416, 233, 1, 3, '9', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4'),
(1417, 233, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1418, 233, 1, 6, '8', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4'),
(1419, 233, 1, 7, '9', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1420, 233, 1, 8, '7', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4'),
(1421, 233, 1, 9, '9', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4'),
(1422, 233, 1, 10, '9', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1423, 233, 1, 11, '9', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1424, 234, 1, 1, '6', '1', '0', 0, 0, '2', '50', '2022-03-10', NULL, NULL, '4'),
(1425, 234, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-01', NULL, NULL, '4'),
(1426, 234, 1, 7, '7', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1427, 234, 1, 9, '8', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4'),
(1428, 234, 1, 10, '9', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1429, 234, 1, 11, '8', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1430, 234, 1, 12, '7', '0', '1', 0, 0, '4', '23', '2022-07-04', NULL, NULL, '4'),
(1490, 2, 1, 1, '8', '1', '0', 0, 0, '2', '25', '2021-08-02', NULL, NULL, '4'),
(1491, 2, 1, 2, '7', '1', '0', 0, 0, '2', '34', '2021-11-29', NULL, NULL, '4'),
(1492, 2, 1, 3, '9', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4'),
(1493, 2, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1494, 2, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4'),
(1495, 2, 1, 6, '8', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4'),
(1496, 2, 1, 7, '10', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1497, 2, 1, 8, '8', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4'),
(1498, 2, 1, 9, '8', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4'),
(1499, 2, 1, 10, '8', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1500, 2, 1, 11, '8', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1501, 2, 1, 12, '8', '0', '1', 0, 0, '4', '23', '2022-07-04', NULL, NULL, '4'),
(1502, 2, 1, 13, '10', '0', '1', 0, 0, '4', '22', '2022-07-04', NULL, NULL, '4'),
(1503, 2, 1, 14, '9', '0', '1', 0, 0, '4', '32', '2022-11-14', NULL, NULL, '4'),
(1504, 2, 1, 15, '8', '1', '0', 0, 0, '2', '17', '2023-03-23', NULL, NULL, '4'),
(1505, 2, 1, 16, '8', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4'),
(1506, 2, 1, 17, '8', '0', '1', 0, 0, '4', '19', '2022-07-04', NULL, NULL, '4'),
(1507, 2, 1, 18, '9', '1', '0', 0, 0, '2', '21', '2023-08-02', NULL, NULL, '4'),
(1508, 2, 1, 19, '9', '0', '1', 0, 0, '4', '35', '2022-11-16', NULL, NULL, '4'),
(1509, 2, 1, 20, '9', '1', '0', 0, 0, '2', '19', '2023-03-27', NULL, NULL, '4'),
(1510, 2, 1, 21, '10', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '4'),
(1511, 2, 1, 22, '7', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4'),
(1512, 2, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1513, 2, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '4'),
(1514, 2, 1, 25, '9', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1515, 2, 1, 26, '7', '0', '1', 0, 0, '5', '4', '2023-11-15', NULL, NULL, '4'),
(1516, 2, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '4'),
(1517, 235, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1518, 235, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1519, 235, 1, 9, '7', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4'),
(1520, 235, 1, 10, '7', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1521, 235, 1, 11, '7', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1522, 236, 1, 4, '8', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1568, 239, 1, 1, '6', '1', '0', 0, 0, '3', '11', '2022-08-02', NULL, NULL, '4'),
(1569, 239, 1, 2, '6', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1570, 239, 1, 3, '6', '0', '0', 0, 1, '1', '49', '2022-11-29', NULL, NULL, '4'),
(1571, 239, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1572, 239, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1573, 239, 1, 6, '7', '0', '0', 0, 1, '1', '42', '2021-08-11', NULL, NULL, '4'),
(1574, 239, 1, 7, '8', '1', '0', 0, 0, '2', '45', '2021-12-16', NULL, NULL, '4'),
(1575, 239, 1, 8, '7', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4'),
(1576, 239, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1577, 239, 1, 10, '9', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1578, 239, 1, 11, '9', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1579, 239, 1, 12, '9', '1', '0', 0, 0, '2', '16', '2023-03-08', NULL, NULL, '4'),
(1580, 239, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1581, 239, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4'),
(1582, 239, 1, 15, '10', '0', '0', 0, 1, '2', '22', '2023-12-22', NULL, NULL, '4'),
(1583, 239, 1, 16, '8', '0', '0', 0, 1, '1', '54', '2023-08-03', NULL, NULL, '4'),
(1584, 239, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(1585, 239, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4'),
(1586, 239, 1, 19, '8', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4'),
(1587, 239, 1, 20, '9', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(1588, 239, 1, 21, '8', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4'),
(1589, 239, 1, 22, '8', '0', '0', 0, 1, '2', '5', '2023-08-16', NULL, NULL, '4'),
(1590, 239, 1, 23, '7', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1591, 239, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(1592, 239, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1593, 239, 1, 26, '6', '1', '0', 0, 0, '1', '36', '2023-12-18', NULL, NULL, '4'),
(1594, 239, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4'),
(1654, 241, 1, 1, '6', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1655, 241, 1, 2, '9', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1656, 241, 1, 3, '8.25', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1657, 241, 1, 4, '9', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1658, 241, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1659, 241, 1, 6, '9', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4'),
(1660, 241, 1, 7, '9', '1', '0', 0, 0, '2', '45', '2021-12-16', NULL, NULL, '4'),
(1661, 241, 1, 8, '10', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4'),
(1662, 241, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1663, 241, 1, 10, '9', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1664, 241, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1665, 241, 1, 12, '9', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(1666, 241, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1667, 241, 1, 14, '9', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4'),
(1668, 241, 1, 15, '10', '0', '1', 0, 0, '2', '9', '2022-08-01', NULL, NULL, '4'),
(1669, 241, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(1670, 241, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(1671, 241, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4'),
(1672, 241, 1, 19, '9', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4'),
(1673, 241, 1, 20, '9', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(1674, 241, 1, 21, '10', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4'),
(1675, 241, 1, 22, '9', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4'),
(1676, 241, 1, 23, '10', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1677, 241, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(1678, 241, 1, 25, '9', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1679, 241, 1, 26, '4', '1', '0', 0, 0, '3', '35', '2023-12-05', NULL, NULL, '4'),
(1680, 241, 1, 27, '10', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4'),
(1713, 242, 1, 1, '6', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1714, 242, 1, 2, '9', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1715, 242, 1, 3, '7.80', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1716, 242, 1, 4, '9', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1717, 242, 1, 5, '9', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1718, 242, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4'),
(1719, 242, 1, 7, '10', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4'),
(1720, 242, 1, 8, '7', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4'),
(1721, 242, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1722, 242, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1723, 242, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1724, 242, 1, 12, '8', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(1725, 242, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1726, 242, 1, 14, '9', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4'),
(1727, 242, 1, 15, '8', '1', '0', 0, 0, '2', '17', '2023-03-23', NULL, NULL, '4'),
(1728, 242, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(1729, 242, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(1730, 242, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4'),
(1731, 242, 1, 19, '8', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4'),
(1732, 242, 1, 20, '9', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(1733, 242, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4'),
(1734, 242, 1, 22, '8', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4'),
(1735, 242, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1736, 242, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(1737, 242, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1738, 242, 1, 26, '7', '1', '0', 0, 0, '3', '35', '2023-12-05', NULL, NULL, '4'),
(1739, 242, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4'),
(1772, 243, 1, 1, '7', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1773, 243, 1, 2, '8', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` (`Id`, `alumno`, `carrera`, `materia`, `nota`, `cursada`, `rendida`, `equivalencia`, `libre`, `libro`, `folio`, `fecha`, `calificacion-cursada`, `calificacion_rendida`, `libro_corte`) VALUES
(1774, 243, 1, 3, '9', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1775, 243, 1, 4, '9', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1776, 243, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1777, 243, 1, 6, '5', '1', '0', 0, 0, '2', '27', '2021-08-04', NULL, NULL, '4'),
(1778, 243, 1, 7, '6', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4'),
(1779, 243, 1, 8, '7', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4'),
(1780, 243, 1, 9, '7', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1781, 243, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1782, 243, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1783, 243, 1, 12, '8', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(1784, 243, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1785, 243, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4'),
(1786, 243, 1, 15, '8', '1', '0', 0, 0, '2', '17', '2023-03-23', NULL, NULL, '4'),
(1787, 243, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(1788, 243, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(1789, 243, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4'),
(1790, 243, 1, 19, '8', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4'),
(1791, 243, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(1792, 243, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4'),
(1793, 243, 1, 22, '7', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4'),
(1794, 243, 1, 23, '9', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1795, 243, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(1796, 243, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1797, 243, 1, 26, '4', '1', '0', 0, 0, '3', '35', '2023-12-05', NULL, NULL, '4'),
(1798, 243, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4'),
(1830, 244, 1, 1, '8', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1831, 244, 1, 2, '7', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1832, 244, 1, 3, '7.75', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1833, 244, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1834, 244, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1835, 244, 1, 6, '6', '1', '0', 0, 0, '2', '27', '2021-08-04', NULL, NULL, '4'),
(1836, 244, 1, 7, '9', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4'),
(1837, 244, 1, 8, '8', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4'),
(1838, 244, 1, 9, '9', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1839, 244, 1, 10, '9', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1840, 244, 1, 11, '9', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1841, 244, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(1842, 244, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1843, 244, 1, 14, '7', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4'),
(1844, 244, 1, 16, '8', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(1845, 244, 1, 17, '7', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(1846, 244, 1, 18, '8', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4'),
(1847, 244, 1, 19, '8', '0', '1', 0, 0, '2', '15', '2022-12-15', NULL, NULL, '4'),
(1848, 244, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(1849, 244, 1, 21, '7', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4'),
(1850, 244, 1, 22, '8', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4'),
(1851, 244, 1, 23, '7', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1852, 244, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(1853, 244, 1, 25, '7', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1854, 245, 1, 2, '10', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1855, 245, 1, 3, '9', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1856, 245, 1, 4, '10', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1857, 245, 1, 6, '9', '0', '1', 0, 0, '3', '25', '2021-07-01', NULL, NULL, '4'),
(1858, 245, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '4'),
(1859, 245, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1860, 245, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(1861, 246, 1, 3, '7.25', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1862, 246, 1, 4, '7', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1895, 247, 1, 1, '7', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1896, 247, 1, 2, '4', '1', '0', 0, 0, '2', '32', '2021-08-17', NULL, NULL, '4'),
(1897, 247, 1, 3, '7.75', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1898, 247, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1899, 247, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1900, 247, 1, 6, '7', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4'),
(1901, 247, 1, 7, '8', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4'),
(1902, 247, 1, 8, '6', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4'),
(1903, 247, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1904, 247, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1905, 247, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1906, 247, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(1907, 247, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1908, 247, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4'),
(1909, 247, 1, 15, '9', '1', '0', 0, 0, '3', '11', '2024-12-20', NULL, NULL, '4'),
(1910, 247, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(1911, 247, 1, 17, '8', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(1912, 247, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '4'),
(1913, 247, 1, 19, '8', '1', '0', 0, 0, '2', '13', '2022-12-01', NULL, NULL, '4'),
(1914, 247, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(1915, 247, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4'),
(1916, 247, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1917, 247, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(1918, 247, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(1919, 247, 1, 27, '8', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '4'),
(1920, 248, 1, 4, '7', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1932, 249, 1, 1, '10', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4'),
(1933, 249, 1, 2, '9', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4'),
(1934, 249, 1, 3, '10', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1935, 249, 1, 4, '10', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1936, 249, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1937, 249, 1, 6, '9', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4'),
(1938, 249, 1, 7, '10', '1', '0', 0, 0, '2', '45', '2021-12-16', NULL, NULL, '4'),
(1939, 249, 1, 8, '8', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4'),
(1940, 249, 1, 9, '9', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1941, 249, 1, 10, '10', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1942, 249, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1943, 249, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(1944, 249, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(1945, 249, 1, 16, '8', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(1946, 249, 1, 17, '7', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(1947, 249, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(1948, 250, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1949, 250, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4'),
(1950, 228, 1, 1, '6', '1', '0', 0, 0, '2', '25', '2021-08-02', NULL, NULL, '4'),
(1951, 228, 1, 2, '7', '1', '0', 0, 0, '2', '34', '2021-11-29', NULL, NULL, '4'),
(1952, 228, 1, 3, '7.50', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4'),
(1953, 228, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1954, 228, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4'),
(1955, 228, 1, 6, '9', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4'),
(1956, 228, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1957, 228, 1, 8, '8', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4'),
(1958, 228, 1, 10, '9', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4'),
(1959, 228, 1, 11, '9', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1960, 230, 1, 3, '8.50', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4'),
(1961, 230, 1, 4, '8', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1962, 230, 1, 7, '7', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1963, 232, 1, 1, '9', '1', '0', 0, 0, '3', '11', '2022-08-02', NULL, NULL, '4'),
(1964, 232, 1, 2, '8', '1', '0', 0, 0, '3', '1', '2022-03-21', NULL, NULL, '4'),
(1965, 232, 1, 3, '7.75', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4'),
(1966, 232, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4'),
(1967, 232, 1, 5, '9', '1', '0', 0, 0, '3', '8', '2022-03-25', NULL, NULL, '4'),
(1968, 232, 1, 6, '9', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4'),
(1969, 232, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4'),
(1970, 232, 1, 8, '9', '1', '0', 0, 0, '3', '28', '2023-03-07', NULL, NULL, '4'),
(1971, 232, 1, 9, '9', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '4'),
(1972, 232, 1, 10, '9', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '4'),
(1973, 232, 1, 11, '9', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4'),
(1974, 232, 1, 12, '9', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '4'),
(1975, 232, 1, 13, '7', '0', '0', 1, 0, '1', '1', '2021-05-19', NULL, NULL, '4'),
(1976, 232, 1, 14, '10', '1', '0', 0, 0, '3', '13', '2025-03-11', NULL, NULL, '4'),
(1977, 232, 1, 16, '10', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4'),
(1978, 232, 1, 17, '8', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '4'),
(1979, 232, 1, 18, '10', '1', '0', 0, 0, '2', '30', '2023-12-06', NULL, NULL, '4'),
(1980, 232, 1, 19, '8', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '4'),
(1981, 232, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '4'),
(1982, 232, 1, 22, '8', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '4'),
(1983, 232, 1, 23, '9', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4'),
(1984, 237, 1, 3, '7.50', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1985, 237, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1986, 237, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4'),
(1987, 238, 1, 1, '8', '1', '0', 0, 0, '3', '7', '2022-03-25', NULL, NULL, '4'),
(1988, 238, 1, 2, '6', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '4'),
(1989, 238, 1, 3, '7.25', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(1990, 238, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(1991, 238, 1, 5, '9', '0', '1', 0, 0, '3', '38', '2021-11-01', NULL, NULL, '4'),
(1992, 238, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4'),
(1993, 238, 1, 7, '7', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4'),
(1994, 238, 1, 8, '9', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4'),
(1995, 238, 1, 9, '9', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(1996, 238, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(1997, 238, 1, 11, '7', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(1998, 238, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(1999, 238, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4'),
(2000, 238, 1, 16, '9', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '4'),
(2001, 238, 1, 17, '8', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(2002, 238, 1, 19, '7', '0', '1', 0, 0, '2', '13', '2022-12-01', NULL, NULL, '4'),
(2003, 238, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(2004, 238, 1, 21, '8', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4'),
(2005, 238, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4'),
(2006, 238, 1, 25, '7', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(2007, 240, 1, 1, '8', '1', '0', 0, 0, '2', '43', '2021-12-15', NULL, NULL, '4'),
(2008, 240, 1, 2, '6', '1', '0', 0, 0, '3', '1', '2022-03-21', NULL, NULL, '4'),
(2009, 240, 1, 3, '9.12', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4'),
(2010, 240, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4'),
(2011, 240, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-01', NULL, NULL, '4'),
(2012, 240, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4'),
(2013, 240, 1, 8, '9', '1', '0', 0, 0, '2', '44', '2021-12-15', NULL, NULL, '4'),
(2014, 240, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4'),
(2015, 240, 1, 10, '10', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4'),
(2016, 240, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4'),
(2017, 240, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4'),
(2018, 240, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4'),
(2019, 240, 1, 16, '8', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4'),
(2020, 240, 1, 17, '7', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4'),
(2021, 240, 1, 18, '8', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4'),
(2022, 240, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4'),
(2023, 240, 1, 25, '7', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4'),
(2024, 251, 1, 1, '8', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2025, 251, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2026, 251, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2027, 252, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2028, 252, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2029, 252, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2030, 252, 1, 4, '7', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2031, 252, 1, 5, '10', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2032, 252, 1, 6, '7', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6'),
(2033, 252, 1, 7, '8', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6'),
(2034, 252, 1, 8, '7', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6'),
(2035, 252, 1, 9, '8', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6'),
(2036, 252, 1, 10, '7', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6'),
(2037, 252, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6'),
(2038, 253, 1, 1, '8', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2039, 253, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2040, 253, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2041, 253, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2042, 253, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2043, 253, 1, 6, '7', '0', '0', 0, 1, '1', '20', '2019-09-23', NULL, NULL, '6'),
(2044, 253, 1, 7, '8', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6'),
(2045, 253, 1, 8, '9', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6'),
(2046, 253, 1, 9, '7', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6'),
(2047, 253, 1, 10, '8', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6'),
(2048, 253, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6'),
(2049, 254, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2050, 254, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2051, 254, 1, 3, '10', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2052, 254, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2053, 254, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2054, 254, 1, 6, '10', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6'),
(2055, 254, 1, 7, '10', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6'),
(2056, 254, 1, 8, '10', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6'),
(2057, 254, 1, 9, '10', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6'),
(2058, 254, 1, 10, '9', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6'),
(2059, 254, 1, 11, '8', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6'),
(2060, 254, 1, 12, '9', '1', '0', 0, 0, '1', '34', '2020-09-09', NULL, NULL, '6'),
(2061, 254, 1, 14, '8', '1', '0', 0, 0, '1', '32', '2020-09-03', NULL, NULL, '6'),
(2062, 254, 1, 17, '8', '0', '1', 0, 0, '3', '5', '2020-11-18', NULL, NULL, '6'),
(2063, 255, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2064, 256, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2065, 256, 1, 2, '10', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2066, 256, 1, 3, '10', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2067, 256, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2068, 256, 1, 5, '10', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2069, 256, 1, 6, '10', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6'),
(2070, 256, 1, 7, '9', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6'),
(2071, 256, 1, 8, '10', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6'),
(2072, 256, 1, 9, '9', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6'),
(2073, 256, 1, 10, '9', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6'),
(2074, 256, 1, 11, '9', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6'),
(2075, 256, 1, 12, '10', '0', '0', 0, 1, '1', '39', '2020-12-18', NULL, NULL, '6'),
(2076, 256, 1, 13, '4', '0', '0', 0, 1, '1', '35', '2020-12-11', NULL, NULL, '6'),
(2077, 256, 1, 14, '10', '1', '0', 0, 0, '1', '44', '2020-03-25', NULL, NULL, '6'),
(2078, 256, 1, 15, '4', '1', '0', 0, 0, '1', '53', '2021-10-22', NULL, NULL, '6'),
(2079, 256, 1, 16, '10', '1', '0', 0, 0, '1', '33', '2020-09-03', NULL, NULL, '6'),
(2080, 256, 1, 17, '8', '0', '1', 0, 0, '3', '5', '2020-11-18', NULL, NULL, '6'),
(2081, 256, 1, 18, '8', '1', '0', 0, 0, '2', '18', '2023-03-23', NULL, NULL, '6'),
(2082, 256, 1, 19, '8', '0', '1', 0, 0, '3', '45', '2021-11-17', NULL, NULL, '6'),
(2083, 256, 1, 20, '10', '1', '0', 0, 0, '1', '51', '2021-09-27', NULL, NULL, '6'),
(2084, 256, 1, 21, '8', '0', '1', 0, 0, '4', '28', '2022-11-14', NULL, NULL, '6'),
(2085, 256, 1, 22, '10', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '6'),
(2086, 256, 1, 23, '7', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '6'),
(2087, 256, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '6'),
(2088, 256, 1, 25, '9', '0', '0', 0, 1, '1', '50', '2022-12-01', NULL, NULL, '6'),
(2089, 256, 1, 26, '8', '1', '0', 0, 0, '1', '39', '2024-08-16', NULL, NULL, '6'),
(2090, 257, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2091, 257, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2092, 257, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2093, 257, 1, 4, '9', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2094, 257, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2095, 257, 1, 6, '8', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6'),
(2096, 258, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2097, 258, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2098, 258, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2099, 258, 1, 4, '9', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2100, 258, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2101, 258, 1, 6, '7', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6'),
(2102, 258, 1, 7, '9', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6'),
(2103, 258, 1, 9, '8', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6'),
(2104, 258, 1, 10, '8', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6'),
(2105, 258, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6'),
(2106, 259, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-09-25', NULL, NULL, '6'),
(2107, 259, 1, 2, '8', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2108, 259, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2109, 259, 1, 4, '7', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2110, 259, 1, 5, '8', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2111, 259, 1, 6, '8', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6'),
(2112, 259, 1, 7, '9', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6'),
(2113, 259, 1, 8, '8', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6'),
(2114, 259, 1, 9, '7', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6'),
(2115, 259, 1, 10, '7', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6'),
(2116, 259, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6'),
(2117, 260, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2118, 260, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2119, 260, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2120, 260, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6'),
(2121, 260, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2122, 260, 1, 6, '8', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6'),
(2123, 260, 1, 7, '10', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6'),
(2124, 260, 1, 8, '9', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6'),
(2125, 260, 1, 9, '8', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6'),
(2126, 260, 1, 10, '7', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6'),
(2127, 260, 1, 11, '8', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6'),
(2128, 15, 1, 1, '7', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2129, 15, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6'),
(2130, 15, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2131, 15, 1, 4, '10', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '6'),
(2132, 15, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6'),
(2133, 15, 1, 6, '7', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '6'),
(2134, 15, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '6'),
(2135, 15, 1, 8, '8', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '6'),
(2136, 15, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '6'),
(2137, 15, 1, 10, '10', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '6'),
(2138, 15, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '6'),
(2139, 15, 1, 12, '8', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6'),
(2140, 15, 1, 13, '4', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6'),
(2141, 15, 1, 14, '8', '0', '1', 0, 0, '4', '32', '2022-11-14', NULL, NULL, '6'),
(2142, 15, 1, 15, '9', '1', '0', 0, 0, '2', '25', '2023-08-15', NULL, NULL, '6'),
(2143, 15, 1, 16, '9', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '6'),
(2144, 15, 1, 17, '8', '0', '1', 0, 0, '4', '19', '2022-07-04', NULL, NULL, '6'),
(2145, 15, 1, 18, '8', '1', '0', 0, 0, '2', '21', '2023-08-02', NULL, NULL, '6'),
(2146, 15, 1, 19, '9', '0', '1', 0, 0, '3', '45', '2021-11-17', NULL, NULL, '6'),
(2147, 15, 1, 20, '9', '1', '0', 0, 0, '2', '19', '2023-03-27', NULL, NULL, '6'),
(2148, 15, 1, 21, '10', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '6'),
(2149, 15, 1, 22, '7', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '6'),
(2150, 15, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '6'),
(2151, 15, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '6'),
(2152, 15, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '6'),
(2153, 15, 1, 26, '6', '1', '0', 0, 0, '1', '36', '2023-12-18', NULL, NULL, '6'),
(2154, 15, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '6'),
(2155, 261, 1, 1, '7', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6'),
(2156, 261, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6'),
(2157, 262, 1, 1, '9', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6'),
(2158, 262, 1, 2, '10', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6'),
(2159, 262, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6'),
(2160, 262, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6'),
(2161, 262, 1, 5, '9', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6'),
(2162, 262, 1, 6, '10', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6'),
(2163, 262, 1, 7, '9', '1', '0', 0, 0, '2', '9', '2020-12-11', NULL, NULL, '6'),
(2164, 262, 1, 8, '9', '0', '1', 0, 0, '2', '48', '2019-11-06', NULL, NULL, '6'),
(2165, 262, 1, 9, '9', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6'),
(2166, 262, 1, 10, '7', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6'),
(2167, 262, 1, 11, '8', '0', '1', 0, 0, '1', '40', '2019-11-12', NULL, NULL, '6'),
(2168, 262, 1, 12, '9', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6'),
(2169, 262, 1, 13, '10', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6'),
(2170, 262, 1, 14, '9', '1', '0', 0, 0, '1', '24', '2020-08-12', NULL, NULL, '6'),
(2171, 262, 1, 15, '10', '1', '0', 0, 0, '1', '25', '2020-08-12', NULL, NULL, '6'),
(2172, 262, 1, 16, '9', '1', '0', 0, 0, '1', '28', '2020-08-14', NULL, NULL, '6'),
(2173, 262, 1, 17, '10', '0', '1', 0, 0, '3', '6', '2020-11-18', NULL, NULL, '6'),
(2174, 262, 1, 18, '8', '1', '0', 0, 0, '1', '38', '2020-12-15', NULL, NULL, '6'),
(2175, 262, 1, 19, '9', '1', '0', 0, 0, '1', '26', '2020-08-14', NULL, NULL, '6'),
(2176, 262, 1, 20, '10', '1', '0', 0, 0, '1', '27', '2020-08-14', NULL, NULL, '6'),
(2177, 262, 1, 21, '10', '0', '1', 0, 0, '3', '11', '2020-11-19', NULL, NULL, '6'),
(2178, 262, 1, 22, '9', '0', '1', 0, 0, '3', '32', '2021-07-02', NULL, NULL, '6'),
(2179, 262, 1, 23, '8', '0', '1', 0, 0, '3', '48', '2021-11-17', NULL, NULL, '6'),
(2180, 262, 1, 24, '8', '0', '1', 0, 0, '3', '33', '2021-07-02', NULL, NULL, '6'),
(2181, 262, 1, 25, '9', '0', '1', 0, 0, '3', '34', '2021-07-02', NULL, NULL, '6'),
(2182, 262, 1, 26, '9', '1', '0', 0, 0, '1', '29', '2021-11-30', NULL, NULL, '6'),
(2183, 262, 1, 27, '8', '0', '1', 0, 0, '3', '46', '2021-11-17', NULL, NULL, '6'),
(2184, 263, 1, 1, '10', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6'),
(2185, 263, 1, 2, '10', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6'),
(2186, 263, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6'),
(2187, 263, 1, 4, '10', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6'),
(2188, 263, 1, 5, '9', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6'),
(2189, 263, 1, 6, '10', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6'),
(2190, 263, 1, 7, '10', '0', '1', 0, 0, '2', '43', '2019-11-04', NULL, NULL, '6'),
(2191, 263, 1, 8, '10', '0', '1', 0, 0, '2', '48', '2019-11-06', NULL, NULL, '6'),
(2192, 263, 1, 9, '10', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6'),
(2193, 263, 1, 10, '9', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6'),
(2194, 263, 1, 11, '8', '0', '1', 0, 0, '1', '40', '2019-11-12', NULL, NULL, '6'),
(2195, 263, 1, 12, '10', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6'),
(2196, 263, 1, 13, '10', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6'),
(2197, 263, 1, 14, '10', '1', '0', 0, 0, '1', '24', '2020-08-12', NULL, NULL, '6'),
(2198, 263, 1, 15, '10', '1', '0', 0, 0, '1', '25', '2020-08-12', NULL, NULL, '6'),
(2199, 263, 1, 16, '10', '1', '0', 0, 0, '1', '28', '2020-08-14', NULL, NULL, '6'),
(2200, 263, 1, 17, '10', '0', '1', 0, 0, '3', '6', '2020-11-18', NULL, NULL, '6'),
(2201, 263, 1, 18, '9', '1', '0', 0, 0, '1', '38', '2020-12-15', NULL, NULL, '6'),
(2202, 263, 1, 19, '10', '1', '0', 0, 0, '1', '26', '2020-08-14', NULL, NULL, '6'),
(2203, 263, 1, 20, '10', '1', '0', 0, 0, '1', '27', '2020-08-14', NULL, NULL, '6'),
(2204, 263, 1, 21, '10', '0', '1', 0, 0, '3', '11', '2020-11-19', NULL, NULL, '6'),
(2205, 263, 1, 22, '10', '0', '1', 0, 0, '3', '32', '2021-07-02', NULL, NULL, '6'),
(2206, 263, 1, 23, '10', '0', '1', 0, 0, '3', '48', '2021-11-17', NULL, NULL, '6'),
(2207, 263, 1, 24, '10', '0', '1', 0, 0, '3', '33', '2021-07-02', NULL, NULL, '6'),
(2208, 263, 1, 25, '10', '0', '1', 0, 0, '3', '34', '2021-07-02', NULL, NULL, '6'),
(2209, 263, 1, 26, '7', '1', '0', 0, 0, '1', '29', '2021-11-30', NULL, NULL, '6'),
(2210, 263, 1, 27, '9', '0', '1', 0, 0, '3', '46', '2021-11-17', NULL, NULL, '6'),
(2211, 264, 1, 1, '8', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6'),
(2212, 264, 1, 2, '9', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6'),
(2213, 264, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6'),
(2214, 264, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6'),
(2215, 264, 1, 5, '9', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6'),
(2216, 264, 1, 6, '10', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6'),
(2217, 264, 1, 7, '10', '0', '1', 0, 0, '2', '43', '2019-11-04', NULL, NULL, '6'),
(2218, 264, 1, 8, '10', '0', '1', 0, 0, '2', '48', '2019-11-06', NULL, NULL, '6'),
(2219, 264, 1, 9, '10', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6'),
(2220, 264, 1, 10, '9', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6'),
(2221, 264, 1, 11, '8', '0', '1', 0, 0, '1', '40', '2019-11-12', NULL, NULL, '6'),
(2222, 264, 1, 12, '10', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6'),
(2223, 264, 1, 13, '10', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6'),
(2224, 264, 1, 14, '9', '1', '0', 0, 0, '1', '29', '2020-08-28', NULL, NULL, '6'),
(2225, 264, 1, 15, '10', '1', '0', 0, 0, '1', '25', '2020-08-12', NULL, NULL, '6'),
(2226, 264, 1, 16, '10', '1', '0', 0, 0, '1', '28', '2020-08-14', NULL, NULL, '6'),
(2227, 264, 1, 17, '10', '0', '1', 0, 0, '3', '6', '2020-11-18', NULL, NULL, '6'),
(2228, 264, 1, 18, '10', '1', '0', 0, 0, '1', '38', '2020-12-15', NULL, NULL, '6'),
(2229, 264, 1, 19, '8', '1', '0', 0, 0, '1', '26', '2020-08-14', NULL, NULL, '6'),
(2230, 264, 1, 20, '10', '1', '0', 0, 0, '1', '27', '2020-08-14', NULL, NULL, '6'),
(2231, 264, 1, 21, '10', '0', '1', 0, 0, '3', '11', '2020-11-19', NULL, NULL, '6'),
(2232, 264, 1, 22, '10', '0', '1', 0, 0, '3', '32', '2021-07-02', NULL, NULL, '6'),
(2233, 264, 1, 23, '9', '0', '1', 0, 0, '3', '48', '2021-11-17', NULL, NULL, '6'),
(2234, 264, 1, 24, '10', '0', '1', 0, 0, '3', '33', '2021-07-02', NULL, NULL, '6'),
(2235, 264, 1, 25, '10', '0', '1', 0, 0, '3', '34', '2021-07-02', NULL, NULL, '6'),
(2236, 264, 1, 26, '7', '0', '1', 0, 0, '3', '41', '2023-11-17', NULL, NULL, '6'),
(2237, 264, 1, 27, '7', '0', '1', 0, 0, '3', '46', '2023-11-17', NULL, NULL, '6'),
(2238, 265, 1, 9, '10', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6'),
(2239, 265, 1, 10, '7', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6'),
(2240, 266, 1, 1, '8', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6'),
(2241, 266, 1, 2, '8', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6'),
(2242, 266, 1, 3, '7', '0', '1', 0, 0, '2', '33', '2019-07-20', NULL, NULL, '6'),
(2243, 266, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6'),
(2244, 266, 1, 5, '8', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6'),
(2245, 267, 1, 1, '8', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6'),
(2246, 267, 1, 2, '10', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6'),
(2247, 267, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6'),
(2248, 267, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6'),
(2249, 267, 1, 5, '8', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6'),
(2250, 267, 1, 6, '9', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6'),
(2251, 267, 1, 7, '10', '0', '1', 0, 0, '2', '43', '2019-11-04', NULL, NULL, '6'),
(2284, 268, 1, 1, '9', '1', '0', 0, 0, '2', '15', '2020-12-21', NULL, NULL, '5'),
(2285, 268, 1, 2, '9', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5'),
(2286, 268, 1, 3, '10', '1', '0', 0, 0, '1', '45', '2020-09-01', NULL, NULL, '5'),
(2287, 268, 1, 4, '10', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2288, 268, 1, 5, '10', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5'),
(2289, 268, 1, 6, '10', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5'),
(2290, 268, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5'),
(2291, 268, 1, 8, '10', '0', '1', 0, 0, '3', '12', '2020-11-20', NULL, NULL, '5'),
(2292, 268, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5'),
(2293, 268, 1, 10, '10', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5'),
(2294, 268, 1, 11, '8', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5'),
(2295, 268, 1, 12, '9', '0', '1', 0, 0, '3', '27', '2021-07-02', NULL, NULL, '5'),
(2296, 268, 1, 13, '6', '1', '0', 0, 0, '2', '5', '2021-12-13', NULL, NULL, '5'),
(2297, 268, 1, 14, '10', '0', '1', 0, 0, '3', '36', '2021-11-08', NULL, NULL, '5'),
(2298, 268, 1, 15, '9', '0', '1', 0, 0, '3', '20', '2021-07-01', NULL, NULL, '5'),
(2299, 268, 1, 16, '10', '1', '0', 0, 0, '1', '52', '2021-09-28', NULL, NULL, '5'),
(2300, 268, 1, 17, '10', '0', '1', 0, 0, '3', '26', '2021-07-02', NULL, NULL, '5'),
(2301, 268, 1, 18, '9', '1', '0', 0, 0, '2', '8', '2022-03-28', NULL, NULL, '5'),
(2302, 268, 1, 19, '9', '0', '1', 0, 0, '3', '45', '2021-11-17', NULL, NULL, '5'),
(2303, 268, 1, 20, '9', '1', '0', 0, 0, '1', '54', '2021-10-25', NULL, NULL, '5'),
(2304, 268, 1, 21, '9', '0', '1', 0, 0, '3', '47', '2021-11-17', NULL, NULL, '5'),
(2305, 268, 1, 22, '10', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5'),
(2306, 268, 1, 23, '10', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5'),
(2307, 268, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5'),
(2308, 268, 1, 25, '10', '0', '1', 0, 0, '4', '13', '2022-07-07', NULL, NULL, '5'),
(2309, 268, 1, 26, '8', '0', '1', 0, 0, '4', '34', '2022-11-16', NULL, NULL, '5'),
(2310, 268, 1, 27, '10', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5'),
(2322, 269, 1, 1, '6', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5'),
(2323, 269, 1, 2, '9', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5'),
(2324, 269, 1, 3, '7', '1', '0', 0, 0, '1', '45', '2020-09-01', NULL, NULL, '5'),
(2325, 269, 1, 4, '10', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2326, 269, 1, 5, '9', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5'),
(2327, 269, 1, 6, '8', '1', '0', 0, 0, '2', '3', '2020-09-18', NULL, NULL, '5'),
(2328, 269, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5'),
(2329, 269, 1, 8, '8', '1', '0', 0, 0, '2', '14', '2020-12-17', NULL, NULL, '5'),
(2330, 269, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5'),
(2331, 269, 1, 10, '7', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5'),
(2332, 269, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5'),
(2333, 269, 1, 17, '7', '0', '1', 0, 0, '3', '26', '2021-07-02', NULL, NULL, '5'),
(2334, 270, 1, 1, '4', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5'),
(2335, 270, 1, 2, '6', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5'),
(2336, 270, 1, 3, '7', '0', '0', 0, 1, '1', '28', '2020-09-01', NULL, NULL, '5'),
(2337, 270, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2338, 270, 1, 5, '9', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5'),
(2339, 270, 1, 7, '8', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5'),
(2340, 270, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5'),
(2341, 270, 1, 10, '8', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5'),
(2342, 270, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5'),
(2343, 270, 1, 12, '7', '0', '1', 0, 0, '3', '27', '2021-07-02', NULL, NULL, '5'),
(2344, 271, 1, 2, '7', '1', '0', 0, 0, '1', '54', '2020-09-16', NULL, NULL, '5'),
(2345, 271, 1, 5, '10', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5'),
(2346, 271, 1, 7, '7', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5'),
(2347, 271, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5'),
(2348, 271, 1, 11, '10', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5'),
(2349, 272, 1, 1, '4', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5'),
(2350, 272, 1, 2, '6', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5'),
(2351, 272, 1, 3, '4', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5'),
(2352, 272, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2353, 272, 1, 5, '7', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5'),
(2354, 272, 1, 7, '8', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5'),
(2355, 272, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5'),
(2356, 272, 1, 10, '7', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5'),
(2357, 272, 1, 11, '7', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5'),
(2358, 272, 1, 16, '4', '1', '0', 0, 0, '1', '46', '2021-08-02', NULL, NULL, '5'),
(2359, 272, 1, 20, '9', '1', '0', 0, 0, '1', '50', '2021-08-11', NULL, NULL, '5'),
(2360, 273, 1, 2, '7', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5'),
(2361, 273, 1, 7, '6', '1', '0', 0, 0, '2', '48', '2022-03-07', NULL, NULL, '5'),
(2362, 273, 1, 10, '7', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5'),
(2363, 275, 1, 3, '6', '1', '0', 0, 0, '1', '45', '2020-09-01', NULL, NULL, '5'),
(2364, 275, 1, 4, '7', '0', '0', 0, 1, '1', '31', '2020-09-04', NULL, NULL, '5'),
(2365, 275, 1, 5, '9', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2366, 275, 1, 7, '10', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5'),
(2367, 275, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2368, 275, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2369, 275, 1, 11, '7', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2370, 276, 1, 1, '6', '1', '0', 0, 0, '1', '53', '2020-09-03', NULL, NULL, '5'),
(2371, 276, 1, 2, '7', '1', '0', 0, 0, '2', '12', '2020-12-16', NULL, NULL, '5'),
(2372, 276, 1, 3, '6', '1', '0', 0, 0, '2', '6', '2020-12-09', NULL, NULL, '5'),
(2373, 276, 1, 4, '7', '1', '0', 0, 0, '2', '4', '2020-09-18', NULL, NULL, '5'),
(2374, 276, 1, 5, '7', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2375, 276, 1, 7, '7', '1', '0', 0, 0, '2', '1', '2020-09-17', NULL, NULL, '5'),
(2376, 276, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2377, 276, 1, 10, '7', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2378, 276, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5'),
(2379, 276, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5'),
(2380, 276, 1, 14, '7', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5'),
(2381, 276, 1, 16, '8', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5'),
(2382, 276, 1, 19, '7', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5'),
(2383, 276, 1, 20, '7', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5'),
(2394, 69, 1, 1, '10', '1', '0', 0, 0, '3', '11', '2022-08-02', NULL, NULL, '5'),
(2395, 69, 1, 2, '10', '1', '0', 0, 0, '2', '32', '2021-08-17', NULL, NULL, '5'),
(2396, 69, 1, 3, '7', '0', '0', 0, 1, '1', '52', '2023-03-21', NULL, NULL, '5'),
(2397, 69, 1, 4, '9', '1', '0', 0, 0, '1', '49', '2020-09-04', NULL, NULL, '5'),
(2398, 69, 1, 5, '9', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2399, 69, 1, 6, '8', '1', '0', 0, 0, '3', '40', '2023-09-25', NULL, NULL, '5'),
(2400, 69, 1, 8, '10', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5'),
(2401, 69, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2402, 69, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2403, 69, 1, 11, '9', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2404, 69, 1, 12, '9', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5'),
(2405, 69, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '5'),
(2406, 69, 1, 14, '9', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5'),
(2407, 69, 1, 15, '10', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '5'),
(2408, 69, 1, 16, '8', '0', '1', 0, 0, '5', '27', '2024-07-03', NULL, NULL, '5'),
(2409, 69, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5'),
(2410, 69, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '5'),
(2411, 69, 1, 19, '7', '0', '1', 0, 0, '3', '29', '2021-11-17', NULL, NULL, '5'),
(2412, 69, 1, 20, '10', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5'),
(2413, 69, 1, 23, '9', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5'),
(2435, 277, 1, 1, '10', '1', '0', 0, 0, '2', '15', '2020-12-21', NULL, NULL, '5'),
(2436, 277, 1, 2, '10', '1', '0', 0, 0, '2', '16', '2021-03-08', NULL, NULL, '5'),
(2437, 277, 1, 3, '9', '1', '0', 0, 0, '2', '22', '2021-03-23', NULL, NULL, '5'),
(2438, 277, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2439, 277, 1, 5, '9', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2440, 277, 1, 6, '10', '1', '0', 0, 0, '2', '13', '2020-12-16', NULL, NULL, '5'),
(2441, 277, 1, 7, '10', '1', '0', 0, 0, '2', '9', '2020-12-11', NULL, NULL, '5'),
(2442, 277, 1, 8, '10', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5'),
(2443, 277, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2444, 277, 1, 10, '9', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2445, 277, 1, 11, '9', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2446, 277, 1, 12, '9', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5'),
(2447, 277, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5'),
(2448, 277, 1, 14, '10', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5'),
(2449, 277, 1, 15, '10', '0', '1', 0, 0, '3', '17', '2021-07-01', NULL, NULL, '5'),
(2450, 277, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5'),
(2451, 277, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5'),
(2452, 277, 1, 18, '9', '0', '1', 0, 0, '2', '8', '2022-03-28', NULL, NULL, '5'),
(2453, 277, 1, 19, '7', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5'),
(2454, 277, 1, 20, '10', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5'),
(2455, 277, 1, 21, '10', '0', '1', 0, 0, '4', '2', '2021-11-17', NULL, NULL, '5'),
(2456, 277, 1, 22, '8', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5'),
(2457, 277, 1, 23, '10', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5'),
(2458, 277, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5'),
(2459, 277, 1, 25, '9', '0', '1', 0, 0, '4', '13', '2022-07-01', NULL, NULL, '5'),
(2460, 277, 1, 26, '10', '1', '0', 0, 0, '1', '32', '2022-12-02', NULL, NULL, '5'),
(2461, 277, 1, 27, '10', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5'),
(2462, 278, 1, 3, '9', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5'),
(2463, 278, 1, 4, '7', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2464, 278, 1, 8, '8', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5'),
(2465, 278, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2466, 278, 1, 10, '7', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2467, 278, 1, 11, '8', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2500, 279, 1, 1, '8', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5'),
(2501, 279, 1, 2, '10', '1', '0', 0, 0, '1', '54', '2020-09-16', NULL, NULL, '5'),
(2502, 279, 1, 3, '10', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5'),
(2503, 279, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2504, 279, 1, 5, '10', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2505, 279, 1, 6, '7', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5'),
(2506, 279, 1, 7, '7', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5'),
(2507, 279, 1, 8, '8', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5'),
(2508, 279, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2509, 279, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2510, 279, 1, 11, '9', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2511, 279, 1, 12, '7', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5'),
(2512, 279, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5'),
(2513, 279, 1, 14, '8', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5'),
(2514, 279, 1, 15, '5', '1', '0', 0, 0, '1', '53', '2021-10-22', NULL, NULL, '5'),
(2515, 279, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5'),
(2516, 279, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5'),
(2517, 279, 1, 18, '9', '1', '0', 0, 0, '2', '8', '2022-03-28', NULL, NULL, '5'),
(2518, 279, 1, 19, '7', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5'),
(2519, 279, 1, 20, '8', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5'),
(2520, 279, 1, 21, '10', '0', '1', 0, 0, '4', '2', '2021-11-17', NULL, NULL, '5'),
(2521, 279, 1, 22, '8', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5'),
(2522, 279, 1, 23, '9', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5'),
(2523, 279, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5'),
(2524, 279, 1, 25, '9', '0', '1', 0, 0, '4', '13', '2022-07-01', NULL, NULL, '5'),
(2525, 279, 1, 26, '10', '1', '0', 0, 0, '1', '32', '2022-12-02', NULL, NULL, '5'),
(2526, 279, 1, 27, '8', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5'),
(2559, 280, 1, 1, '6', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5'),
(2560, 280, 1, 2, '7', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5'),
(2561, 280, 1, 3, '10', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5'),
(2562, 280, 1, 4, '8', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2563, 280, 1, 5, '10', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2564, 280, 1, 6, '10', '1', '0', 0, 0, '1', '44', '2020-09-01', NULL, NULL, '5'),
(2565, 280, 1, 7, '7', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5'),
(2566, 280, 1, 8, '10', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5'),
(2567, 280, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2568, 280, 1, 10, '9', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2569, 280, 1, 11, '8', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2570, 280, 1, 12, '8', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5'),
(2571, 280, 1, 13, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5'),
(2572, 280, 1, 14, '7', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5'),
(2573, 280, 1, 15, '10', '0', '1', 0, 0, '3', '17', '2021-07-01', NULL, NULL, '5'),
(2574, 280, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5'),
(2575, 280, 1, 17, '10', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5'),
(2576, 280, 1, 18, '5', '1', '0', 0, 0, '2', '1', '2021-12-01', NULL, NULL, '5'),
(2577, 280, 1, 19, '9', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5'),
(2578, 280, 1, 20, '9', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5'),
(2579, 280, 1, 21, '10', '0', '1', 0, 0, '4', '2', '2021-11-17', NULL, NULL, '5'),
(2580, 280, 1, 22, '9', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5'),
(2581, 280, 1, 23, '9', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5'),
(2582, 280, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5'),
(2583, 280, 1, 25, '10', '0', '1', 0, 0, '4', '13', '2022-07-01', NULL, NULL, '5'),
(2584, 280, 1, 26, '9', '1', '0', 0, 0, '1', '33', '2022-12-16', NULL, NULL, '5'),
(2585, 280, 1, 27, '10', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5'),
(2586, 281, 1, 1, '6', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5'),
(2587, 281, 1, 2, '7', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5'),
(2588, 281, 1, 3, '9', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5'),
(2589, 281, 1, 4, '7', '0', '1', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2590, 281, 1, 5, '10', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2591, 281, 1, 6, '9', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5'),
(2592, 281, 1, 7, '10', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5'),
(2593, 281, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5'),
(2594, 281, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2595, 281, 1, 11, '10', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5'),
(2596, 281, 1, 13, '10', '0', '0', 0, 1, '1', '46', '2022-08-16', NULL, NULL, '5'),
(2597, 281, 1, 16, '8', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5'),
(2598, 34, 1, 2, '6', '1', '0', 0, 0, '1', '54', '2020-09-16', NULL, NULL, '5'),
(2599, 34, 1, 4, '7', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '5'),
(2600, 34, 1, 5, '7', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '5'),
(2601, 34, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '5'),
(2602, 34, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` (`Id`, `alumno`, `carrera`, `materia`, `nota`, `cursada`, `rendida`, `equivalencia`, `libre`, `libro`, `folio`, `fecha`, `calificacion-cursada`, `calificacion_rendida`, `libro_corte`) VALUES
(2603, 34, 1, 11, '7', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2604, 34, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '5'),
(2637, 84, 1, 1, '7', '1', '0', 0, 0, '2', '43', '2021-12-15', NULL, NULL, '5'),
(2638, 84, 1, 2, '9', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5'),
(2639, 84, 1, 3, '10', '0', '0', 0, 1, '1', '28', '2020-09-01', NULL, NULL, '5'),
(2640, 84, 1, 4, '7', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5'),
(2641, 84, 1, 5, '8', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5'),
(2642, 84, 1, 6, '10', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5'),
(2643, 84, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5'),
(2644, 84, 1, 8, '9', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5'),
(2645, 84, 1, 9, '9', '1', '0', 0, 0, '3', '17', '2022-08-18', NULL, NULL, '5'),
(2646, 84, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5'),
(2647, 84, 1, 11, '7', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5'),
(2648, 84, 1, 12, '9', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '5'),
(2649, 84, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5'),
(2650, 84, 1, 14, '9', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '5'),
(2651, 84, 1, 15, '10', '1', '0', 0, 0, '2', '32', '2023-12-11', NULL, NULL, '5'),
(2652, 84, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5'),
(2653, 84, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5'),
(2654, 84, 1, 18, '8', '0', '0', 0, 1, '2', '20', '2023-12-20', NULL, NULL, '5'),
(2655, 84, 1, 19, '7', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '5'),
(2656, 84, 1, 20, '8', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '5'),
(2657, 84, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '5'),
(2658, 84, 1, 22, '9', '1', '0', 0, 0, '1', '34', '2023-08-16', NULL, NULL, '5'),
(2659, 84, 1, 23, '8', '1', '0', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '5'),
(2660, 84, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '5'),
(2661, 84, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '5'),
(2662, 84, 1, 26, '7', '0', '1', 0, 0, '2', '46', '2023-05-07', NULL, NULL, '5'),
(2663, 84, 1, 27, '10', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '5'),
(2664, 16, 1, 24, '7', '1', '1', NULL, NULL, NULL, NULL, '2025-10-01', NULL, NULL, NULL),
(2665, 282, 1, 1, '7', '1', '1', NULL, NULL, NULL, NULL, '2024-10-01', NULL, NULL, NULL),
(2666, 282, 1, 2, '7', '1', '1', NULL, NULL, NULL, NULL, '2024-10-01', NULL, NULL, NULL),
(2667, 282, 1, 3, '7', '1', '1', NULL, NULL, NULL, NULL, '2024-10-01', NULL, NULL, NULL),
(2668, 282, 1, 4, '7', '1', '1', NULL, NULL, NULL, NULL, '2024-10-01', NULL, NULL, NULL),
(2669, 282, 1, 6, '7', '1', '1', NULL, NULL, NULL, NULL, '2024-10-01', NULL, NULL, NULL),
(2670, 282, 1, 5, '7', '1', '1', NULL, NULL, NULL, NULL, '2025-04-01', NULL, NULL, NULL),
(2671, 282, 1, 9, '7', '1', '1', NULL, NULL, NULL, NULL, '2025-04-01', NULL, NULL, NULL),
(2672, 282, 1, 13, '7', '1', '1', NULL, NULL, NULL, NULL, '2025-07-01', NULL, NULL, NULL),
(2673, 282, 1, 20, '7', '1', '1', NULL, NULL, NULL, NULL, '2025-07-01', NULL, NULL, NULL),
(2674, 282, 1, 15, NULL, '1', '0', NULL, NULL, NULL, NULL, '2025-07-01', NULL, NULL, NULL),
(2675, 282, 1, 16, '7', '1', '1', NULL, NULL, NULL, NULL, '2025-07-01', NULL, NULL, NULL),
(2676, 282, 1, 17, '7', '1', '1', NULL, NULL, NULL, NULL, '2025-08-01', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_alumnos_mesa`
--

CREATE TABLE `tbl_alumnos_mesa` (
  `id` int(11) NOT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `legajo` varchar(255) DEFAULT NULL,
  `anno` varchar(255) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `materia` int(11) NOT NULL DEFAULT 0,
  `turno` int(11) NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `curso` int(11) NOT NULL,
  `division` int(11) NOT NULL,
  `cursado` varchar(255) NOT NULL,
  `mesa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_carreras`
--

CREATE TABLE `tbl_carreras` (
  `Id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `resolucion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_carreras`
--

INSERT INTO `tbl_carreras` (`Id`, `nombre`, `resolucion`) VALUES
(1, 'Tecnicatura Superior en Desarrollo de Software', NULL),
(2, 'Profesorado de Educación Inicial', NULL),
(3, 'Profesorado de Educación Primaria', NULL),
(4, 'Tecnicatura Superior en Turismo', NULL),
(5, 'Tecnicatura Superior en Mecatrónica', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cursar`
--

CREATE TABLE `tbl_cursar` (
  `id` int(11) NOT NULL,
  `mesa` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `cerra_mesa` varchar(255) DEFAULT NULL,
  `estado_cerra` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_cursar`
--

INSERT INTO `tbl_cursar` (`id`, `mesa`, `estado`, `fecha`, `cerra_mesa`, `estado_cerra`) VALUES
(1, 'cursar_agosto_2_semestre', '1', '2025-08-20 20:40:10', '0', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_materias`
--

CREATE TABLE `tbl_materias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `semestre` varchar(255) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `anno` int(11) DEFAULT NULL,
  `paracursar_regular` varchar(255) DEFAULT NULL COMMENT 'codigo materia :',
  `paracursar_rendido` varchar(255) DEFAULT NULL COMMENT 'codigo materia :',
  `pararendir` varchar(255) DEFAULT NULL COMMENT 'codigo materia :',
  `resolucion` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_materias`
--

INSERT INTO `tbl_materias` (`id`, `nombre`, `semestre`, `carrera`, `anno`, `paracursar_regular`, `paracursar_rendido`, `pararendir`, `resolucion`) VALUES
(1, 'Comprensión y producción de texto', '1', 1, 1, NULL, NULL, NULL, 1),
(2, 'Matemática I', '1', 1, 1, NULL, NULL, NULL, 1),
(3, 'Procesamiento de Datos', '1', 1, 1, NULL, NULL, NULL, 1),
(4, 'Sistemas de Información I', '1', 1, 1, NULL, NULL, NULL, 1),
(5, 'Inglés Técnico I', '2', 1, 1, NULL, NULL, NULL, 1),
(6, 'Programación I', '1', 1, 1, NULL, NULL, NULL, 1),
(7, 'Contexto socio-economico y productivo', '1', 1, 1, '', NULL, NULL, 1),
(8, 'Programación II', '2', 1, 1, '6', '', '6', 1),
(9, 'Ambiente Empresarial', '2', 1, 1, '', NULL, NULL, 1),
(10, 'Practica Profesionalizante I', '2', 1, 1, '', NULL, NULL, 1),
(11, 'Sistemas Operativos y Redes I', '2', 1, 1, '', NULL, NULL, 1),
(12, 'Marco juridico especifico', '1', 1, 2, NULL, NULL, '', 1),
(13, 'Matemática II', '1', 1, 2, '2', NULL, '2', 1),
(14, 'Sistemas Administrativos', '2', 1, 2, '', NULL, NULL, 1),
(15, 'Programación III', '1', 1, 2, '8', '2', '8', 1),
(16, 'Base de Datos I', '1', 1, 2, '', NULL, NULL, 1),
(17, 'Practica Profesionalizante II', '2', 1, 2, '', '10', '', 1),
(18, 'Matemática III', '2', 1, 2, '13', '2', '13', 1),
(19, 'Sistemas de Información II', '2', 1, 2, '', '4', NULL, 1),
(20, 'Desarrollo de Software I', '1', 1, 2, '', NULL, NULL, 1),
(21, 'Practica Profesionalizante III', '2', 1, 2, '', '17', '', 1),
(22, 'Base de Datos II', '1', 1, 3, '16', NULL, '16', 1),
(23, 'Sistemas Operativos y Redes II', '2', 1, 3, '', '11', NULL, 1),
(24, 'Practica Profesionalizante IV', '1', 1, 3, '', '21', '', 1),
(25, 'Desarrollo de Software II', '1', 1, 3, '', '20', NULL, 1),
(26, 'Etica y deontologia profesional', '2', 1, 3, '', NULL, '1', 1),
(27, 'Practica Profesionalizante V', '2', 1, 3, '', '24', '', 1),
(28, 'Aporte de sociologia y antroprologia a la educacion', '1', 2, 1, NULL, NULL, NULL, 1),
(29, 'Psicologia educacional', '1', 2, 1, NULL, NULL, NULL, 1),
(30, 'Sujeto de nivel inicial I', '1', 2, 1, NULL, NULL, NULL, 1),
(31, 'Practicas de lengua y literatura', '1', 2, 1, NULL, NULL, NULL, 1),
(32, 'Teorias de la educacion', '2', 2, 1, NULL, NULL, NULL, 1),
(33, 'Didactica y curriculum', '2', 2, 1, NULL, NULL, NULL, 1),
(34, 'Iniciacion a las tic', '2', 2, 1, NULL, NULL, NULL, 1),
(35, 'Sujeto de nivel inicial II', '2', 2, 1, NULL, NULL, NULL, 1),
(36, 'Ciencias sociales', '2', 2, 1, NULL, NULL, NULL, 1),
(37, 'Practica I', '1', 2, 1, NULL, NULL, NULL, 1),
(38, 'Historia social argentina y la latinoamericana', '1', 2, 2, '36', NULL, NULL, 1),
(39, 'Didactica de nivel I', '1', 2, 2, '29:30:33', NULL, '30', 1),
(40, 'Matematica', '1', 2, 2, NULL, NULL, NULL, 1),
(41, 'Ciencias naturales', '1', 2, 2, NULL, NULL, NULL, 1),
(42, 'Plástica y su didáctica', '2', 2, 2, '30:33', NULL, NULL, 1),
(43, 'Estado, sociedad y educación', '2', 2, 2, '28:38', NULL, '28', 1),
(44, 'Filosofía y conocimiento', '2', 2, 2, '28:32', NULL, '28', 1),
(45, 'Didactica de nivel II', '2', 2, 2, '35:39', '33', '29:30:35', 1),
(46, 'Literatura infantil', '2', 2, 2, '30:31:33', NULL, NULL, 1),
(47, 'Expresión corporal y su didáctica', '2', 2, 2, '33:35', NULL, NULL, 1),
(48, 'Practica II', '1', 2, 2, '28:30:33', '37', '33', 1),
(49, 'Investigación educativa I', '1', 2, 3, '37:44', '28:', NULL, 1),
(50, 'Didáctica de la lengua y la literatura infantil', '1', 2, 3, NULL, NULL, NULL, 1),
(51, 'Didáctica  de las ciencias sociales', '1', 2, 3, NULL, NULL, NULL, 1),
(52, 'Música y su didáctica', '1', 2, 3, NULL, NULL, NULL, 1),
(53, 'Formación ética y ciudadana', '2', 2, 3, NULL, NULL, NULL, 1),
(54, 'Comunicación, cultura y tic', '2', 2, 3, NULL, NULL, NULL, 1),
(55, 'Didáctica de la matematica', '2', 2, 3, '', NULL, NULL, 1),
(56, 'Didáctica de las ciencias naturales', '2', 2, 3, NULL, NULL, NULL, 1),
(57, 'Educación física en el nivel inicial', '2', 2, 3, NULL, NULL, NULL, 1),
(58, 'Práctica III', '1', 2, 3, NULL, NULL, NULL, 1),
(59, 'Investigación educativa II', '1', 2, 4, NULL, NULL, NULL, 1),
(60, 'UDI I', '1', 2, 4, NULL, NULL, NULL, 1),
(61, 'UDI II (CFE)', '2', 2, 4, NULL, NULL, NULL, 1),
(62, 'UDI III', '2', 2, 4, '', NULL, NULL, 1),
(63, 'Práctica IV', '1', 2, 4, NULL, NULL, NULL, 1),
(64, 'Aporte de sociología y antropología a la educación', '1', 3, 1, NULL, NULL, NULL, 1),
(65, 'Psicologia educacional', '1', 3, 1, NULL, NULL, NULL, 1),
(66, 'Matemática', '1', 3, 1, NULL, NULL, NULL, 1),
(67, 'Ciencias naturales', '1', 3, 1, NULL, NULL, NULL, 1),
(68, 'Teorías de la educación', '2', 3, 1, NULL, NULL, NULL, 1),
(69, 'Iniciación a las TIC', '2', 3, 1, NULL, NULL, NULL, 1),
(70, 'Lengua y literatura', '2', 3, 1, NULL, NULL, NULL, 1),
(71, 'Ciencias sociales', '2', 3, 1, NULL, NULL, NULL, 1),
(72, 'Sujeto de la educación primaria I', '2', 3, 1, NULL, NULL, NULL, 1),
(73, 'Didactica y curriculum', '2', 3, 1, NULL, NULL, NULL, 1),
(74, 'Práctica I', '1', 3, 1, NULL, NULL, NULL, 1),
(75, 'Historia social argentina y latinoamericana', '1', 3, 2, NULL, NULL, NULL, 1),
(76, 'Alfabetización', '1', 3, 2, NULL, NULL, NULL, 1),
(77, 'Didáctica de las ciencias sociales', '1', 3, 2, NULL, NULL, NULL, 1),
(78, 'Sujeto de la educación primaria II', '1', 3, 2, NULL, NULL, NULL, 1),
(79, 'Estado, sociedad y educación', '2', 3, 2, NULL, NULL, NULL, 1),
(80, 'Filosofía y conocimiento', '2', 3, 2, NULL, NULL, NULL, 1),
(81, 'Didáctica de la matematica I', '2', 3, 2, NULL, NULL, NULL, 1),
(82, 'Didáctica de las ciencias Naturales I', '2', 3, 2, NULL, NULL, NULL, 1),
(83, 'Práctica II', '1', 3, 2, NULL, NULL, NULL, 1),
(84, 'Investigación educativa I', '1', 3, 3, NULL, NULL, NULL, 1),
(85, 'Didáctica de la matematica II', '1', 3, 3, NULL, NULL, NULL, 1),
(86, 'Didáctica de las ciencias naturales II', '1', 3, 3, NULL, NULL, NULL, 1),
(87, 'Educación tecnológica', '1', 3, 3, NULL, NULL, NULL, 1),
(88, 'Formación Etica y ciudadana', '2', 3, 3, NULL, NULL, NULL, 1),
(89, 'Comunicación, cultura y TIC', '2', 3, 3, NULL, NULL, NULL, 1),
(90, 'Didáctica de la lengua y la literatura', '2', 3, 3, NULL, NULL, NULL, 1),
(91, 'Didáctica de las ciencias sociales II', '2', 3, 3, NULL, NULL, NULL, 1),
(92, 'Práctica III', '1', 3, 3, NULL, NULL, NULL, 1),
(93, 'Investigación educativa II', '1', 3, 4, NULL, NULL, NULL, 1),
(94, 'UDI I', '1', 3, 4, NULL, NULL, NULL, 1),
(95, 'UDI II (CFE)', '2', 3, 4, NULL, NULL, NULL, 1),
(96, 'UDI III', '2', 3, 4, NULL, NULL, NULL, 1),
(97, 'Práctica IV', '1', 3, 4, NULL, NULL, NULL, 1),
(98, 'Compresiòn y Producciòn de texto', NULL, -4, 1, NULL, NULL, NULL, 1),
(99, 'Contexto Social Econòmico y Productivo', '', 4, 1, NULL, NULL, NULL, 1),
(100, 'Ingles Tècnico I', '', 4, 1, NULL, NULL, NULL, 1),
(101, 'Quimica Aplicada I', '', 4, 1, NULL, NULL, NULL, 1),
(102, 'Matematica Aplicada I', NULL, 4, 1, NULL, NULL, NULL, 1),
(103, 'Geologia', NULL, -4, 1, NULL, NULL, NULL, 1),
(104, 'Compresion y Produccion de textos', NULL, 5, 1, NULL, NULL, NULL, 1),
(105, 'Contexto Socio Economico y Productivo', NULL, 5, 1, NULL, NULL, NULL, 1),
(106, 'Ingles Tecnico I', NULL, 5, 1, NULL, NULL, NULL, 1),
(107, 'Quimica Aplicada I', NULL, 5, 1, NULL, NULL, NULL, 1),
(108, 'Matematica Aplicada I', NULL, 5, 1, NULL, NULL, NULL, 1),
(110, 'Quimica Aplicada II', NULL, 4, 1, NULL, NULL, NULL, 1),
(111, 'Matematica Aplicada II', NULL, 4, 1, NULL, NULL, NULL, 1),
(113, 'Ingles Tècnico II', NULL, 4, 1, NULL, NULL, NULL, 1),
(115, 'Tecnica de prospecciòn', NULL, 4, 1, NULL, NULL, NULL, 1),
(116, 'Fìsica Aplicada I', NULL, 4, 1, NULL, NULL, NULL, 1),
(117, 'Ingles Tecnico III', '1', 4, 2, NULL, NULL, NULL, 1),
(118, 'Portugues Tecnico I', '1', 4, 2, NULL, NULL, NULL, 1),
(119, 'Marketing de Servicios turisticos', '1', 4, 2, NULL, NULL, NULL, 1),
(120, 'Oratoria', '1', 4, 2, NULL, NULL, NULL, 1),
(121, 'Practica Profesionalizante III', '1', 4, 2, NULL, NULL, NULL, 1),
(122, 'Portugues Tecnico II', '2', 4, 2, NULL, NULL, NULL, 1),
(123, 'Informatica Aplicada', '2', 4, 2, NULL, NULL, NULL, 1),
(124, 'Organizacion de Empresas Turisticas', '2', 4, 2, NULL, NULL, NULL, 1),
(125, 'Geografia Turistica II', '2', 4, 2, NULL, NULL, NULL, 1),
(126, 'Sistema de Transporte para el Turismo', '2', 4, 2, NULL, NULL, NULL, 1),
(127, 'Practica Profesionalizante IV', '1', 4, 3, NULL, NULL, NULL, 1),
(128, 'Geologia', NULL, 5, 1, NULL, NULL, NULL, 1),
(129, 'Ingles Tecnico II', NULL, 5, 1, NULL, NULL, NULL, 1),
(130, 'Quimica Aplicada II', NULL, 5, 1, NULL, NULL, NULL, 1),
(131, 'Matematica Aplicada II', NULL, 5, 1, NULL, NULL, NULL, 1),
(132, 'Fisica Aplicada I', NULL, 5, 1, NULL, NULL, NULL, 1),
(133, 'Tecnicas de Prospeccion', NULL, 5, 1, NULL, NULL, NULL, 1),
(134, 'Practicas Profesionalizante I', NULL, 5, 1, NULL, NULL, NULL, 1),
(135, 'Seguridad e Higiene', '1', 5, 2, NULL, NULL, NULL, 1),
(136, 'Ingles Tecnico III', '1', 5, 2, NULL, NULL, NULL, 1),
(137, 'Fisica Aplicada II', '1', 5, 2, NULL, NULL, NULL, 1),
(138, 'Mineralogia y Petrografia', '1', 5, 2, NULL, NULL, NULL, 1),
(139, 'Practica Profesionalizante II', '1', 5, 2, NULL, NULL, NULL, 1),
(140, 'Explotacion Minera', '2', 5, 2, NULL, NULL, NULL, 1),
(141, 'Prospeccion Geofisica I', '2', 5, 2, NULL, NULL, NULL, 1),
(142, 'Prospeccion Geoquimica I', '2', 5, 2, NULL, NULL, NULL, 1),
(143, 'Economia y Costos de Exploracion', '2', 5, 2, NULL, NULL, NULL, 1),
(144, 'Gestion Ambiental Minera', '2', 5, 2, NULL, NULL, NULL, 1),
(145, 'Practica Profesionalizante III', '2', 5, 2, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_materias_alumno`
--

CREATE TABLE `tbl_materias_alumno` (
  `id` int(11) NOT NULL,
  `alumno` varchar(255) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(3) NOT NULL,
  `fecha_cancelacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_materias_alumno`
--

INSERT INTO `tbl_materias_alumno` (`id`, `alumno`, `materia`, `fecha`, `estado`, `fecha_cancelacion`) VALUES
(1, '22720764', 1, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00'),
(2, '22720764', 2, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00'),
(3, '22720764', 3, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00'),
(4, '22720764', 4, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00'),
(5, '22720764', 6, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_materias_alumno_cursa`
--

CREATE TABLE `tbl_materias_alumno_cursa` (
  `id` int(11) NOT NULL,
  `alumno` varchar(255) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `observacion` varchar(512) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(3) NOT NULL,
  `fecha_cancelacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_materias_alumno_mesa`
--

CREATE TABLE `tbl_materias_alumno_mesa` (
  `id` int(11) NOT NULL,
  `alumno` varchar(255) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `observacion` varchar(512) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(3) NOT NULL,
  `fecha_cancelacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_mesas`
--

CREATE TABLE `tbl_mesas` (
  `id` int(11) NOT NULL,
  `mesa` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `cerra_mesa` varchar(255) DEFAULT NULL,
  `estado_cerra` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_mesas`
--

INSERT INTO `tbl_mesas` (`id`, `mesa`, `estado`, `fecha`, `cerra_mesa`, `estado_cerra`) VALUES
(1, 'mesa_agosto_1_llamado', '1', '2025-07-16 20:40:10', '0', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_periodos_inscripcion`
--

CREATE TABLE `tbl_periodos_inscripcion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cuatrimestre` enum('1','2') NOT NULL,
  `anio` year(4) NOT NULL,
  `fecha_inicio_inscripcion` date NOT NULL,
  `fecha_fin_inscripcion` date NOT NULL,
  `fecha_inicio_cursada` date NOT NULL,
  `fecha_fin_cursada` date NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tbl_periodos_inscripcion`
--

INSERT INTO `tbl_periodos_inscripcion` (`id`, `nombre`, `cuatrimestre`, `anio`, `fecha_inicio_inscripcion`, `fecha_fin_inscripcion`, `fecha_inicio_cursada`, `fecha_fin_cursada`, `activo`, `created_at`, `updated_at`) VALUES
(1, '1° Cuatrimestre 2025', '1', '2025', '2025-04-01', '2025-04-03', '2025-04-07', '2025-07-31', 0, '2025-10-01 05:52:21', '2025-10-01 16:03:11'),
(2, '2° Cuatrimestre 2025', '2', '2025', '2025-09-30', '2025-10-03', '2025-08-26', '2025-12-15', 1, '2025-10-01 05:52:22', '2025-10-01 16:03:11'),
(3, '1° Cuatrimestre 2026', '1', '2026', '2026-04-01', '2026-04-03', '2026-04-06', '2026-07-31', 0, '2025-10-01 05:52:22', '2025-10-01 16:03:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_profesores`
--

CREATE TABLE `tbl_profesores` (
  `id` int(11) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `division` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_profesores`
--

INSERT INTO `tbl_profesores` (`id`, `apellido`, `nombre`, `email`, `dni`, `carrera`, `division`) VALUES
(1, 'beron', 'fabian ruben', NULL, '22720764', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_profesor_tiene_materias`
--

CREATE TABLE `tbl_profesor_tiene_materias` (
  `id` int(11) NOT NULL,
  `profesor` int(11) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `cursado` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_profesor_tiene_materias`
--

INSERT INTO `tbl_profesor_tiene_materias` (`id`, `profesor`, `carrera`, `materia`, `cursado`, `division`) VALUES
(1, 1, 1, 16, '2023', '1'),
(3, 2, 1, 11, '', '1'),
(4, 1, 1, 24, '', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_resoluciones`
--

CREATE TABLE `tbl_resoluciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `carrera` int(11) DEFAULT NULL,
  `ano1` int(11) DEFAULT NULL,
  `ano2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_resoluciones`
--

INSERT INTO `tbl_resoluciones` (`id`, `nombre`, `carrera`, `ano1`, `ano2`) VALUES
(1, 'res-8167', 1, 2017, 2019),
(2, 'res-10518', 2, 2015, 2019),
(3, 'res-10519', 3, 2015, 2019),
(4, 'res-274', 1, 2016, 2016),
(5, 'res-1515', 2, 2009, 2014),
(6, 'res-1514', 3, 2009, 2014);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tipos_usuarios`
--

CREATE TABLE `tbl_tipos_usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_tipos_usuarios`
--

INSERT INTO `tbl_tipos_usuarios` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'usuario'),
(3, 'profesor'),
(4, 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `clave` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `pais` int(11) NOT NULL,
  `provincia` int(11) NOT NULL,
  `sexo` int(11) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id`, `nombre`, `usuario`, `clave`, `email`, `telefono`, `tipo`, `pais`, `provincia`, `sexo`, `avatar`) VALUES
(1, 'fabian beron', 'admin', '123456', 'frberon@gmail.com', '12321', 1, 1, 1, 1, 'avatar-mini2.jpg'),
(2, 'puesto1', 'usuario1', '123456', 'frberon@gmail.com', '121321', 2, 1, 2, 1, 'avatar-mini2.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dni` varchar(20) NOT NULL,
  `role` enum('admin','profesor','alumno') NOT NULL DEFAULT 'alumno',
  `alumno_id` int(11) DEFAULT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `dni`, `role`, `alumno_id`, `profesor_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '', 'alumno', NULL, NULL, 'Admin Test', 'test@ies.edu', NULL, '$2y$12$VSwQSomtn9fff6znssiZPuAvoSSZrEhaYL6GJgTAFg4wwgrIZfcR2', NULL, '2025-09-30 17:47:03', '2025-09-30 17:47:03'),
(2, '20829266', 'alumno', 1, NULL, 'Quiroga, Carlos Eduardo', 'quiedu@gmail.com', NULL, '$2y$12$pEjrKW5m0.hGRpYaUznzxeAcj3FLzYGymnXQqOlfd2SkRSwaL0NXa', NULL, '2025-10-01 00:49:59', '2025-10-01 00:49:59'),
(3, '42809162', 'alumno', 2, NULL, 'Moran , Federico ', 'federicojmoran@gmail.com', NULL, '$2y$12$kEvENjuIWZYxMnWQPHXw.uCfQQghrmp3Z17pF/aMyQ/GugzelHJJC', NULL, '2025-10-01 00:50:00', '2025-10-01 00:50:00'),
(4, '45473369', 'alumno', 3, NULL, 'Burgoa, Rosana', 'rosanaburgoa123@gmail.com', NULL, '$2y$12$8E1av/Z0GnBA1dqrPKm6s.mz7cfOAhTy0z5GdwlANDa3HxOwYghya', NULL, '2025-10-01 00:50:00', '2025-10-01 00:50:00'),
(5, '44316616', 'alumno', 4, NULL, 'Echevarria , Carlos Leandro JesÃºs ', 'leandroechevarria0107@gmail.com', NULL, '$2y$12$VNGbgfpcl.M5dIneOqQoqOgWNruZpoXtBhFvXJ2T4oZ1sGXV4mXNG', NULL, '2025-10-01 00:50:01', '2025-10-01 00:50:01'),
(6, '29694457', 'alumno', 5, NULL, 'Peralta CÃ¡ceres , Omar Ariel ', 'omarperalta1408@gmail.com', NULL, '$2y$12$HPrfZj9YdDsfcGllBaDpX.BQt/zI0IS70HozaFtsUEPFhZxNM9Fyy', NULL, '2025-10-01 00:50:01', '2025-10-01 00:50:01'),
(7, '40591552', 'alumno', 6, NULL, 'AgÃ¼ero , Eugenio Ismael ', 'ismael8aguero@gmail.com', NULL, '$2y$12$h0UbwmI0n0h5xAfBxl0fDOtTUCpm4iCmy0q3.yqZ.UnY54nd07FAa', NULL, '2025-10-01 00:50:02', '2025-10-01 00:50:02'),
(8, '46726829', 'alumno', 7, NULL, 'Godoy, AgustÃ­n ', 'godoyagustin802@gmail.com', NULL, '$2y$12$2pqqYPQqy/IHAeK2LShVjerXe.0aK6OvOSfLj2SKa8clEjJBu/ywO', NULL, '2025-10-01 00:50:02', '2025-10-01 00:50:02'),
(9, '40823054', 'alumno', 8, NULL, 'MuÃ±oz, Angela Cecilia', 'angiemunoz432@gmail.com', NULL, '$2y$12$jh4.4bdPLaOextavyFM9Gu8etOuLKVx8hPUVKifXsOozl2dlKu5fa', NULL, '2025-10-01 00:50:03', '2025-10-01 00:50:03'),
(10, '46407762', 'alumno', 9, NULL, 'AgÃ¼ero, Yamil', 'yaguerogil@gmail.com', NULL, '$2y$12$h2B2IjUcsmHU3SqSvnTEneKIj2LfciD0/cd9fmToDyT6sKo4h8viK', NULL, '2025-10-01 00:50:03', '2025-10-01 00:50:03'),
(11, '35852955', 'alumno', 10, NULL, 'ArgaÃ±araz Diaz, Eliana Gabriela ', 'Elianaargdiaz@gmail.com', NULL, '$2y$12$IwMGp.qFW4rpKtHaracQ0unQPTaYz.y1Y.GzCiOKO5vLCOB/Ti5x.', NULL, '2025-10-01 00:50:03', '2025-10-01 00:50:03'),
(12, '43690003', 'alumno', 11, NULL, 'Reyes , Mateo ', 'reyesmateo988@gmail.com', NULL, '$2y$12$zeGcPcbkAcCPdfd7dR9RCuRDjtGCoWYElphRpHYul5N6e0jN75PQe', NULL, '2025-10-01 00:50:04', '2025-10-01 00:50:04'),
(13, '42712320', 'alumno', 12, NULL, 'Fonzalida , Melina ', 'fonzalidamelina1@gmail.com', NULL, '$2y$12$vHry7LaqpRVuiMrSImpVUOCWFR2F7.8IIDJtn1RKGhX8.o6tYnSTK', NULL, '2025-10-01 00:50:04', '2025-10-01 00:50:04'),
(14, '43488986', 'alumno', 13, NULL, 'MuÃ±oz, Fernando', 'munozfernando264@gmail.com', NULL, '$2y$12$HcAPSArjnS2FPX8h7PJTyOivKyjDUFcJ5w/xuYwuWDs46OiaantVS', NULL, '2025-10-01 00:50:05', '2025-10-01 00:50:05'),
(15, '43641793', 'alumno', 14, NULL, 'Balmaceda, Karen', 'balmacedaemilce290@gmail.com', NULL, '$2y$12$1Qs.BvmRVxsMLEf1KTwTluL5hSdIxVDilAs5YnVqVrtVuo/yjsiFu', NULL, '2025-10-01 00:50:05', '2025-10-01 00:50:05'),
(16, '40086620', 'alumno', 15, NULL, 'Simeoni, Facundo', 'facundosimeoni1@gmail.com', NULL, '$2y$12$bus1Mx31ke8aV4ALepzybO2Io.nfTQRV4f6j8x5.ZFaZnL1OwqE1K', NULL, '2025-10-01 00:50:06', '2025-10-01 00:50:06'),
(17, '46180633', 'alumno', 16, NULL, 'Areyuna, Ramon', 'ramonareyuna09@gmail.com', NULL, '$2y$12$cjHlzoEHKs8LBTiwwMisou43wFeJZQqni5HNtS61m1A4xvTJGzsRy', NULL, '2025-10-01 00:50:06', '2025-10-01 00:50:06'),
(18, '43689779', 'alumno', 17, NULL, 'Molina , Ricardo Nahuel', 'nahuelmoli270@gmail.com', NULL, '$2y$12$GeTHllbxUzmiNaPxQMukQemZfp9U098vyl1wnH.lRXK5qyPbiDf1m', NULL, '2025-10-01 00:50:06', '2025-10-01 00:50:06'),
(19, '44062183', 'alumno', 18, NULL, 'Sarmiento, Mathias', 'mathisarmiento6@gmail.com', NULL, '$2y$12$hb1qIkitqkZazSDZ4gVHSe6m4ZQvbG1sby3pXBrXghK/wdQKeTPC2', NULL, '2025-10-01 00:50:07', '2025-10-01 00:50:07'),
(20, '45473305', 'alumno', 19, NULL, 'Garibay, Ramiro', 'ramirogaribay69@gmail.com', NULL, '$2y$12$daTwwIVeGDyzTMAO1ACU9OUwtQYTtThtMid6sovJRRXTf2mmXe0pe', NULL, '2025-10-01 00:50:07', '2025-10-01 00:50:07'),
(21, '44018317', 'alumno', 20, NULL, 'Castro, Exequiel', 'exeloc3@gmail.com', NULL, '$2y$12$nBBdQAXzVVvoPuVL2u500O43LFMk4OXiJ6ErkiUeJAesoeLorruKK', NULL, '2025-10-01 00:50:08', '2025-10-01 00:50:08'),
(22, '45634862', 'alumno', 21, NULL, 'Diaz, Ariana', 'ad421637@gmail.com', NULL, '$2y$12$aa0/0y.ADoiwmmnsz7maEepJM/E36XeP6x6PbU3Aoc79C9vwZP7yu', NULL, '2025-10-01 00:50:08', '2025-10-01 00:50:08'),
(23, '34100722', 'alumno', 22, NULL, 'Garcia Martin , Victor Ariel', 'victorarielgarciamartin@hotmail.com', NULL, '$2y$12$OZcf4nydXpfUMwpZO.8bvOdbaG/AraWYFOefih9ntPmUb5hU.BB3.', NULL, '2025-10-01 00:50:09', '2025-10-01 00:50:09'),
(24, '45473301', 'alumno', 23, NULL, 'Fernandez, Amilcar Jose Leonel', 'amilcarlfernandez310@gmail.com', NULL, '$2y$12$/fTF2IW0P9U7gBU7SISufektPDqJNZ1umSHEWwmpNQNbhXJLRnRWe', NULL, '2025-10-01 00:50:09', '2025-10-01 00:50:09'),
(25, '45635063', 'alumno', 24, NULL, 'Aballay Sepeda, Eli Valentina', 'eliaballay534@gmail.com', NULL, '$2y$12$u9zWn/ju/Va7uN7znr9S1e1rqlZagGXF7Ai6BLzBULBprrZFAFVQS', NULL, '2025-10-01 00:50:10', '2025-10-01 00:50:10'),
(26, '44634748', 'alumno', 25, NULL, 'Gomez Gimenez , Luis Gabriel ', 'luisgomez98654@gmail.com', NULL, '$2y$12$OIGLwfg4cv5WFShKWSKbzOSC8WndBgQhxNSkcLBYHEbJuBLAs1wk2', NULL, '2025-10-01 00:50:10', '2025-10-01 00:50:10'),
(27, '46070642', 'alumno', 26, NULL, 'Vieyra, Matias', 'matiashidalgo147@gmail.com', NULL, '$2y$12$uNBgBwC.gJGFrTjOPrgP9edXqLzYtKqr97oamEMVneWPCXqBWG.Ry', NULL, '2025-10-01 00:50:10', '2025-10-01 00:50:10'),
(28, '42081141', 'alumno', 27, NULL, 'Gimenez, Leandro Albano', 'albanogimenez062@gmail.com', NULL, '$2y$12$5T4y5kXN24LywIbgPlpj8OhEvMVZSc61ueOAE8RI8aXZiALhyv2eC', NULL, '2025-10-01 00:50:11', '2025-10-01 00:50:11'),
(29, '45212175', 'alumno', 28, NULL, 'Bustos, Juan', 'juanixd873@gmail.com', NULL, '$2y$12$zmof8AT3.IaNPYHNSEh3zuHGCbDZFL/WeFdLCrhNB1ccXoEINPHZm', NULL, '2025-10-01 00:50:11', '2025-10-01 00:50:11'),
(31, '4521175', 'alumno', 29, NULL, 'Bustos, Juan', '4521175@ies.edu.ar', NULL, '$2y$12$LKV5JMDMVSv/JaeMovv0de7y2XUzWyBqMKZCj2pIHvbfAfy5oUkBi', NULL, '2025-10-01 00:52:03', '2025-10-01 00:52:03'),
(32, '41641247', 'alumno', 30, NULL, 'Ferreyra, Yamila', 'Yamilaferreyra444@gmail.com', NULL, '$2y$12$Doeety6.yzzNM0A5W53E..uHJAekPIrH/MaF2/F7BXbtEZXsJkJ2i', NULL, '2025-10-01 00:52:03', '2025-10-01 00:52:03'),
(33, '35850632', 'alumno', 31, NULL, 'Ramirez, Emiliano,emanuel', 'emilianoramirez2091@gmail.com', NULL, '$2y$12$NHbJMaS0kMjJgd/9qAK/TumG39B7lSHQb72DFNDVSKqO.9B3kVPK.', NULL, '2025-10-01 00:52:04', '2025-10-01 00:52:04'),
(34, '44674217', 'alumno', 32, NULL, 'rodriguez, alan', 'rodriguezalanm731@gmail.com', NULL, '$2y$12$tNgCdkSXGXEGUq40l2DMKuKk5SQN.MpzJfoX5p5iT0WFInuloAzhO', NULL, '2025-10-01 00:52:04', '2025-10-01 00:52:04'),
(35, '42516938', 'alumno', 33, NULL, 'Guaquinchay , AyelÃ©n RocÃ­o ', 'ayelen.guaquinchay09@gmail.com', NULL, '$2y$12$Aw.oNZZ/NPLBuRKGC2QFne4LMN2A88EmlaIeWNvNn6A/oyMOkS0Ui', NULL, '2025-10-01 00:52:04', '2025-10-01 00:52:04'),
(36, '33836289', 'alumno', 34, NULL, 'Navarro , Maximiliano ', 'sirmaximilian1@hotmail.com', NULL, '$2y$12$kVVlxkPVfV9xGhjonmjHM.tqNbq7rg.N/rvrOseqRimXwsMaZCbvy', NULL, '2025-10-01 00:52:05', '2025-10-01 00:52:05'),
(37, '34609402', 'alumno', 35, NULL, 'Carrizo , MatÃ­as Gabriel ', 'matias.g.carrizo11@gmail.com', NULL, '$2y$12$QFcd.1ZBcodI9TjVldYmxOw2jkyZNTzgdGUBdj7lwSBcNiXCOf59i', NULL, '2025-10-01 00:52:05', '2025-10-01 00:52:05'),
(38, '42005854', 'alumno', 36, NULL, 'Cordoba, Maximiliano ', 'maxcordoba100@gmail.com', NULL, '$2y$12$yQyl.zfS0FbH12mR.u58VOXQOfb7OmNEMzNP5JgGxFiSICNmuqLJq', NULL, '2025-10-01 00:52:06', '2025-10-01 00:52:06'),
(39, '45634685', 'alumno', 37, NULL, 'Garcia , Dayana', 'dayimarilau@gmail.com', NULL, '$2y$12$R2at9SeP3ZmY7VXUqKLZ6eHd8yoDi.ntrK7OR/RKflFrYDyE4seoO', NULL, '2025-10-01 00:52:06', '2025-10-01 00:52:06'),
(40, '43952965', 'alumno', 38, NULL, 'Toledo, SebastiÃ¡n', 'sebatoledo.sdt@gmail.com', NULL, '$2y$12$9LtmH3/duEz4SmKj.q3oveA3ohNZE6rDFrF/SDwleWcpWKdNO2AYu', NULL, '2025-10-01 00:52:06', '2025-10-01 00:52:06'),
(41, '42187883', 'alumno', 39, NULL, 'Uribi, Antonio ', 'uribeantonio078@gmail.com', NULL, '$2y$12$NilRInxPZd16o9nivXTeJusdrUXlLqmpDVjebWrEcTSSzR7ZVmnye', NULL, '2025-10-01 00:52:07', '2025-10-01 00:52:07'),
(42, '46544506', 'alumno', 40, NULL, 'Moyano, Braian', 'braiankevinmoyano@gmail.com', NULL, '$2y$12$WToBpfXfs/nd0jWH3WLFHukVgV5D.an62eZi96cRwpF6KrxP2E.lO', NULL, '2025-10-01 00:52:07', '2025-10-01 00:52:07'),
(43, '43952026', 'alumno', 41, NULL, 'Montenegro, Nara Fabiana', 'nara74578@gmail.com', NULL, '$2y$12$9EB6eqOjYKQe38JwKLDYFu6WWq49fVkbw4xebkcn11hpGO0uUyuLi', NULL, '2025-10-01 00:52:08', '2025-10-01 00:52:08'),
(44, '42356568', 'alumno', 42, NULL, 'Castillo, Rodrigo', 'rofcastillo@gmail.com', NULL, '$2y$12$6f6sI5yQ1ez7.w11o235Me7DVLCirAuh/V7MEPxrWx.6qWnD/SUKO', NULL, '2025-10-01 00:52:08', '2025-10-01 00:52:08'),
(45, '44527380', 'alumno', 68, NULL, 'Fiorelli, Santino', 'santifiorelli27@gmail.com', NULL, '$2y$12$GymWolBnO7vlqCbqFR4xqudM8.XmJ8r6719KPCEgTRo593RwFeWJe', NULL, '2025-10-01 00:52:09', '2025-10-01 00:52:09'),
(47, '12345678', 'alumno', 282, NULL, 'Alumno, Prueba', '12345678@ies.edu.ar', NULL, '$2y$12$AWcJ5KB9zotu2OfN6G/YbOFMCFvmKXCTtfU6o3qGy.YhQ/TucmsO.', NULL, '2025-10-01 17:23:53', '2025-10-01 17:23:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `reglas_correlativas`
--
ALTER TABLE `reglas_correlativas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reglas_correlativas_materia_id_index` (`materia_id`),
  ADD KEY `reglas_correlativas_carrera_id_index` (`carrera_id`),
  ADD KEY `reglas_correlativas_materia_id_carrera_id_tipo_index` (`materia_id`,`carrera_id`,`tipo`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tbl_alumnos`
--
ALTER TABLE `tbl_alumnos`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_alumnos_cursa`
--
ALTER TABLE `tbl_alumnos_cursa`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_alumnos_materias`
--
ALTER TABLE `tbl_alumnos_materias`
  ADD PRIMARY KEY (`Id`) USING BTREE;

--
-- Indices de la tabla `tbl_alumnos_mesa`
--
ALTER TABLE `tbl_alumnos_mesa`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_carreras`
--
ALTER TABLE `tbl_carreras`
  ADD PRIMARY KEY (`Id`) USING BTREE;

--
-- Indices de la tabla `tbl_cursar`
--
ALTER TABLE `tbl_cursar`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_materias`
--
ALTER TABLE `tbl_materias`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_materias_alumno`
--
ALTER TABLE `tbl_materias_alumno`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_materias_alumno_cursa`
--
ALTER TABLE `tbl_materias_alumno_cursa`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_materias_alumno_mesa`
--
ALTER TABLE `tbl_materias_alumno_mesa`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_mesas`
--
ALTER TABLE `tbl_mesas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_periodos_inscripcion`
--
ALTER TABLE `tbl_periodos_inscripcion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_profesores`
--
ALTER TABLE `tbl_profesores`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_profesor_tiene_materias`
--
ALTER TABLE `tbl_profesor_tiene_materias`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_resoluciones`
--
ALTER TABLE `tbl_resoluciones`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_tipos_usuarios`
--
ALTER TABLE `tbl_tipos_usuarios`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_dni_unique` (`dni`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reglas_correlativas`
--
ALTER TABLE `reglas_correlativas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_alumnos`
--
ALTER TABLE `tbl_alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT de la tabla `tbl_alumnos_cursa`
--
ALTER TABLE `tbl_alumnos_cursa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_alumnos_materias`
--
ALTER TABLE `tbl_alumnos_materias`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2677;

--
-- AUTO_INCREMENT de la tabla `tbl_alumnos_mesa`
--
ALTER TABLE `tbl_alumnos_mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_carreras`
--
ALTER TABLE `tbl_carreras`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_cursar`
--
ALTER TABLE `tbl_cursar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_materias`
--
ALTER TABLE `tbl_materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT de la tabla `tbl_materias_alumno`
--
ALTER TABLE `tbl_materias_alumno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_materias_alumno_cursa`
--
ALTER TABLE `tbl_materias_alumno_cursa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_materias_alumno_mesa`
--
ALTER TABLE `tbl_materias_alumno_mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_mesas`
--
ALTER TABLE `tbl_mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_periodos_inscripcion`
--
ALTER TABLE `tbl_periodos_inscripcion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_profesores`
--
ALTER TABLE `tbl_profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_profesor_tiene_materias`
--
ALTER TABLE `tbl_profesor_tiene_materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_resoluciones`
--
ALTER TABLE `tbl_resoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tbl_tipos_usuarios`
--
ALTER TABLE `tbl_tipos_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
