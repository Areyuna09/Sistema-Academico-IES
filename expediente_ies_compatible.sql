/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80403
 Source Host           : localhost:3306
 Source Schema         : expediente_ies

 Target Server Type    : MySQL
 Target Server Version : 80403
 File Encoding         : 65001

 Date: 17/09/2025 18:31:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cursar_agosto_2_semestre
-- ----------------------------
DROP TABLE IF EXISTS `cursar_agosto_2_semestre`;
CREATE TABLE `cursar_agosto_2_semestre`  (
  `id` int NOT NULL,
  `alumno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `estado` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `observacion` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_cancelacion` datetime NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cursar_agosto_2_semestre
-- ----------------------------
INSERT INTO `cursar_agosto_2_semestre` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `cursar_agosto_2_semestre` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);

-- ----------------------------
-- Table structure for mesa_agosoto_2_llamado
-- ----------------------------
DROP TABLE IF EXISTS `mesa_agosoto_2_llamado`;
CREATE TABLE `mesa_agosoto_2_llamado`  (
  `id` int NOT NULL,
  `alumno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `estado` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `observacion` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_cancelacion` datetime NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of mesa_agosoto_2_llamado
-- ----------------------------
INSERT INTO `mesa_agosoto_2_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosoto_2_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);

-- ----------------------------
-- Table structure for mesa_agosto_1_llamado
-- ----------------------------
DROP TABLE IF EXISTS `mesa_agosto_1_llamado`;
CREATE TABLE `mesa_agosto_1_llamado`  (
  `id` int NOT NULL,
  `alumno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `estado` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `observacion` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha_cancelacion` datetime NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of mesa_agosto_1_llamado
-- ----------------------------
INSERT INTO `mesa_agosto_1_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (1, '40086620', 18, '2025-07-15 19:40:53', NULL, 'Alumno 40086620 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (2, '40591552', 13, '2025-07-15 19:49:14', NULL, 'Alumno 40591552 esta en CONDICIONES', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (3, '40823054', 16, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 9 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (4, '40823054', 20, '2025-07-15 20:26:22', NULL, 'Alumno 40823054 esta en CONDICIONES y nota 10 fecha 2023-06-29', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (5, '45473305', 22, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 45473305 esta en CONDICIONES materia: 16nota 8 fecha 2024-07-03', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (6, '45473305', 23, '2025-07-16 19:40:25', NULL, 'Alumno 45473305 en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (7, '41641247', 24, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (8, '41641247', 25, '2025-07-16 20:27:25', NULL, 'Alumno 41641247  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (9, '44018317', 26, '2025-07-16 20:30:31', NULL, 'Alumno 44018317 NO esta en CONDICIONES en la materia que se inscribio.<br> Alumno 44018317 esta en CONDICIONES materia: 1nota 7 fecha 2022-06-27', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (10, '44018109', 16, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);
INSERT INTO `mesa_agosto_1_llamado` VALUES (11, '44018109', 17, '2025-07-16 20:33:29', NULL, 'Alumno 44018109  NO esta en CONDICIONES en la materia que se inscribio.', NULL);

-- ----------------------------
-- Table structure for tbl_alumnos
-- ----------------------------
DROP TABLE IF EXISTS `tbl_alumnos`;
CREATE TABLE `tbl_alumnos`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `dni` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `apellido` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `curso` int NOT NULL,
  `division` int NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `celular` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `legajo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `anno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `materia` int NOT NULL DEFAULT 0,
  `turno` int NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 281 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_alumnos
-- ----------------------------
INSERT INTO `tbl_alumnos` VALUES (1, '20829266', 'Quiroga', 'Carlos Eduardo', 0, 2, 'quiedu@gmail.com', '+542644001533', '+542644001533', NULL, '2022', 1, 0, 0, '2023-07-26 09:08:49');
INSERT INTO `tbl_alumnos` VALUES (2, '42809162', 'Moran ', 'Federico ', 0, 1, 'federicojmoran@gmail.com', '-', '2644534735', NULL, '2021', 1, 0, 0, '2023-07-26 09:13:57');
INSERT INTO `tbl_alumnos` VALUES (3, '45473369', 'Burgoa', 'Rosana', 0, 2, 'rosanaburgoa123@gmail.com', '2644398404', '2644398404', NULL, '2', 1, 0, 0, '2023-07-26 09:55:04');
INSERT INTO `tbl_alumnos` VALUES (4, '44316616', 'Echevarria ', 'Carlos Leandro JesÃºs ', 0, 2, 'leandroechevarria0107@gmail.com', '', '2644054399', NULL, '2023', 1, 0, 0, '2023-07-26 10:34:56');
INSERT INTO `tbl_alumnos` VALUES (5, '29694457', 'Peralta CÃ¡ceres ', 'Omar Ariel ', 0, 1, 'omarperalta1408@gmail.com', '', '2644819470', NULL, '2023', 1, 0, 0, '2023-07-26 10:38:35');
INSERT INTO `tbl_alumnos` VALUES (6, '40591552', 'AgÃ¼ero ', 'Eugenio Ismael ', 0, 2, 'ismael8aguero@gmail.com', '', '2645718934', NULL, '2023', 1, 0, 0, '2023-07-26 10:51:09');
INSERT INTO `tbl_alumnos` VALUES (7, '46726829', 'Godoy', 'AgustÃ­n ', 0, 1, 'godoyagustin802@gmail.com', '', '2645482409', NULL, '2023', 1, 0, 0, '2023-07-26 11:47:21');
INSERT INTO `tbl_alumnos` VALUES (8, '40823054', 'MuÃ±oz', 'Angela Cecilia', 0, 2, 'angiemunoz432@gmail.com', '2644845105', '2644845105', NULL, '2022', 1, 0, 0, '2023-07-26 12:10:27');
INSERT INTO `tbl_alumnos` VALUES (9, '46407762', 'AgÃ¼ero', 'Yamil', 0, 1, 'yaguerogil@gmail.com', '2644434072', '2644434072', NULL, '2023', 1, 0, 0, '2023-07-26 12:46:10');
INSERT INTO `tbl_alumnos` VALUES (10, '35852955', 'ArgaÃ±araz Diaz', 'Eliana Gabriela ', 0, 2, 'Elianaargdiaz@gmail.com', '', '2646039934', NULL, '2023', 1, 0, 0, '2023-07-26 13:18:05');
INSERT INTO `tbl_alumnos` VALUES (11, '43690003', 'Reyes ', 'Mateo ', 0, 2, 'reyesmateo988@gmail.com', '2644046033', '2644046033', NULL, '2023', 1, 0, 0, '2023-07-26 13:37:41');
INSERT INTO `tbl_alumnos` VALUES (12, '42712320', 'Fonzalida ', 'Melina ', 0, 1, 'fonzalidamelina1@gmail.com', '', '2644720921', NULL, '2023', 1, 0, 0, '2023-07-26 14:32:09');
INSERT INTO `tbl_alumnos` VALUES (13, '43488986', 'MuÃ±oz', 'Fernando', 0, 1, 'munozfernando264@gmail.com', '', '2645259424', NULL, '2022', 1, 0, 0, '2023-07-26 15:56:32');
INSERT INTO `tbl_alumnos` VALUES (14, '43641793', 'Balmaceda', 'Karen', 0, 2, 'balmacedaemilce290@gmail.com', '', '2645882800', NULL, '2022', 1, 0, 0, '2023-07-26 18:10:24');
INSERT INTO `tbl_alumnos` VALUES (15, '40086620', 'Simeoni', 'Facundo', 0, 1, 'facundosimeoni1@gmail.com', '2646293463', '2646293463', NULL, '2019', 1, 0, 0, '2023-07-26 18:12:53');
INSERT INTO `tbl_alumnos` VALUES (16, '46180633', 'Areyuna', 'Ramon', 0, 1, 'ramonareyuna09@gmail.com', '2645839327', '2645839327', NULL, '2023', 1, 0, 0, '2023-07-26 19:45:06');
INSERT INTO `tbl_alumnos` VALUES (17, '43689779', 'Molina ', 'Ricardo Nahuel', 0, 1, 'nahuelmoli270@gmail.com', '', '2646222825', NULL, '2023', 1, 0, 0, '2023-07-26 19:48:07');
INSERT INTO `tbl_alumnos` VALUES (18, '44062183', 'Sarmiento', 'Mathias', 0, 1, 'mathisarmiento6@gmail.com', '2645677254', '2645677254', NULL, '2023', 1, 0, 0, '2023-07-26 19:48:21');
INSERT INTO `tbl_alumnos` VALUES (19, '45473305', 'Garibay', 'Ramiro', 0, 2, 'ramirogaribay69@gmail.com', '', '2645769672', NULL, '2023', 1, 0, 0, '2023-07-26 19:48:50');
INSERT INTO `tbl_alumnos` VALUES (20, '44018317', 'Castro', 'Exequiel', 0, 1, 'exeloc3@gmail.com', '(264) 577-0761', '2645770761', NULL, '2022', 1, 0, 0, '2023-07-26 19:49:58');
INSERT INTO `tbl_alumnos` VALUES (21, '45634862', 'Diaz', 'Ariana', 0, 2, 'ad421637@gmail.com', '265457202', '2645457202', NULL, '2023', 1, 0, 0, '2023-07-26 19:50:51');
INSERT INTO `tbl_alumnos` VALUES (22, '34100722', 'Garcia Martin ', 'Victor Ariel', 0, 2, 'victorarielgarciamartin@hotmail.com', '2645150526', '2645150526', NULL, '2023', 1, 0, 0, '2023-07-26 19:55:08');
INSERT INTO `tbl_alumnos` VALUES (23, '45473301', 'Fernandez', 'Amilcar Jose Leonel', 0, 1, 'amilcarlfernandez310@gmail.com', '', '2645787558', NULL, '2023', 1, 0, 0, '2023-07-26 20:01:18');
INSERT INTO `tbl_alumnos` VALUES (24, '45635063', 'Aballay Sepeda', 'Eli Valentina', 0, 1, 'eliaballay534@gmail.com', '2645117870', '2645117870', NULL, '2023', 1, 0, 0, '2023-07-26 20:10:46');
INSERT INTO `tbl_alumnos` VALUES (25, '44634748', 'Gomez Gimenez ', 'Luis Gabriel ', 0, 1, 'luisgomez98654@gmail.com', '', '2646234290', NULL, '2023', 1, 0, 0, '2023-07-26 23:37:27');
INSERT INTO `tbl_alumnos` VALUES (26, '46070642', 'Vieyra', 'Matias', 0, 1, 'matiashidalgo147@gmail.com', '2644361541', '2644361541', NULL, '2023', 1, 0, 0, '2023-07-26 23:42:12');
INSERT INTO `tbl_alumnos` VALUES (27, '42081141', 'Gimenez', 'Leandro Albano', 0, 2, 'albanogimenez062@gmail.com', '', '2644715192', NULL, '2020', 1, 0, 0, '2023-07-27 00:30:32');
INSERT INTO `tbl_alumnos` VALUES (28, '45212175', 'Bustos', 'Juan', 0, 1, 'juanixd873@gmail.com', '2644893444', '2644893444', NULL, '2023', 1, 0, 0, '2023-07-27 08:04:34');
INSERT INTO `tbl_alumnos` VALUES (29, '4521175', 'Bustos', 'Juan', 0, 1, 'juanixd873@gmail.com', '2644893444', '2644893444', NULL, '2023', 1, 0, 0, '2023-07-27 08:05:44');
INSERT INTO `tbl_alumnos` VALUES (30, '41641247', 'Ferreyra', 'Yamila', 0, 1, 'Yamilaferreyra444@gmail.com', '', '2645892359', NULL, '1', 1, 0, 0, '2023-07-27 09:19:24');
INSERT INTO `tbl_alumnos` VALUES (31, '35850632', 'Ramirez', 'Emiliano,emanuel', 0, 1, 'emilianoramirez2091@gmail.com', '', '2646268639', NULL, '2023', 1, 0, 0, '2023-07-27 11:33:07');
INSERT INTO `tbl_alumnos` VALUES (32, '44674217', 'rodriguez', 'alan', 0, 2, 'rodriguezalanm731@gmail.com', '02645296845', '2645263370', NULL, '1', 1, 0, 0, '2023-07-27 12:21:37');
INSERT INTO `tbl_alumnos` VALUES (33, '42516938', 'Guaquinchay ', 'AyelÃ©n RocÃ­o ', 0, 1, 'ayelen.guaquinchay09@gmail.com', '', '2644681308', NULL, '2023', 1, 0, 0, '2023-07-27 12:46:19');
INSERT INTO `tbl_alumnos` VALUES (34, '33836289', 'Navarro ', 'Maximiliano ', 0, 2, 'sirmaximilian1@hotmail.com', '2644144421', '2644144421', NULL, '2020', 1, 0, 0, '2023-07-27 14:07:42');
INSERT INTO `tbl_alumnos` VALUES (35, '34609402', 'Carrizo ', 'MatÃ­as Gabriel ', 0, 2, 'matias.g.carrizo11@gmail.com', '', '2644813560', NULL, '2023', 1, 0, 0, '2023-07-27 15:25:32');
INSERT INTO `tbl_alumnos` VALUES (36, '42005854', 'Cordoba', 'Maximiliano ', 0, 1, 'maxcordoba100@gmail.com', '2646619081', '2646619081', NULL, '2022', 1, 0, 0, '2023-07-27 17:37:20');
INSERT INTO `tbl_alumnos` VALUES (37, '45634685', 'Garcia ', 'Dayana', 0, 2, 'dayimarilau@gmail.com', '', '2644411948', NULL, '2023', 1, 0, 0, '2023-07-27 18:03:57');
INSERT INTO `tbl_alumnos` VALUES (38, '43952965', 'Toledo', 'SebastiÃ¡n', 0, 2, 'sebatoledo.sdt@gmail.com', '2646720761', '2646720761', NULL, '2023', 1, 0, 0, '2023-07-27 18:07:46');
INSERT INTO `tbl_alumnos` VALUES (39, '42187883', 'Uribi', 'Antonio ', 0, 2, 'uribeantonio078@gmail.com', '', '2645710511', NULL, '2023', 1, 0, 0, '2023-07-27 18:12:06');
INSERT INTO `tbl_alumnos` VALUES (40, '46544506', 'Moyano', 'Braian', 0, 1, 'braiankevinmoyano@gmail.com', '2644893834', '2644893834', NULL, '2023', 1, 0, 0, '2023-07-27 18:13:14');
INSERT INTO `tbl_alumnos` VALUES (41, '43952026', 'Montenegro', 'Nara Fabiana', 0, 1, 'nara74578@gmail.com', '2645796815', '2645796815', NULL, '2023', 1, 0, 0, '2023-07-27 18:50:36');
INSERT INTO `tbl_alumnos` VALUES (42, '42356568', 'Castillo', 'Rodrigo', 0, 1, 'rofcastillo@gmail.com', '4315523', '2645450921', NULL, '2022', 1, 0, 0, '2023-07-27 19:52:18');
INSERT INTO `tbl_alumnos` VALUES (68, '44527380', 'Fiorelli', 'Santino', 0, 2, 'santifiorelli27@gmail.com', '4963227', '2644622255', NULL, '2022', 1, 0, 0, '2023-08-08 11:35:44');
INSERT INTO `tbl_alumnos` VALUES (69, '42081141', 'Gimenez', 'Leandro Albano', 0, 2, 'albanogimenez062@gmail.com', '', '2644715495', NULL, '2020', 1, 0, 0, '2023-08-08 11:48:17');
INSERT INTO `tbl_alumnos` VALUES (70, '40823054', 'MuÃ±oz', 'Angela Cecilia', 0, 2, 'angiemunoz432@gmail.com', '2644845105', '2644845105', NULL, '2022', 1, 0, 0, '2023-08-08 12:00:13');
INSERT INTO `tbl_alumnos` VALUES (73, '44018519', 'Palta', 'Luciano', 0, 2, 'Lucianoalejandropalta@gmail.com', '4963930', '2645790362', NULL, '2023', 1, 0, 0, '2023-08-08 15:24:52');
INSERT INTO `tbl_alumnos` VALUES (74, '44248877', 'Saavedra ', 'Jeremias Gerardo', 0, 2, 'Jeremiassaavedra99@gmail.com', '2645726540', '2645726540', NULL, '2023', 1, 0, 0, '2023-08-08 15:35:03');
INSERT INTO `tbl_alumnos` VALUES (75, '25590289', 'Aguero', 'Jorge Luis', 0, 1, 'flacobass@gmail.com', '02644963279', '2646238119', NULL, '2021', 1, 0, 0, '2023-08-08 16:05:23');
INSERT INTO `tbl_alumnos` VALUES (76, '45635742', 'Orosco', 'Matias ', 0, 2, 'oroscon35@gmail.com', '', '2645769084', NULL, '2022', 1, 0, 0, '2023-08-08 16:34:17');
INSERT INTO `tbl_alumnos` VALUES (77, '42334964', 'Paredes', 'Tomas', 0, 0, 'tomasparedes764@gmail.com', '', '+5492644699952', NULL, '2022', 1, 0, 0, '2023-08-08 19:05:19');
INSERT INTO `tbl_alumnos` VALUES (82, '44527338', 'GarcÃ­a ', 'Daniel ', 0, 2, 'daniestebangarciasj@gmail.com', '', '2644831555', NULL, '2022', 1, 0, 0, '2023-08-09 10:50:14');
INSERT INTO `tbl_alumnos` VALUES (84, '41122633', 'Sirerol', 'Mateo', 0, 1, 'mateo.sirerolsanchez9@gmail.com', '2644962105', '2645404301', NULL, '2020', 1, 0, 0, '2023-08-09 13:46:32');
INSERT INTO `tbl_alumnos` VALUES (86, '41814431', 'Ozan Pastran ', 'Facundo Emiliano ', 0, 1, 'ozanfacundo13@gmail.com', '', '2645406894', NULL, '2023', 1, 0, 0, '2023-08-09 16:29:52');
INSERT INTO `tbl_alumnos` VALUES (89, '45212726', 'Cortez', 'Fabrizio ', 0, 1, 'fabriziocortez98325@gmail.com', '2644858479', '2644858479', NULL, '2023', 1, 0, 0, '2023-08-09 17:00:00');
INSERT INTO `tbl_alumnos` VALUES (90, '37609402', 'Carrizo ', 'MatÃ­as ', 0, 2, 'matias.g.carrizo11@gmail.com', '', '2644813560', NULL, '2023', 1, 0, 0, '2023-08-09 17:32:43');
INSERT INTO `tbl_alumnos` VALUES (91, '46476829', 'Godoy', 'Agustin', 0, 1, 'godoyagustin802@gmail.com', '2645482409', '2645482409', NULL, '2023', 1, 0, 0, '2023-08-09 17:35:16');
INSERT INTO `tbl_alumnos` VALUES (92, '46258855', 'Antunez ', 'Fabricio ', 0, 1, 'fabriciomaximiliano.68@gmail.com', '2644671539', '2644671539', NULL, '2023', 1, 0, 0, '2023-08-09 17:44:36');
INSERT INTO `tbl_alumnos` VALUES (94, '38458530', 'Jorquera', 'Daniel', 0, 2, 'danieljorqueraa130@yahoo.com', '2646271346', '2646271346', NULL, '2023', 1, 0, 0, '2023-08-09 17:50:03');
INSERT INTO `tbl_alumnos` VALUES (96, '45634862', 'Diaz Correa', 'Ariana', 0, 2, 'ad421637@gmail.com', '2645457202', '2645457202', NULL, '2023', 1, 0, 0, '2023-08-09 21:14:29');
INSERT INTO `tbl_alumnos` VALUES (127, '40273506', 'Ocaranza ', 'Natalia ocaranza ', 0, 0, 'nataliaocaranza89@gmail.com', '', '2646232163', NULL, '2023', 5, 0, 0, '2023-09-18 09:17:51');
INSERT INTO `tbl_alumnos` VALUES (128, '25573246', 'Fkores', 'JosÃ© RamÃ³n ', 0, 0, 'jrf19760@gmail.com', '', '2645724820', NULL, '2023', 5, 0, 0, '2023-09-18 09:19:50');
INSERT INTO `tbl_alumnos` VALUES (129, '45212796', 'Ruarte Maldonado ', 'Ana Paula ', 0, 0, 'ruarteanapaula@gmail.com', '2644767095', '2644764095', NULL, '2023', 5, 0, 0, '2023-09-18 09:32:44');
INSERT INTO `tbl_alumnos` VALUES (130, '45264102', 'Garcia ', 'VerÃ³nica ', 0, 0, 'vero.garcia5ph@gmail.com', '2646723931', '2646723931', NULL, 'Primer aÃ±o', 5, 0, 0, '2023-09-18 09:50:49');
INSERT INTO `tbl_alumnos` VALUES (131, '32353013', 'Ge pereyra ', 'Liliana natali ', 0, 0, 'liliananatalioge@gmail.com', '2646611092', '2646611092', NULL, '2023', 5, 0, 0, '2023-09-18 10:08:56');
INSERT INTO `tbl_alumnos` VALUES (132, '40367829', 'Becerra Veragua ', 'MarÃ­a del RocÃ­o ', 0, 0, 'rousagi2015@gmail.com', '', '2645017511', NULL, '2023', 5, 0, 0, '2023-09-18 18:19:51');
INSERT INTO `tbl_alumnos` VALUES (133, '37833893', 'Becerra ', 'Celeste', 0, 0, 'celestebecerraveragua@gmail.com', '2644655345', '2644655345', NULL, '2023', 0, 0, 0, '2023-09-18 18:27:39');
INSERT INTO `tbl_alumnos` VALUES (134, '39011548', 'Gutierrez', 'Waldo', 0, 0, 'waldoguti.wg@gmail.com', '2645070681', '2645070681', NULL, '1', 5, 0, 0, '2023-09-18 18:51:12');
INSERT INTO `tbl_alumnos` VALUES (135, '43689762', 'AgÃ¼ero ', 'Milagros ', 0, 0, 'miliaguero24@gmail.com', '2644432291', '2644432291', NULL, '2023', 5, 0, 0, '2023-09-18 18:52:41');
INSERT INTO `tbl_alumnos` VALUES (136, '42334976', 'Aciar Sanchez ', 'Evy Lourdes', 0, 0, 'evyaciar21@gmail.com', '4963735', '2644627361', NULL, '1ro', 5, 0, 0, '2023-09-18 22:46:43');
INSERT INTO `tbl_alumnos` VALUES (137, '41909088', 'Mercado', 'Pamela ', 0, 0, 'pamela07mercado@gmail.com', '2645266911', '2645266911', NULL, '2023', 5, 0, 0, '2023-09-18 22:56:00');
INSERT INTO `tbl_alumnos` VALUES (138, '44730818', 'Rivero', 'Exequiel', 0, 0, 'riverohoracio531@gmail.com', '2645892104', '2645892104', NULL, '2023', 5, 0, 0, '2023-09-18 23:38:55');
INSERT INTO `tbl_alumnos` VALUES (139, '32624461', 'Quiroga', 'Jesica Noelia', 0, 0, 'quirogayesicanoelia@gmail.com', '+542645663124', '2645663124', NULL, '2023', 5, 0, 0, '2023-09-18 23:43:52');
INSERT INTO `tbl_alumnos` VALUES (140, '45473342', 'Aballay', 'Maite', 0, 0, 'maiteaballay1@gmail.com', '2644104964', '2644104965', NULL, '2023', 5, 0, 0, '2023-09-19 00:02:03');
INSERT INTO `tbl_alumnos` VALUES (141, '46726275', 'Segura', 'Delfina ', 0, 0, 'delfinaasegura14@gmail.com', '2644186953', '2644186953', NULL, '2023', 5, 0, 0, '2023-09-19 02:06:36');
INSERT INTO `tbl_alumnos` VALUES (142, '41957748', 'Bustos', 'Gimena', 0, 0, 'bustosgimena23@gmail.com', '', '2645717687', NULL, '2023', 5, 0, 0, '2023-09-19 10:54:33');
INSERT INTO `tbl_alumnos` VALUES (143, '32353041', 'Torres', 'Julieta yanina', 0, 0, 'torresjuli57@gmail.com', '02644676136', '02644676136', NULL, '2023', 5, 0, 0, '2023-09-19 13:44:46');
INSERT INTO `tbl_alumnos` VALUES (144, '40779372', 'VelÃ¡zquez zalazar ', 'Maria del valle ', 0, 0, 'mariazalazar119@gmail.com', '4961488', '2645727227', NULL, '2023', 5, 0, 0, '2023-09-19 17:23:46');
INSERT INTO `tbl_alumnos` VALUES (145, '46408459', 'Chavez', 'Milagros Gilda', 0, 0, 'milagroschavez1536@gmail.com', '2644129775', '2644129775', NULL, '2023', 5, 0, 0, '2023-09-19 19:13:28');
INSERT INTO `tbl_alumnos` VALUES (159, '46407086', 'aguero godoy', 'facundo alejandro', 0, 1, 'a@gmail.com', '', '2644123456', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (162, '43689758', 'Aguero', 'Emanuel Alejandro', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (163, '45472928', 'Alamo', 'Franco Adrian Jesus', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (164, '46407026', 'Arredondo Aranda', 'Luciano Francisco', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (165, '46933134', 'Correa Velardez', 'Matias Luciano', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (166, '46544178', 'Cortes Rodriguez', 'Enzo David', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (167, '44062491', 'Diaz Rodriguez', 'Enzo Santiago', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (168, '43555663', 'Dominguez Heredia', 'Hector Santiago', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (169, '44060993', 'Farias', 'Luis', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (170, '35850538', 'Lucero Bustos', 'Ricardo Matias', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (171, '46805928', 'Maradona', 'Juan Carlos', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (172, '47046928', 'Medina Aguero', 'Martina', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (173, '46071794', 'Moran Castro', 'Florencia Dayana', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (174, '46616541', 'Ogas Rivero', 'Efrain Moises', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (175, '46407083', 'Olmos Duran', 'Bruno Ivan', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (176, '44991810', 'Pereyra Gimenez', 'Pablo Manuel', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (177, '33460738', 'Rojas Torres', 'Johana Silvana', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (178, '44018109', 'Saavedra Oropel', 'Gian Franco', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (180, '46484581', 'Ucciardelo Pagliari', 'Lautaro', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (181, '47046535', 'Yubelmacitusa Amir', 'Eduardo Daniel', 0, 1, 'x@gmail.com', '264400000', '264400000', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (182, '46803652', 'Antunez Saavedra ', 'Jenifer Aida', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (183, '33059379', 'Balmaceda', 'Eduardo Emmanuel', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (184, '45981179', 'Chadi PeÃ±aloza', 'Hector Miguel Ivan', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (185, '45635418', 'Escudero Oviedo', 'Estela Virginia', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (186, '46544590', 'Funes Leyes', 'Maximiliano Alexis', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (187, '42909475', 'Gomez Heredia', 'Julieta Edith', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (188, '44844864', 'Martinez Caravajal', 'Mauricio Adrian', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (189, '47370452', 'Pereyra Nieva', 'Jesus Mauricio Fernando', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (190, '43423151', 'Reinoso Atencio ', 'Celina Alexia', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (191, '44018679', 'Rodriguez Castro', 'Franco Javier', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (192, '45377877', 'Romero Barzola', 'Milton', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (193, '46485339', 'Silva Aballay', 'Milagros Ayelen', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (194, '47285718', 'Silva Gomez', 'Magali Aldana', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (195, '42081123', 'Suarez', 'Ismael Maximiliano', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (196, '42706650', 'Torres', 'Matias Ismael', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (197, '46408369', 'Videla', 'Juana Elizabeth', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (198, '47286300', 'IbaÃ±ez', 'Abril', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (199, '46407750', 'Montivero', 'Alejo', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (200, '41531447', 'Endrizzi', 'Juan', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2024', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (202, '42081190', 'GarcÃ­a Salinas', 'Rodrigo Alexis', 0, 1, 'prueba@gmail.com', '', '264400000', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (203, '43642145', 'Vargas Gutierrez', 'MarÃ­a Florencia', 0, 2, 'g@gmail.com', '26400000', '264400000', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (204, '39651990', 'Alvarez Vicentela', 'Pablo Exequiel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (205, '42168750', 'Asevey Mollo', 'Franco Armando', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (206, '42452396', 'Tejada Godoy', 'Jorge Alexander', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (207, '45981148', 'Gonzalez Suarez', 'Carlos Esteban', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (208, '49563950', 'Ochoa', 'Luz Milagro del Valle', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (209, '35850436', 'Reinoso Bustos', 'Jose Dario Jairo', 0, 1, 'xxx@gmail.com', '12234', '12345', NULL, '2023', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (210, '33846101', 'Zalazar', 'Gisela AnahÃ­', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (211, '38016491', 'Zalazar', 'BelÃ©n Elizabeth', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (212, '35857033', 'Rosas GarcÃ­a', 'Vicente', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (213, '44527318', 'Riveros Belli', 'Marcelo', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (214, '39525909', 'Alonso', 'Santiago David', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (215, '45884679', 'Castillo', 'Rocio Belen', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (216, '23752197', 'Narvay', 'Estela Alejandra', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (217, '43123431', 'Homedes', 'Juan Agustin', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (218, '44730830', 'Luna Bustos', 'Nazareno Nehuen', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (219, '43221132', 'Monardez Furlani', 'Facundo Ismael', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (220, '44730841', 'Arce', 'Caterina Adriana', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (221, '27639445', 'Castro Oviedo', 'MarÃ­a Laura', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (222, '43690017', 'Paez Guzman', 'Benjamin', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (223, '25237008', 'Nievas', 'Cristina Graciela', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (224, '44316644', 'Flores', 'Juan Ignacio', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (225, '45634679', 'Brizuela RÃ­os', 'Ulises Luciano', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (226, '43221182', 'Monardez Furlani', 'Facundo Ismael', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (227, '24038819', 'Usair', 'Julio CÃ©sar', 0, 2, 'g@gmail.com', '222', '222', NULL, '2022', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (228, '44844544', 'Ahumada Lucero', 'Nahuel Nair', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (229, 'Calderon Acosta', 'Dalila BelÃ©n', '39009483', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (230, 'Carrizo', 'Leonardo GastÃ³n', '29226409', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (231, '43689746', 'Gutierrez Aguero', 'SebastiÃ¡n', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (232, '38078817', 'Leiva Fernandez', 'Alfredo', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (233, '35849716', 'Martin Campillay', 'Macarena', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (234, '43689707', 'Melian Fernandez', 'Franco Leonel', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (235, '44665720', 'Romero', 'Leandro JesÃºs', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (236, '44248998', 'Carrizo', 'Diego AgustÃ­n', 0, 1, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (237, '35922670', 'Castro', 'GastÃ³n Esteban', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (238, '42250428', 'Ganzitano', 'IvÃ¡n David', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (239, '41957684', 'Marti Castro', 'Ignacio ValentÃ­n', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (240, '38595276', 'Montiveros', 'MarÃ­a Ayelen', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (241, '41776488', 'Morales', 'JesÃºs Facundo Daniel', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (242, '42163173', 'Moralez Moreno', 'MatÃ­as AndrÃ©s', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (243, '43764054', 'Moralez Moreno', 'TomÃ¡s MartÃ­n', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (244, '37833931', 'Quiroga', 'Carina Micaela', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (245, '35852801', 'Quiroga', 'Ruben MatÃ­as', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (246, '43489624', 'Silva', 'David Nahuel', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (247, '40332615', 'Sosa', 'JosÃ© Gabriel', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (248, '44062439', 'Suarez', 'Valeria Juana', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (249, '24234814', 'Torrente Ariza', 'Silvia Beatriz', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (250, '44665988', 'Vila MuÃ±oz', 'JosÃ© Gabriel', 0, 2, 'g@gmail.com', '222', '222', NULL, '2021', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (251, '42250353', 'Alaniz Escudero', 'Tamara Milagros', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (252, '45635092', 'Carrizo', 'Martin Fernando', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (253, '39425454', 'Coria Alaniz', 'Maximiliano Exequiel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (254, '40592489', 'Ferreyra ', 'Marcia Mariana', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (255, '33759973', 'Garay', 'Mauro David', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (256, '35510553', 'Garcia Diaz', 'Cecilia Valeria', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (257, '43488625', 'Lucero Olivera', 'Ludmila Milagros', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (258, '39425413', 'Ochoa', 'Melina Gisel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (259, '38592327', 'Oro MuÃ±oz', 'Eric Andres', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (260, '43221539', 'Rivero', 'Elisa Adriana', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (261, '41321729', 'Yavante Martinez', 'Brian Nahuel', 0, 1, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (262, '37924455', 'Alvarez', 'Santiago David', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (263, '21666862', 'Andrada', 'Alberto Javier', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (264, '42805632', 'Carmona Paredes', 'Juan Gabriel', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (265, '24426668', 'Pacheco Almarcha', 'Lorena Liliana', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (266, '40939084', 'Riveros Arce', 'Aylen Constanza', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (267, '39995228', 'Videla', 'Emanuel Rodrigo', 0, 2, 'xxx@gmail.com', '1234', '1234', NULL, '2019', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (268, '41957789', 'IbaÃ±ez Sirerol', 'JosÃ© JuliÃ¡n', 0, 1, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (269, '33846465', 'Molina Delgado', 'MarÃ­a NoemÃ­', 0, 1, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (270, '42516940', 'MuÃ±oz Mercado', 'Ian Braian', 0, 1, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (271, '41531416', 'PeÃ±aloza', 'Micaela Liseth', 0, 1, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (272, '29270300', 'Ramos', 'Ivana Graciela', 0, 1, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (273, '43952481', 'Sirerol Almarcha', 'Marco NicolÃ¡s', 0, 1, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (274, '', '', '', 0, 0, '', '', '', NULL, '', 0, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (275, '38216902', 'Cortez', 'Tatiana Erica', 0, 2, 'g@gmail.com', '22', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (276, '42516951', 'Elizondo', 'Franco SebastiÃ¡n', 0, 2, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (277, '42005554', 'Gomez Platero', 'Mauricio AndrÃ©s', 0, 2, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (278, '42711754', 'Guaquinchay Moran', 'Kevin Antonio', 0, 2, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (279, '42250342', 'Guzman Gomez', 'JuliÃ¡n Gabriel', 0, 2, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (280, '42805636', 'Malla', 'Franco Nahuel', 0, 2, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');
INSERT INTO `tbl_alumnos` VALUES (281, '32653148', 'Morrone Espinosa', 'Pablo Javier', 0, 2, 'g@gmail.com', '222', '222', NULL, '2020', 1, 0, 1, '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for tbl_alumnos_cursa
-- ----------------------------
DROP TABLE IF EXISTS `tbl_alumnos_cursa`;
CREATE TABLE `tbl_alumnos_cursa`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `dni` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `celular` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `legajo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `anno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `materia` int NOT NULL DEFAULT 0,
  `turno` int NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL,
  `apellido` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `curso` int NOT NULL,
  `division` int NOT NULL,
  `cursado` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mesa` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_alumnos_cursa
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_alumnos_materias
-- ----------------------------
DROP TABLE IF EXISTS `tbl_alumnos_materias`;
CREATE TABLE `tbl_alumnos_materias`  (
  `Id` int NOT NULL AUTO_INCREMENT,
  `alumno` int NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `nota` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cursada` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `rendida` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `equivalencia` int NULL DEFAULT NULL,
  `libre` int NULL DEFAULT NULL,
  `libro` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `folio` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `calificacion-cursada` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `calificacion_rendida` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `libro_corte` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2663 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_alumnos_materias
-- ----------------------------
INSERT INTO `tbl_alumnos_materias` VALUES (8, 159, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (9, 159, 1, 2, '6', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (10, 159, 1, 3, '10', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (11, 159, 1, 4, '7', '1', '0', 0, 0, '4', '34', '2024-09-17', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (12, 159, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (13, 159, 1, 7, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (14, 159, 1, 9, '8', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (15, 159, 1, 10, '7', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (16, 159, 1, 11, '8', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (17, 162, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (18, 162, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (19, 162, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (20, 162, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (21, 162, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (22, 162, 1, 6, '7', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (23, 162, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (24, 162, 1, 8, '7', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (25, 162, 1, 9, '8', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (26, 162, 1, 10, '9', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (27, 162, 1, 11, '8', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (28, 163, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (29, 163, 1, 2, '4', '1', '0', 0, 0, '4', '20', '2024-07-30', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (30, 163, 1, 3, '8', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (31, 163, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (32, 163, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (33, 163, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (34, 163, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (35, 163, 1, 8, '9', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (36, 163, 1, 9, '10', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (37, 163, 1, 10, '8', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (38, 163, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (39, 164, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (40, 164, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (41, 164, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (42, 164, 1, 4, '9', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (43, 164, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (44, 164, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (45, 164, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (46, 164, 1, 8, '7', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (47, 164, 1, 9, '9', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (48, 164, 1, 10, '8', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (49, 164, 1, 11, '8', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (50, 165, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (51, 166, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (52, 166, 1, 3, '8', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (53, 167, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (54, 167, 1, 2, '4', '1', '0', 0, 0, '4', '20', '2024-07-30', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (55, 167, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (56, 167, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (57, 167, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (58, 167, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (59, 167, 1, 7, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (60, 167, 1, 8, '7', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (61, 167, 1, 9, '8', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (62, 167, 1, 10, '9', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (63, 167, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (64, 168, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (65, 168, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (66, 168, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (67, 169, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (68, 169, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (69, 169, 1, 4, '8', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (70, 170, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (71, 170, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (72, 171, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (73, 171, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (74, 171, 1, 4, '8', '1', '0', 0, 0, '4', '34', '2024-09-17', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (75, 171, 1, 7, '9', '1', '0', 0, 0, '4', '31', '2024-08-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (76, 172, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (77, 172, 1, 2, '5', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (78, 172, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (79, 172, 1, 4, '9', '1', '0', 0, 0, '4', '40', '2024-12-04', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (80, 172, 1, 5, '7', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (81, 172, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (82, 172, 1, 9, '10', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (83, 172, 1, 11, '8', '1', '0', 0, 0, '4', '49', '2024-12-18', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (84, 173, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (85, 173, 1, 4, '9', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (86, 173, 1, 5, '7', '0', '1', 0, 0, '5', '33', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (87, 173, 1, 7, '6', '1', '0', 0, 0, '4', '36', '2024-12-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (88, 173, 1, 9, '9', '1', '0', 0, 0, '5', '2', '2025-03-10', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (89, 173, 1, 11, '4', '1', '0', 0, 0, '4', '41', '2024-12-04', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (90, 174, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (91, 174, 1, 3, '10', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (92, 174, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (93, 174, 1, 5, '7', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (94, 174, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (95, 174, 1, 10, '8', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (96, 175, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (97, 175, 1, 2, '7', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (98, 175, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (99, 175, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (100, 175, 1, 5, '7', '0', '1', 0, 0, '5', '38', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (101, 175, 1, 6, '9', '1', '0', 0, 0, '4', '23', '2024-07-30', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (102, 175, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (103, 175, 1, 8, '8', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (104, 175, 1, 9, '9', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (105, 175, 1, 10, '10', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (106, 175, 1, 11, '7', '0', '0', 0, 1, '2', '39', '2024-12-18', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (107, 176, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (108, 176, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (109, 177, 1, 1, '9', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (110, 177, 1, 3, '9', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (111, 178, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (112, 178, 1, 2, '5', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (113, 178, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (114, 178, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (115, 178, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (116, 178, 1, 6, '8', '0', '1', 0, 0, '5', '25', '2024-06-28', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (117, 178, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (118, 178, 1, 8, '8', '0', '1', 0, 0, '5', '31', '2024-11-11', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (119, 178, 1, 9, '9', '0', '1', 0, 0, '5', '33', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (120, 178, 1, 10, '10', '0', '1', 0, 0, '5', '40', '2024-01-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (121, 178, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (122, 180, 1, 1, '10', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (123, 180, 1, 2, '6', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (124, 180, 1, 3, '8', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (125, 180, 1, 4, '10', '0', '1', 0, 0, '5', '27', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (126, 180, 1, 5, '10', '0', '1', 0, 0, '5', '38', '2024-11-15', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (127, 180, 1, 6, '8', '0', '1', 0, 0, '5', '23', '2024-06-28', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (128, 180, 1, 7, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (129, 180, 1, 8, '9', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (130, 180, 1, 9, '9', '1', '0', 0, 0, '4', '47', '2024-12-17', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (131, 180, 1, 10, '10', '0', '1', 0, 0, '5', '40', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (132, 180, 1, 11, '8', '0', '1', 0, 0, '5', '37', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (133, 181, 1, 1, '7', '0', '1', 0, 0, '5', '29', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (134, 181, 1, 3, '7', '0', '1', 0, 0, '5', '22', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (135, 182, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (136, 182, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (137, 182, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (138, 182, 1, 5, '10', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (139, 182, 1, 6, '7', '1', '0', 0, 0, '4', '51', '2024-12-20', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (140, 182, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (141, 182, 1, 8, '6', '1', '0', 0, 0, '5', '5', '2025-03-21', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (142, 182, 1, 10, '10', '0', '1', 0, 0, '5', '35', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (143, 182, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (144, 183, 1, 1, '10', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (145, 183, 1, 2, '9', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (146, 183, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (147, 183, 1, 4, '10', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (148, 183, 1, 5, '9', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (149, 183, 1, 6, '9', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (150, 183, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (151, 183, 1, 8, '7', '0', '1', 0, 0, '5', '32', '2024-11-11', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (152, 183, 1, 9, '9', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (153, 183, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (154, 183, 1, 11, '9', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (155, 184, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (156, 184, 1, 2, '4', '1', '0', 0, 0, '4', '35', '2024-09-20', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (157, 184, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (158, 184, 1, 4, '7', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (159, 184, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (160, 184, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (161, 184, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (162, 184, 1, 8, '6', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (163, 184, 1, 9, '9', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (164, 184, 1, 10, '8', '0', '1', 0, 0, '5', '35', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (165, 184, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (166, 185, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (167, 186, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (168, 186, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (169, 186, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (170, 186, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (171, 186, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (172, 186, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (173, 186, 1, 10, '7', '0', '1', 0, 0, '5', '35', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (174, 186, 1, 11, '9', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (175, 187, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (176, 187, 1, 3, '7', '0', '0', 0, 1, '2', '34', '2024-12-03', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (177, 187, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (178, 187, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (179, 188, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (180, 188, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (181, 188, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (182, 188, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (183, 188, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (184, 189, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (185, 189, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (186, 189, 1, 3, '9', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (187, 189, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (188, 189, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (189, 189, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (190, 189, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (191, 189, 1, 9, '8', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (192, 189, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (193, 190, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (194, 190, 1, 3, '7', '0', '0', 0, 1, '2', '34', '2024-12-03', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (195, 190, 1, 4, '10', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (196, 190, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (197, 190, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (198, 191, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (199, 191, 1, 5, '9', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (200, 191, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (201, 191, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (202, 192, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (203, 192, 1, 3, '10', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (204, 192, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (205, 192, 1, 5, '9', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (206, 192, 1, 6, '7', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (207, 192, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (208, 192, 1, 10, '7', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (209, 192, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (210, 193, 1, 1, '7', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (211, 193, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (212, 193, 1, 4, '7', '1', '0', 0, 0, '4', '32', '2024-08-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (213, 193, 1, 5, '7', '1', '0', 0, 0, '4', '43', '2024-12-06', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (214, 193, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (215, 193, 1, 10, '8', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (216, 193, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (217, 194, 1, 1, '10', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (218, 194, 1, 2, '7', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (219, 194, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (220, 194, 1, 4, '10', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (221, 194, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (222, 194, 1, 6, '9', '0', '1', 0, 0, '5', '24', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (223, 194, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (224, 194, 1, 8, '8', '0', '1', 0, 0, '5', '32', '2024-11-11', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (225, 194, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (226, 194, 1, 11, '9', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (227, 195, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (228, 195, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (229, 195, 1, 4, '8', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (230, 195, 1, 5, '10', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (231, 195, 1, 6, '8', '1', '0', 0, 0, '4', '27', '2024-08-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (232, 195, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (233, 195, 1, 8, '7', '1', '0', 0, 0, '4', '44', '2024-12-06', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (234, 195, 1, 10, '9', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (235, 195, 1, 11, '8', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (236, 196, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (237, 196, 1, 3, '7', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (238, 196, 1, 4, '8', '1', '0', 0, 0, '4', '32', '2024-08-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (239, 196, 1, 5, '8', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (240, 196, 1, 6, '4', '1', '0', 0, 0, '4', '27', '2024-08-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (241, 196, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-06-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (242, 197, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (243, 197, 1, 2, '4', '1', '0', 0, 0, '4', '28', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (244, 197, 1, 3, '9', '0', '1', 0, 0, '5', '23', '2024-06-27', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (245, 197, 1, 4, '9', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (246, 197, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (247, 197, 1, 7, '9', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (248, 197, 1, 10, '7', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (249, 197, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (250, 198, 1, 1, '6', '1', '0', 0, 0, '4', '29', '2024-08-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (251, 198, 1, 2, '4', '1', '0', 0, 0, '4', '35', '2024-09-20', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (252, 198, 1, 7, '8', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (253, 199, 1, 1, '9', '0', '1', 0, 0, '5', '30', '2024-07-02', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (254, 199, 1, 4, '7', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (255, 199, 1, 7, '10', '0', '1', 0, 0, '5', '26', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (256, 200, 1, 1, '8', '1', '0', 0, 0, '4', '21', '2024-07-30', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (257, 200, 1, 4, '7', '0', '1', 0, 0, '5', '28', '2024-07-01', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (258, 200, 1, 5, '7', '0', '1', 0, 0, '5', '35', '2024-11-12', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (259, 200, 1, 6, '5', '1', '0', 0, 0, '4', '23', '2024-07-30', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (260, 200, 1, 8, '6', '1', '0', 0, 0, '4', '52', '2024-12-20', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (261, 200, 1, 10, '9', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (262, 200, 1, 11, '7', '0', '1', 0, 0, '5', '36', '2024-11-13', NULL, NULL, '1');
INSERT INTO `tbl_alumnos_materias` VALUES (263, 6, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (264, 6, 1, 2, '5', '0', '1', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (265, 6, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (266, 6, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (267, 6, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (268, 6, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (269, 6, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (270, 6, 1, 8, '8', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (271, 6, 1, 9, '8', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (272, 6, 1, 10, '10', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (273, 6, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (274, 10, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-08-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (275, 10, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (276, 10, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (277, 10, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (278, 10, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (279, 90, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (280, 90, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (281, 90, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (282, 90, 1, 6, '4', '1', '0', 0, 0, '3', '32', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (283, 90, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (284, 96, 1, 1, '7', '0', '1', 0, 0, '3', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (285, 96, 1, 2, '7', '1', '0', 0, 0, '4', '8', '2023-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (286, 96, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (287, 96, 1, 5, '8', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (288, 96, 1, 6, '6', '1', '0', 0, 0, '3', '32', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (289, 96, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (290, 96, 1, 8, '7', '1', '0', 0, 0, '4', '22', '2024-07-30', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (291, 96, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (292, 96, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (293, 96, 1, 12, '7', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (294, 96, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (295, 96, 1, 15, '9', '1', '0', 0, 0, '2', '49', '2024-08-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (296, 96, 1, 16, '8', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (297, 96, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (298, 96, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (299, 96, 1, 20, '7', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (300, 4, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (301, 4, 1, 2, '7', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (302, 4, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (303, 4, 1, 4, '9', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (304, 4, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (305, 4, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (306, 4, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (307, 4, 1, 8, '9', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (308, 4, 1, 10, '10', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (309, 4, 1, 11, '9', '0', '1', 0, 0, '5', '10', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (310, 4, 1, 12, '10', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (311, 4, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (312, 4, 1, 14, '7', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (313, 4, 1, 15, '10', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (314, 4, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (315, 4, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (316, 4, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (317, 4, 1, 20, '10', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (318, 37, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (319, 37, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (320, 37, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (321, 37, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (322, 37, 1, 6, '4', '1', '0', 0, 0, '3', '40', '2023-09-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (323, 37, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (324, 37, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (325, 202, 1, 1, '8', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (326, 202, 1, 2, '10', '1', '0', 0, 0, '4', '13', '2023-03-06', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (327, 202, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (328, 202, 1, 4, '9', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (329, 202, 1, 5, '10', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (330, 202, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (331, 202, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (332, 202, 1, 8, '9', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (333, 202, 1, 10, '10', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (334, 202, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (335, 202, 1, 12, '9', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (336, 202, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (337, 202, 1, 15, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (338, 202, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (339, 202, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (340, 202, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (341, 202, 1, 20, '9', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (382, 24, 1, 1, '10', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (383, 24, 1, 2, '4', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (384, 24, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (385, 24, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (386, 24, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (387, 24, 1, 6, '9', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (388, 24, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (389, 24, 1, 8, '8', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (390, 24, 1, 9, '8', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (391, 24, 1, 10, '10', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (392, 24, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (393, 24, 1, 12, '9', '0', '1', 0, 0, '5', '18', '2024-04-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (394, 24, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (395, 24, 1, 14, '9', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (396, 24, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (397, 24, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (398, 24, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (399, 24, 1, 18, '9', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (400, 24, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (401, 24, 1, 20, '9', '1', '0', 0, 0, '3', '2', '2024-09-18', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (402, 24, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (403, 9, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (404, 9, 1, 2, '5', '1', '0', 0, 0, '4', '48', '2024-12-18', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (405, 9, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (406, 9, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (407, 9, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (408, 9, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (409, 9, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (410, 9, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (411, 204, 1, 1, '8', '1', '0', 0, 0, '4', '2', '2023-12-06', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (412, 204, 1, 2, '4', '1', '0', 0, 0, '3', '36', '0000-00-00', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (413, 204, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (414, 204, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (415, 204, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (416, 204, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (417, 204, 1, 7, '9', '1', '0', 0, 0, '4', '7', '2023-12-19', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (418, 204, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (419, 204, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (420, 204, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (421, 204, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (422, 204, 1, 16, '10', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (423, 204, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (424, 92, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (425, 92, 1, 2, '5', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (426, 92, 1, 3, '4', '0', '0', 0, 1, '2', '10', '2023-09-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (427, 92, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (428, 92, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (429, 92, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (430, 92, 1, 7, '7', '1', '0', 0, 0, '4', '7', '2023-12-19', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (431, 92, 1, 8, '4', '0', '0', 0, 1, '2', '21', '2023-12-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (432, 92, 1, 10, '7', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (433, 92, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (434, 92, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (435, 92, 1, 13, '8', '0', '0', 0, 1, '2', '40', '2024-12-18', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (436, 92, 1, 15, '9', '1', '0', 0, 0, '3', '7', '2024-12-06', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (437, 92, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (438, 92, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (439, 92, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (440, 92, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (441, 92, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (442, 16, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (443, 16, 1, 2, '4', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (444, 16, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (445, 16, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (446, 16, 1, 5, '8', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (447, 16, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (448, 16, 1, 7, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (449, 16, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (450, 16, 1, 9, '7', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (451, 16, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (452, 16, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (453, 16, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (454, 16, 1, 14, '10', '0', '1', 0, 0, '5', '29', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (455, 16, 1, 15, '9', '1', '0', 0, 0, '2', '49', '2024-08-12', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (456, 16, 1, 16, '10', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (457, 16, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (458, 16, 1, 18, '7', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (459, 16, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (460, 16, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (461, 16, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (462, 205, 1, 1, '6', '0', '0', 0, 1, '2', '14', '2023-12-06', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (463, 205, 1, 2, '8', '0', '0', 0, 1, '2', '19', '2023-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (464, 205, 1, 3, '5', '0', '0', 0, 1, '2', '10', '2023-09-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (465, 205, 1, 4, '9', '0', '0', 0, 1, '2', '12', '2023-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (466, 205, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (467, 205, 1, 6, '4', '0', '0', 0, 1, '2', '13', '2023-12-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (468, 205, 1, 7, '9', '0', '0', 0, 1, '2', '4', '2023-08-16', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (469, 205, 1, 8, '6', '0', '0', 0, 1, '2', '27', '2024-03-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (470, 205, 1, 9, '9', '1', '0', 0, 0, '5', '49', '2023-12-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (471, 205, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (472, 205, 1, 11, '7', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (473, 205, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (474, 205, 1, 13, '7', '0', '0', 0, 1, '2', '29', '2024-07-31', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (475, 205, 1, 14, '10', '0', '1', 0, 0, '5', '29', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (476, 205, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (477, 205, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (478, 205, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (479, 205, 1, 18, '7', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (480, 205, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (481, 205, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (482, 205, 1, 21, '10', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (483, 28, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (484, 28, 1, 2, '6', '0', '0', 0, 1, '2', '2', '2023-09-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (485, 28, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (486, 28, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (487, 28, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (488, 28, 1, 6, '8', '0', '0', 0, 1, '1', '53', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (489, 28, 1, 7, '6', '1', '0', 0, 0, '3', '39', '2023-08-16', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (490, 28, 1, 8, '4', '0', '0', 0, 1, '2', '42', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (491, 28, 1, 9, '8', '1', '0', 0, 0, '4', '6', '2023-12-19', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (492, 28, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (493, 28, 1, 11, '7', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (494, 28, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (495, 28, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (496, 28, 1, 14, '7', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (497, 28, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (498, 28, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (499, 28, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (500, 28, 1, 20, '5', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (501, 89, 1, 1, '7', '1', '0', 0, 0, '3', '38', '2023-08-16', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (502, 89, 1, 2, '4', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (503, 89, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (504, 89, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (505, 89, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (506, 89, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (507, 89, 1, 13, '6', '0', '0', 0, 1, '2', '33', '2024-09-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (508, 89, 1, 16, '7', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (509, 89, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (510, 23, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (511, 23, 1, 2, '5', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (512, 23, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (513, 23, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (514, 23, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (515, 23, 1, 6, '9', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (516, 23, 1, 7, '9', '1', '0', 0, 0, '4', '7', '2023-12-19', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (517, 23, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (518, 23, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (519, 23, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (520, 23, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (521, 23, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (522, 23, 1, 13, '9', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (523, 23, 1, 15, '10', '1', '0', 0, 0, '2', '49', '2024-08-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (524, 23, 1, 16, '10', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (525, 23, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (526, 23, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (527, 23, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (528, 23, 1, 20, '9', '1', '0', 0, 0, '3', '15', '2025-03-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (529, 23, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (530, 30, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (531, 30, 1, 2, '5', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (532, 30, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (533, 30, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (534, 30, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (535, 30, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (536, 30, 1, 7, '10', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (537, 30, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (538, 30, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (539, 30, 1, 10, '10', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (540, 30, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (541, 30, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (542, 30, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (543, 30, 1, 14, '10', '1', '0', 0, 0, '3', '9', '2024-12-17', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (544, 30, 1, 15, '8', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (545, 30, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (546, 30, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (547, 30, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (548, 30, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (549, 30, 1, 20, '9', '1', '0', 0, 0, '2', '31', '2024-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (550, 30, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (551, 12, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (552, 12, 1, 2, '9', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (553, 12, 1, 3, '4', '0', '0', 0, 1, '2', '26', '2024-03-19', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (554, 12, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (555, 12, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (556, 12, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (557, 12, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (558, 12, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (559, 12, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (560, 12, 1, 10, '10', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (561, 12, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (562, 12, 1, 12, '9', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (563, 12, 1, 13, '10', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (564, 12, 1, 14, '10', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (565, 12, 1, 15, '8', '1', '0', 0, 0, '2', '45', '2024-07-30', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (566, 12, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (567, 12, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (568, 12, 1, 18, '9', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (569, 12, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (570, 12, 1, 20, '8', '1', '0', 0, 0, '2', '51', '2024-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (571, 12, 1, 21, '8', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (572, 7, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (573, 7, 1, 2, '4', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (574, 7, 1, 3, '9', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (575, 7, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (576, 7, 1, 5, '8', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (577, 7, 1, 6, '5', '1', '0', 0, 0, '3', '37', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (578, 7, 1, 7, '10', '1', '0', 0, 0, '3', '35', '2023-08-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (579, 7, 1, 8, '7', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (580, 7, 1, 9, '9', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (581, 7, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (582, 7, 1, 11, '7', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (583, 7, 1, 12, '8', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (584, 7, 1, 13, '8', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (585, 7, 1, 14, '7', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (586, 7, 1, 15, '9', '0', '0', 0, 1, '2', '30', '2024-08-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (587, 7, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (588, 7, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (589, 7, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (590, 7, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (591, 25, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (592, 25, 1, 2, '8', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (593, 25, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (594, 25, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (595, 25, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (596, 25, 1, 6, '7', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (597, 25, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (598, 25, 1, 8, '10', '0', '1', 0, 0, '5', '14', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (599, 25, 1, 10, '9', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (600, 25, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (601, 25, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (602, 25, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (603, 25, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (604, 25, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (605, 25, 1, 17, '7', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (606, 25, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (607, 25, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (608, 25, 1, 20, '8', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (609, 25, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (610, 33, 1, 1, '10', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (611, 33, 1, 2, '8', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (612, 33, 1, 3, '8', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (613, 33, 1, 4, '10', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (614, 33, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (615, 33, 1, 6, '9', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (616, 33, 1, 7, '10', '1', '0', 0, 0, '3', '39', '2023-08-16', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (617, 33, 1, 9, '10', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (618, 33, 1, 10, '10', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (619, 33, 1, 11, '9', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (620, 86, 1, 1, '8', '1', '0', 0, 0, '4', '9', '2023-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (621, 86, 1, 2, '4', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (622, 86, 1, 3, '7', '0', '0', 0, 1, '2', '10', '2023-09-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (623, 86, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (624, 86, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (625, 86, 1, 6, '4', '0', '0', 0, 1, '2', '41', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (626, 86, 1, 7, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (627, 86, 1, 10, '8', '0', '0', 1, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (628, 86, 1, 11, '6', '1', '0', 0, 0, '4', '5', '2023-12-18', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (629, 86, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (630, 86, 1, 13, '7', '0', '1', 0, 0, '5', '22', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (631, 86, 1, 14, '8', '1', '0', 0, 0, '3', '9', '2024-12-17', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (632, 86, 1, 16, '8', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (633, 86, 1, 18, '8', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (634, 86, 1, 19, '9', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (635, 31, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (636, 31, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (637, 31, 1, 6, '7', '0', '0', 0, 1, '2', '2', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (638, 18, 1, 1, '7', '1', '0', 0, 0, '3', '34', '2023-08-02', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (639, 18, 1, 2, '8', '0', '0', 0, 1, '2', '16', '2023-12-06', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (640, 18, 1, 4, '8', '1', '0', 0, 0, '3', '43', '2023-09-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (641, 18, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (642, 18, 1, 7, '4', '1', '0', 0, 0, '3', '35', '2023-08-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (643, 18, 1, 9, '8', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (644, 18, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (645, 18, 1, 11, '7', '1', '0', 0, 0, '4', '16', '2024-03-18', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (646, 18, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (647, 18, 1, 14, '7', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (648, 18, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (649, 206, 1, 1, '6', '0', '0', 0, 1, '2', '14', '2023-12-06', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (650, 206, 1, 2, '9', '0', '0', 0, 1, '2', '19', '2023-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (651, 206, 1, 4, '7', '0', '0', 0, 1, '2', '12', '2023-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (652, 206, 1, 5, '8', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (653, 206, 1, 6, '6', '0', '0', 0, 1, '2', '13', '2023-12-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (654, 206, 1, 7, '7', '0', '0', 0, 1, '2', '28', '2024-07-31', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (655, 206, 1, 8, '5', '0', '0', 0, 1, '2', '27', '2024-03-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (656, 206, 1, 9, '10', '1', '0', 0, 0, '4', '6', '2023-12-19', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (657, 206, 1, 10, '8', '0', '1', 0, 0, '5', '19', '2023-11-22', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (658, 206, 1, 11, '6', '1', '0', 0, 0, '3', '45', '2023-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (659, 206, 1, 12, '7', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (660, 206, 1, 13, '7', '0', '0', 0, 1, '2', '29', '2024-07-31', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (661, 206, 1, 14, '9', '0', '1', 0, 0, '5', '26', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (662, 206, 1, 15, '9', '0', '1', 0, 0, '5', '20', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (663, 206, 1, 16, '9', '0', '1', 0, 0, '5', '19', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (664, 206, 1, 17, '8', '0', '1', 0, 0, '5', '24', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (665, 206, 1, 18, '7', '1', '0', 0, 0, '3', '6', '2024-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (666, 206, 1, 19, '8', '0', '1', 0, 0, '5', '27', '2024-11-11', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (667, 206, 1, 20, '9', '1', '0', 0, 0, '3', '12', '2024-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (668, 206, 1, 21, '10', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (669, 26, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (670, 26, 1, 4, '7', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (671, 207, 1, 1, '8', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (672, 207, 1, 4, '9', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (677, 208, 1, 4, '8', '0', '1', 0, 0, '5', '8', '2023-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (678, 209, 1, 1, '7', '0', '1', 0, 0, '5', '3', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (679, 22, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (680, 22, 1, 2, '8', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (681, 22, 1, 3, '9', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (682, 22, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (683, 22, 1, 6, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (684, 22, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (685, 19, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (686, 19, 1, 2, '5', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (687, 19, 1, 3, '10', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (688, 19, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (689, 19, 1, 5, '10', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (690, 19, 1, 6, '7', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (691, 19, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (692, 19, 1, 8, '7', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (693, 19, 1, 9, '8', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (694, 19, 1, 10, '10', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (695, 19, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (696, 19, 1, 12, '9', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (697, 19, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (698, 19, 1, 14, '7', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (699, 19, 1, 15, '9', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (700, 19, 1, 16, '8', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (701, 19, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (702, 19, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (703, 19, 1, 19, '9', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (704, 19, 1, 20, '9', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (705, 19, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (706, 94, 1, 1, '10', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (707, 94, 1, 2, '8', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (708, 94, 1, 3, '10', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (709, 94, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (710, 94, 1, 6, '10', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (711, 94, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (712, 17, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (713, 17, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (714, 17, 1, 5, '8', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (715, 17, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (716, 17, 1, 10, '7', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (717, 17, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (718, 17, 1, 16, '7', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (719, 17, 1, 17, '7', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (720, 17, 1, 19, '8', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (721, 41, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (722, 40, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (723, 40, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (724, 73, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (725, 73, 1, 3, '6', '0', '0', 0, 1, '2', '26', '2024-03-19', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (726, 73, 1, 4, '7', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (727, 73, 1, 5, '10', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (728, 73, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (729, 73, 1, 8, '8', '1', '0', 0, 0, '5', '1', '2025-03-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (730, 73, 1, 9, '9', '0', '1', 0, 0, '5', '34', '2024-11-12', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (731, 73, 1, 10, '8', '0', '1', 0, 0, '5', '39', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (732, 73, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (733, 5, 1, 1, '9', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (734, 5, 1, 2, '7', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (735, 5, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (736, 5, 1, 4, '10', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (737, 5, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (738, 5, 1, 6, '8', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (739, 5, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (740, 5, 1, 8, '7', '0', '1', 0, 0, '5', '13', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (741, 5, 1, 9, '8', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (742, 5, 1, 10, '8', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (743, 5, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (744, 5, 1, 12, '10', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (745, 5, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (746, 5, 1, 14, '8', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (747, 5, 1, 15, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (748, 5, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (749, 5, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (750, 5, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (751, 5, 1, 19, '9', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (752, 5, 1, 20, '8', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (753, 5, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (754, 11, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (755, 11, 1, 2, '6', '1', '0', 0, 0, '3', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (756, 11, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (757, 11, 1, 4, '9', '1', '0', 0, 0, '5', '46', '2023-12-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (758, 11, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (759, 11, 1, 6, '4', '1', '0', 0, 0, '3', '37', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (760, 11, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (761, 11, 1, 10, '9', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (762, 11, 1, 11, '9', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (763, 32, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (764, 32, 1, 2, '6', '1', '0', 0, 0, '4', '8', '2023-12-20', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (765, 32, 1, 3, '10', '0', '0', 0, 1, '2', '23', '2024-03-06', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (766, 32, 1, 4, '7', '1', '0', 0, 0, '3', '43', '2023-09-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (767, 32, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (768, 32, 1, 6, '4', '1', '0', 0, 0, '4', '1', '2023-12-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (769, 32, 1, 7, '7', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (770, 32, 1, 8, '4', '1', '0', 0, 0, '4', '4', '2023-12-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (771, 32, 1, 9, '8', '0', '1', 0, 0, '5', '10', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (772, 32, 1, 10, '8', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (773, 32, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (774, 32, 1, 12, '8', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (775, 32, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (776, 32, 1, 15, '9', '1', '0', 0, 0, '2', '49', '2024-08-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (777, 32, 1, 16, '9', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (778, 32, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (779, 32, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (780, 32, 1, 19, '9', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (781, 32, 1, 20, '9', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (782, 32, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (783, 38, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (784, 38, 1, 2, '4', '1', '0', 0, 0, '5', '31', '2023-08-01', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (785, 38, 1, 3, '7', '0', '1', 0, 0, '5', '2', '2023-06-26', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (786, 38, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (787, 38, 1, 5, '9', '0', '1', 0, 0, '5', '11', '2023-11-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (788, 38, 1, 7, '8', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (789, 38, 1, 9, '7', '0', '1', 0, 0, '5', '16', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (790, 38, 1, 10, '8', '0', '1', 0, 0, '5', '18', '2023-11-21', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (791, 38, 1, 11, '8', '0', '1', 0, 0, '5', '12', '2023-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (792, 38, 1, 14, '7', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (793, 38, 1, 16, '7', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (794, 38, 1, 17, '8', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (795, 38, 1, 19, '10', '0', '1', 0, 0, '5', '31', '2024-11-14', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (796, 38, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (803, 203, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (804, 203, 1, 4, '8', '0', '1', 0, 0, '5', '9', '2023-07-05', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (805, 203, 1, 5, '8', '0', '1', 0, 0, '5', '11', '2023-12-09', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (806, 74, 1, 1, '7', '0', '1', 0, 0, '5', '4', '2023-06-28', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (807, 74, 1, 2, '4', '1', '0', 0, 0, '3', '36', '2023-08-15', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (808, 74, 1, 7, '8', '1', '0', 0, 0, '3', '35', '2023-08-03', NULL, NULL, '2');
INSERT INTO `tbl_alumnos_materias` VALUES (809, 210, 1, 1, '8', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (810, 210, 1, 3, '9', '0', '1', 0, 0, '4', '10', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (811, 210, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (812, 210, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (813, 210, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (814, 211, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (815, 211, 1, 3, '8', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (816, 211, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (817, 211, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (818, 211, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (819, 212, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (820, 212, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (821, 213, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (822, 213, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (823, 213, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (824, 213, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (825, 214, 1, 2, '9', '0', '1', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (826, 214, 1, 3, '7', '0', '1', 0, 0, '5', '1', '2023-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (827, 214, 1, 4, '9', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (828, 214, 1, 5, '9', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (829, 214, 1, 6, '8', '0', '1', 0, 0, '5', '6', '2023-07-03', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (830, 214, 1, 8, '4', '1', '0', 0, 0, '4', '19', '2024-03-21', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (831, 214, 1, 9, '7', '0', '1', 0, 0, '5', '15', '2023-11-21', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (832, 214, 1, 11, '8', '0', '1', 0, 0, '5', '17', '2023-11-21', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (844, 215, 1, 1, '7', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (845, 215, 1, 4, '8', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (846, 1, 1, 1, '8', '0', '1', 0, 0, '4', '1', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (847, 1, 1, 2, '9', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (848, 1, 1, 3, '9', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (849, 1, 1, 4, '9', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (850, 1, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (851, 1, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (852, 1, 1, 7, '10', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (853, 1, 1, 8, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (854, 1, 1, 9, '10', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (855, 1, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (856, 1, 1, 11, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (857, 1, 1, 12, '9', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (858, 1, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (859, 1, 1, 14, '10', '0', '1', 0, 0, '5', '10', '2023-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (860, 1, 1, 16, '9', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (861, 1, 1, 17, '9', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (862, 1, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (863, 1, 1, 19, '9', '0', '1', 0, 0, '5', '13', '2023-11-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (864, 1, 1, 20, '9', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (865, 1, 1, 21, '9', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (866, 42, 1, 1, '8', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (867, 42, 1, 2, '6', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (868, 42, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (869, 42, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (870, 42, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (871, 42, 1, 6, '8', '0', '1', 0, 0, '4', '7', '2022-08-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (872, 42, 1, 7, '6', '1', '0', 0, 0, '3', '12', '2022-08-05', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (873, 42, 1, 8, '10', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (874, 42, 1, 9, '8', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (875, 42, 1, 10, '9', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (876, 42, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (877, 42, 1, 12, '7', '0', '1', 0, 0, '5', '3', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (878, 42, 1, 15, '9', '0', '1', 0, 0, '5', '7', '2023-07-03', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (879, 42, 1, 16, '9', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (880, 42, 1, 17, '9', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (881, 42, 1, 19, '7', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (882, 42, 1, 20, '9', '1', '0', 0, 0, '2', '28', '2023-08-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (883, 42, 1, 21, '9', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (884, 42, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (885, 42, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (886, 42, 1, 25, '7', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (919, 76, 1, 1, '7', '0', '1', 0, 0, '4', '1', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (920, 76, 1, 2, '8', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (921, 76, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (922, 76, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (923, 76, 1, 5, '9', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (924, 76, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (925, 76, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (926, 76, 1, 8, '7', '0', '1', 0, 0, '3', '30', '2023-03-21', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (927, 76, 1, 9, '7', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (928, 76, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (929, 76, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (930, 76, 1, 12, '7', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (931, 76, 1, 13, '7', '0', '0', 0, 1, '2', '15', '2023-12-06', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (932, 76, 1, 14, '8', '1', '0', 0, 0, '2', '48', '2024-08-12', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (933, 76, 1, 15, '10', '1', '0', 0, 0, '2', '37', '2023-12-22', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (934, 76, 1, 16, '7', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (935, 76, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (936, 76, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (937, 76, 1, 19, '8', '1', '0', 0, 0, '2', '35', '2024-03-06', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (938, 76, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (939, 76, 1, 21, '8', '0', '1', 0, 0, '5', '14', '2023-11-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (940, 76, 1, 22, '7', '0', '0', 0, 1, '2', '37', '2024-12-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (941, 76, 1, 23, '9', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (942, 76, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (943, 76, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2025-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (944, 76, 1, 26, '5', '0', '0', 0, 1, '2', '45', '2025-03-19', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (945, 76, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (946, 20, 1, 1, '7', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (947, 20, 1, 2, '7', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (948, 20, 1, 3, '9', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (949, 20, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (950, 20, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (951, 20, 1, 6, '9', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (952, 20, 1, 8, '8', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (953, 20, 1, 9, '8', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (954, 20, 1, 10, '10', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (955, 20, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (956, 20, 1, 12, '8', '0', '1', 0, 0, '5', '3', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (957, 20, 1, 13, '10', '0', '1', 0, 0, '5', '1', '2023-06-28', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (958, 20, 1, 14, '4', '1', '0', 0, 0, '2', '33', '2023-12-19', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (959, 20, 1, 15, '9', '1', '0', 0, 0, '3', '7', '2024-12-06', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (960, 20, 1, 16, '9', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (961, 20, 1, 17, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (962, 20, 1, 18, '9', '1', '0', 0, 0, '2', '30', '2023-12-06', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (963, 20, 1, 19, '9', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (964, 20, 1, 20, '8', '1', '0', 0, 0, '2', '28', '2023-08-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (965, 20, 1, 21, '9', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (966, 20, 1, 22, '10', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (967, 20, 1, 23, '10', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (968, 20, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (969, 20, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (970, 20, 1, 27, '8', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (971, 216, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (972, 216, 1, 2, '4', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (973, 216, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (974, 216, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (975, 216, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (976, 36, 1, 1, '8', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (977, 36, 1, 2, '9', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (978, 36, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (979, 36, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (980, 36, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (981, 36, 1, 6, '9', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (982, 36, 1, 7, '8', '0', '1', 0, 0, '4', '5', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (983, 36, 1, 8, '8', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (984, 36, 1, 9, '9', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (985, 36, 1, 10, '10', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (986, 36, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (987, 36, 1, 12, '7', '0', '1', 0, 0, '5', '3', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (988, 36, 1, 16, '8', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (989, 36, 1, 17, '9', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1001, 217, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1002, 217, 1, 4, '10', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1003, 217, 1, 6, '8', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1024, 70, 1, 1, '10', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1025, 70, 1, 2, '7', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1026, 70, 1, 3, '9', '0', '1', 0, 0, '4', '10', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1027, 70, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1028, 70, 1, 5, '9', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1029, 70, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-19', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1030, 70, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1031, 70, 1, 8, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1032, 70, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1033, 70, 1, 10, '9', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1034, 70, 1, 11, '9', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1035, 70, 1, 12, '10', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1036, 70, 1, 13, '10', '1', '0', 0, 0, '2', '20', '2023-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1037, 70, 1, 14, '9', '0', '1', 0, 0, '5', '10', '2023-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1038, 70, 1, 16, '9', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1039, 70, 1, 17, '9', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1040, 70, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1041, 70, 1, 19, '9', '0', '1', 0, 0, '5', '13', '2023-11-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1042, 70, 1, 20, '10', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1043, 70, 1, 21, '10', '0', '1', 0, 0, '5', '14', '2023-11-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1044, 70, 1, 22, '10', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1045, 70, 1, 23, '10', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1046, 70, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1047, 70, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1048, 70, 1, 26, '7', '0', '1', 0, 0, '5', '8', '2024-12-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1049, 218, 1, 1, '5', '1', '0', 0, 0, '3', '14', '2022-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1050, 218, 1, 2, '7', '1', '0', 0, 0, '3', '9', '2022-08-02', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1051, 218, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1052, 218, 1, 4, '9', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1053, 218, 1, 5, '7', '0', '1', 0, 0, '5', '20', '2023-11-22', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1054, 218, 1, 7, '7', '0', '0', 0, 1, '1', '48', '2022-08-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1055, 218, 1, 10, '8', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1056, 218, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1057, 218, 1, 13, '9', '1', '0', 0, 0, '3', '10', '2024-12-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1058, 219, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1059, 219, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1060, 219, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1088, 82, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1089, 82, 1, 2, '9', '1', '0', 0, 0, '4', '8', '2023-12-20', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1090, 82, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1091, 82, 1, 5, '7', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1092, 82, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1093, 82, 1, 7, '9', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1094, 82, 1, 8, '8', '1', '0', 0, 0, '3', '28', '2023-03-07', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1095, 82, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1096, 82, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1097, 82, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1098, 82, 1, 12, '7', '0', '1', 0, 0, '5', '4', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1099, 82, 1, 13, '9', '1', '0', 0, 0, '3', '10', '2024-12-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1100, 82, 1, 16, '8', '1', '0', 0, 0, '2', '26', '2023-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1101, 82, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1102, 82, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1103, 82, 1, 19, '8', '1', '0', 0, 0, '2', '39', '2024-03-06', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1104, 82, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1105, 82, 1, 23, '8', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1106, 82, 1, 25, '8', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1107, 220, 1, 1, '9', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1108, 220, 1, 2, '8', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1109, 220, 1, 3, '8', '0', '1', 0, 0, '4', '10', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1110, 220, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1111, 220, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1112, 220, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1113, 221, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1114, 221, 1, 3, '10', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1115, 221, 1, 4, '9', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1116, 221, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1117, 221, 1, 6, '10', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1118, 221, 1, 7, '10', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1119, 221, 1, 8, '8', '0', '1', 0, 0, '3', '18', '0000-00-00', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1120, 221, 1, 9, '10', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1121, 221, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1122, 221, 1, 11, '8', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1123, 13, 1, 1, '9', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1124, 13, 1, 2, '4', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1125, 13, 1, 3, '7', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1126, 13, 1, 4, '9', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1127, 13, 1, 5, '10', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1128, 13, 1, 6, '9', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1129, 13, 1, 7, '9', '1', '0', 0, 0, '3', '16', '2022-08-12', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1130, 13, 1, 8, '9', '0', '1', 0, 0, '4', '30', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1131, 13, 1, 9, '9', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1132, 13, 1, 10, '9', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1133, 13, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1134, 13, 1, 12, '10', '0', '1', 0, 0, '5', '3', '2023-06-21', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1135, 13, 1, 13, '8', '0', '1', 0, 0, '5', '1', '2023-06-28', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1136, 13, 1, 14, '8', '1', '0', 0, 0, '2', '33', '2023-12-19', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1137, 13, 1, 15, '9', '1', '0', 0, 0, '2', '25', '2023-08-15', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1138, 13, 1, 16, '8', '0', '1', 0, 0, '5', '8', '2023-07-03', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1139, 13, 1, 17, '9', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1140, 13, 1, 18, '9', '1', '0', 0, 0, '2', '30', '2023-12-06', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1141, 13, 1, 19, '7', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1142, 13, 1, 20, '9', '1', '0', 0, 0, '2', '28', '2023-08-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1143, 13, 1, 21, '9', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1144, 13, 1, 22, '10', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1145, 13, 1, 23, '10', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1146, 13, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1147, 13, 1, 25, '10', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1148, 13, 1, 26, '7', '0', '0', 0, 1, '2', '45', '2025-03-19', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1149, 13, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1150, 222, 1, 1, '6', '1', '0', 0, 0, '3', '14', '2022-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1151, 222, 1, 3, '8', '0', '1', 0, 0, '4', '15', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1152, 222, 1, 4, '8', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1153, 222, 1, 5, '8', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1154, 222, 1, 6, '7', '0', '1', 0, 0, '4', '7', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1155, 222, 1, 7, '6', '0', '0', 0, 1, '1', '48', '2022-08-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1156, 222, 1, 10, '8', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1157, 222, 1, 11, '9', '0', '1', 0, 0, '4', '44', '2022-11-18', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1158, 77, 1, 1, '9', '0', '1', 0, 0, '4', '3', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1159, 77, 1, 5, '8', '0', '1', 0, 0, '4', '37', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1160, 77, 1, 9, '8', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1161, 223, 1, 4, '7', '0', '1', 0, 0, '4', '9', '2022-06-30', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1171, 224, 1, 1, '9', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1172, 224, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1173, 224, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1174, 224, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1175, 224, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1176, 224, 1, 8, '9', '0', '1', 0, 0, '4', '31', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1177, 224, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1178, 224, 1, 10, '10', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1179, 224, 1, 11, '8', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1180, 224, 1, 12, '8', '0', '1', 0, 0, '5', '17', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1181, 224, 1, 14, '8', '0', '1', 0, 0, '5', '30', '2024-11-15', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1182, 224, 1, 15, '8', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1183, 224, 1, 16, '7', '0', '1', 0, 0, '5', '23', '2024-07-03', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1184, 224, 1, 17, '8', '0', '1', 0, 0, '5', '25', '2024-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1185, 224, 1, 19, '8', '0', '1', 0, 0, '5', '16', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1186, 224, 1, 21, '9', '0', '1', 0, 0, '5', '32', '2024-11-15', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1207, 14, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1208, 14, 1, 2, '4', '1', '0', 0, 0, '3', '15', '2022-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1209, 14, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1210, 14, 1, 5, '8', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1211, 14, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1212, 14, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1213, 14, 1, 8, '5', '1', '0', 0, 0, '3', '30', '2023-03-21', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1214, 14, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1215, 14, 1, 10, '8', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1216, 14, 1, 13, '5', '0', '0', 0, 1, '2', '1', '2023-08-15', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1217, 14, 1, 16, '8', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1218, 14, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1219, 14, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1220, 14, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1221, 14, 1, 21, '8', '0', '1', 0, 0, '5', '14', '2023-11-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1222, 14, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1223, 14, 1, 25, '9', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1224, 225, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1225, 225, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1226, 225, 1, 5, '7', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1227, 225, 1, 6, '6', '1', '0', 0, 0, '3', '10', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1228, 225, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1229, 225, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1230, 225, 1, 11, '8', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1242, 3, 1, 1, '7', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1243, 3, 1, 2, '6', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1244, 3, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1245, 3, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1246, 3, 1, 5, '7', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1247, 3, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1248, 3, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1249, 3, 1, 8, '7', '1', '0', 0, 0, '3', '25', '2022-12-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1250, 3, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1251, 3, 1, 10, '9', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1252, 3, 1, 11, '9', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1253, 3, 1, 12, '7', '0', '1', 0, 0, '2', '27', '2023-08-16', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1254, 3, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1255, 3, 1, 16, '7', '0', '1', 0, 0, '5', '6', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1256, 3, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1257, 3, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1258, 3, 1, 19, '9', '0', '1', 0, 0, '2', '35', '2023-12-20', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1259, 3, 1, 20, '8', '0', '1', 0, 0, '5', '5', '2023-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1260, 3, 1, 23, '7', '0', '1', 0, 0, '5', '11', '2024-11-11', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1261, 3, 1, 25, '8', '0', '1', 0, 0, '5', '8', '2024-07-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1262, 3, 1, 26, '6', '1', '0', 0, 0, '1', '40', '2024-12-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1263, 68, 1, 1, '9', '0', '1', 0, 0, '4', '4', '2022-06-27', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1264, 68, 1, 2, '5', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1265, 68, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1266, 68, 1, 4, '8', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1267, 68, 1, 5, '10', '0', '1', 0, 0, '4', '27', '2022-11-10', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1268, 68, 1, 6, '9', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1269, 68, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1270, 68, 1, 8, '9', '1', '0', 0, 0, '3', '28', '2022-03-07', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1271, 68, 1, 9, '8', '0', '1', 0, 0, '4', '39', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1272, 68, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1273, 68, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1274, 68, 1, 17, '8', '0', '1', 0, 0, '5', '9', '2023-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1275, 68, 1, 19, '8', '0', '1', 0, 0, '2', '39', '2024-03-06', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1276, 226, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1277, 226, 1, 6, '8', '0', '1', 0, 0, '4', '8', '2022-06-29', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1278, 226, 1, 7, '7', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1279, 227, 1, 3, '7', '0', '1', 0, 0, '4', '16', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1280, 227, 1, 4, '7', '0', '1', 0, 0, '4', '20', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1281, 227, 1, 6, '4', '1', '0', 0, 0, '3', '10', '2022-08-01', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1282, 227, 1, 7, '8', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1283, 227, 1, 11, '7', '0', '1', 0, 0, '4', '36', '2022-11-14', NULL, NULL, '3');
INSERT INTO `tbl_alumnos_materias` VALUES (1316, 75, 1, 1, '8', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1317, 75, 1, 2, '10', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1318, 75, 1, 3, '10', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1319, 75, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1320, 75, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1321, 75, 1, 6, '10', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1322, 75, 1, 7, '10', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1323, 75, 1, 8, '9', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1324, 75, 1, 9, '10', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1325, 75, 1, 10, '10', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1326, 75, 1, 11, '10', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1327, 75, 1, 12, '8', '0', '1', 0, 0, '4', '23', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1328, 75, 1, 13, '8', '0', '1', 0, 0, '4', '22', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1329, 75, 1, 14, '10', '0', '1', 0, 0, '4', '32', '2022-03-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1330, 75, 1, 15, '10', '1', '0', 0, 0, '2', '11', '2022-08-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1331, 75, 1, 16, '9', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1332, 75, 1, 17, '9', '0', '1', 0, 0, '4', '19', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1333, 75, 1, 18, '10', '1', '0', 0, 0, '2', '34', '2023-12-20', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1334, 75, 1, 19, '9', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1335, 75, 1, 20, '9', '1', '0', 0, 0, '2', '29', '2023-08-18', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1336, 75, 1, 21, '8', '0', '1', 0, 0, '4', '28', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1337, 75, 1, 22, '8', '0', '1', 0, 0, '5', '2', '2023-07-23', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1338, 75, 1, 23, '10', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1339, 75, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1340, 75, 1, 25, '9', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1341, 75, 1, 26, '7', '0', '1', 0, 0, '5', '4', '2023-11-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1342, 75, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1353, 229, 1, 3, '7', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1354, 229, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1355, 229, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1356, 229, 1, 10, '8', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1357, 229, 1, 11, '8', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1361, 231, 1, 2, '7', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1362, 231, 1, 3, '7', '1', '0', 0, 0, '2', '35', '2021-11-30', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1363, 231, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1364, 231, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1365, 231, 1, 6, '8', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1366, 231, 1, 7, '9', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1367, 231, 1, 8, '8', '1', '0', 0, 0, '2', '44', '2021-12-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1368, 231, 1, 10, '8', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1369, 231, 1, 11, '8', '0', '1', 0, 0, '2', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1370, 231, 1, 13, '7', '0', '1', 0, 0, '4', '22', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1371, 231, 1, 16, '8', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1414, 233, 1, 1, '9', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1415, 233, 1, 2, '6', '1', '0', 0, 0, '2', '32', '2021-08-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1416, 233, 1, 3, '9', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1417, 233, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1418, 233, 1, 6, '8', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1419, 233, 1, 7, '9', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1420, 233, 1, 8, '7', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1421, 233, 1, 9, '9', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1422, 233, 1, 10, '9', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1423, 233, 1, 11, '9', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1424, 234, 1, 1, '6', '1', '0', 0, 0, '2', '50', '2022-03-10', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1425, 234, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1426, 234, 1, 7, '7', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1427, 234, 1, 9, '8', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1428, 234, 1, 10, '9', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1429, 234, 1, 11, '8', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1430, 234, 1, 12, '7', '0', '1', 0, 0, '4', '23', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1490, 2, 1, 1, '8', '1', '0', 0, 0, '2', '25', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1491, 2, 1, 2, '7', '1', '0', 0, 0, '2', '34', '2021-11-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1492, 2, 1, 3, '9', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1493, 2, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1494, 2, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1495, 2, 1, 6, '8', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1496, 2, 1, 7, '10', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1497, 2, 1, 8, '8', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1498, 2, 1, 9, '8', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1499, 2, 1, 10, '8', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1500, 2, 1, 11, '8', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1501, 2, 1, 12, '8', '0', '1', 0, 0, '4', '23', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1502, 2, 1, 13, '10', '0', '1', 0, 0, '4', '22', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1503, 2, 1, 14, '9', '0', '1', 0, 0, '4', '32', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1504, 2, 1, 15, '8', '1', '0', 0, 0, '2', '17', '2023-03-23', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1505, 2, 1, 16, '8', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1506, 2, 1, 17, '8', '0', '1', 0, 0, '4', '19', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1507, 2, 1, 18, '9', '1', '0', 0, 0, '2', '21', '2023-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1508, 2, 1, 19, '9', '0', '1', 0, 0, '4', '35', '2022-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1509, 2, 1, 20, '9', '1', '0', 0, 0, '2', '19', '2023-03-27', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1510, 2, 1, 21, '10', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1511, 2, 1, 22, '7', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1512, 2, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1513, 2, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1514, 2, 1, 25, '9', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1515, 2, 1, 26, '7', '0', '1', 0, 0, '5', '4', '2023-11-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1516, 2, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1517, 235, 1, 4, '9', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1518, 235, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1519, 235, 1, 9, '7', '0', '1', 0, 0, '3', '50', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1520, 235, 1, 10, '7', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1521, 235, 1, 11, '7', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1522, 236, 1, 4, '8', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1568, 239, 1, 1, '6', '1', '0', 0, 0, '3', '11', '2022-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1569, 239, 1, 2, '6', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1570, 239, 1, 3, '6', '0', '0', 0, 1, '1', '49', '2022-11-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1571, 239, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1572, 239, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1573, 239, 1, 6, '7', '0', '0', 0, 1, '1', '42', '2021-08-11', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1574, 239, 1, 7, '8', '1', '0', 0, 0, '2', '45', '2021-12-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1575, 239, 1, 8, '7', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1576, 239, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1577, 239, 1, 10, '9', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1578, 239, 1, 11, '9', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1579, 239, 1, 12, '9', '1', '0', 0, 0, '2', '16', '2023-03-08', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1580, 239, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1581, 239, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1582, 239, 1, 15, '10', '0', '0', 0, 1, '2', '22', '2023-12-22', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1583, 239, 1, 16, '8', '0', '0', 0, 1, '1', '54', '2023-08-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1584, 239, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1585, 239, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1586, 239, 1, 19, '8', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1587, 239, 1, 20, '9', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1588, 239, 1, 21, '8', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1589, 239, 1, 22, '8', '0', '0', 0, 1, '2', '5', '2023-08-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1590, 239, 1, 23, '7', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1591, 239, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1592, 239, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1593, 239, 1, 26, '6', '1', '0', 0, 0, '1', '36', '2023-12-18', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1594, 239, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1654, 241, 1, 1, '6', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1655, 241, 1, 2, '9', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1656, 241, 1, 3, '8.25', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1657, 241, 1, 4, '9', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1658, 241, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1659, 241, 1, 6, '9', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1660, 241, 1, 7, '9', '1', '0', 0, 0, '2', '45', '2021-12-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1661, 241, 1, 8, '10', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1662, 241, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1663, 241, 1, 10, '9', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1664, 241, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1665, 241, 1, 12, '9', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1666, 241, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1667, 241, 1, 14, '9', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1668, 241, 1, 15, '10', '0', '1', 0, 0, '2', '9', '2022-08-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1669, 241, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1670, 241, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1671, 241, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1672, 241, 1, 19, '9', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1673, 241, 1, 20, '9', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1674, 241, 1, 21, '10', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1675, 241, 1, 22, '9', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1676, 241, 1, 23, '10', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1677, 241, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1678, 241, 1, 25, '9', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1679, 241, 1, 26, '4', '1', '0', 0, 0, '3', '35', '2023-12-05', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1680, 241, 1, 27, '10', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1713, 242, 1, 1, '6', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1714, 242, 1, 2, '9', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1715, 242, 1, 3, '7.80', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1716, 242, 1, 4, '9', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1717, 242, 1, 5, '9', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1718, 242, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1719, 242, 1, 7, '10', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1720, 242, 1, 8, '7', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1721, 242, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1722, 242, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1723, 242, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1724, 242, 1, 12, '8', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1725, 242, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1726, 242, 1, 14, '9', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1727, 242, 1, 15, '8', '1', '0', 0, 0, '2', '17', '2023-03-23', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1728, 242, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1729, 242, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1730, 242, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1731, 242, 1, 19, '8', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1732, 242, 1, 20, '9', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1733, 242, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1734, 242, 1, 22, '8', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1735, 242, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1736, 242, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1737, 242, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1738, 242, 1, 26, '7', '1', '0', 0, 0, '3', '35', '2023-12-05', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1739, 242, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1772, 243, 1, 1, '7', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1773, 243, 1, 2, '8', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1774, 243, 1, 3, '9', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1775, 243, 1, 4, '9', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1776, 243, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1777, 243, 1, 6, '5', '1', '0', 0, 0, '2', '27', '2021-08-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1778, 243, 1, 7, '6', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1779, 243, 1, 8, '7', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1780, 243, 1, 9, '7', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1781, 243, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1782, 243, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1783, 243, 1, 12, '8', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1784, 243, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1785, 243, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1786, 243, 1, 15, '8', '1', '0', 0, 0, '2', '17', '2023-03-23', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1787, 243, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1788, 243, 1, 17, '10', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1789, 243, 1, 18, '9', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1790, 243, 1, 19, '8', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1791, 243, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1792, 243, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1793, 243, 1, 22, '7', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1794, 243, 1, 23, '9', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1795, 243, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1796, 243, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1797, 243, 1, 26, '4', '1', '0', 0, 0, '3', '35', '2023-12-05', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1798, 243, 1, 27, '9', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1830, 244, 1, 1, '8', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1831, 244, 1, 2, '7', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1832, 244, 1, 3, '7.75', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1833, 244, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1834, 244, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1835, 244, 1, 6, '6', '1', '0', 0, 0, '2', '27', '2021-08-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1836, 244, 1, 7, '9', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1837, 244, 1, 8, '8', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1838, 244, 1, 9, '9', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1839, 244, 1, 10, '9', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1840, 244, 1, 11, '9', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1841, 244, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1842, 244, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1843, 244, 1, 14, '7', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1844, 244, 1, 16, '8', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1845, 244, 1, 17, '7', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1846, 244, 1, 18, '8', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1847, 244, 1, 19, '8', '0', '1', 0, 0, '2', '15', '2022-12-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1848, 244, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1849, 244, 1, 21, '7', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1850, 244, 1, 22, '8', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1851, 244, 1, 23, '7', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1852, 244, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1853, 244, 1, 25, '7', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1854, 245, 1, 2, '10', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1855, 245, 1, 3, '9', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1856, 245, 1, 4, '10', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1857, 245, 1, 6, '9', '0', '1', 0, 0, '3', '25', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1858, 245, 1, 7, '9', '0', '1', 0, 0, '4', '21', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1859, 245, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1860, 245, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1861, 246, 1, 3, '7.25', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1862, 246, 1, 4, '7', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1895, 247, 1, 1, '7', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1896, 247, 1, 2, '4', '1', '0', 0, 0, '2', '32', '2021-08-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1897, 247, 1, 3, '7.75', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1898, 247, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1899, 247, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1900, 247, 1, 6, '7', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1901, 247, 1, 7, '8', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1902, 247, 1, 8, '6', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1903, 247, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1904, 247, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1905, 247, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1906, 247, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1907, 247, 1, 13, '7', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1908, 247, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1909, 247, 1, 15, '9', '1', '0', 0, 0, '3', '11', '2024-12-20', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1910, 247, 1, 16, '9', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1911, 247, 1, 17, '8', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1912, 247, 1, 18, '8', '0', '1', 0, 0, '5', '29', '2024-11-13', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1913, 247, 1, 19, '8', '1', '0', 0, 0, '2', '13', '2022-12-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1914, 247, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1915, 247, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1916, 247, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1917, 247, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1918, 247, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1919, 247, 1, 27, '8', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1920, 248, 1, 4, '7', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1932, 249, 1, 1, '10', '1', '0', 0, 0, '2', '31', '2021-08-12', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1933, 249, 1, 2, '9', '1', '0', 0, 0, '2', '24', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1934, 249, 1, 3, '10', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1935, 249, 1, 4, '10', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1936, 249, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1937, 249, 1, 6, '9', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1938, 249, 1, 7, '10', '1', '0', 0, 0, '2', '45', '2021-12-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1939, 249, 1, 8, '8', '0', '1', 0, 0, '3', '40', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1940, 249, 1, 9, '9', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1941, 249, 1, 10, '10', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1942, 249, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1943, 249, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1944, 249, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1945, 249, 1, 16, '8', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1946, 249, 1, 17, '7', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1947, 249, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1948, 250, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1949, 250, 1, 5, '10', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1950, 228, 1, 1, '6', '1', '0', 0, 0, '2', '25', '2021-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1951, 228, 1, 2, '7', '1', '0', 0, 0, '2', '34', '2021-11-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1952, 228, 1, 3, '7.50', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1953, 228, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1954, 228, 1, 5, '10', '1', '0', 0, 0, '2', '39', '2021-12-03', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1955, 228, 1, 6, '9', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1956, 228, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1957, 228, 1, 8, '8', '0', '1', 0, 0, '3', '39', '2021-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1958, 228, 1, 10, '9', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1959, 228, 1, 11, '9', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1960, 230, 1, 3, '8.50', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1961, 230, 1, 4, '8', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1962, 230, 1, 7, '7', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1963, 232, 1, 1, '9', '1', '0', 0, 0, '3', '11', '2022-08-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1964, 232, 1, 2, '8', '1', '0', 0, 0, '3', '1', '2022-03-21', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1965, 232, 1, 3, '7.75', '0', '1', 0, 0, '3', '23', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1966, 232, 1, 4, '10', '0', '1', 0, 0, '3', '24', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1967, 232, 1, 5, '9', '1', '0', 0, 0, '3', '8', '2022-03-25', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1968, 232, 1, 6, '9', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1969, 232, 1, 7, '8', '0', '1', 0, 0, '3', '22', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1970, 232, 1, 8, '9', '1', '0', 0, 0, '3', '28', '2023-03-07', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1971, 232, 1, 9, '9', '0', '1', 0, 0, '4', '38', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1972, 232, 1, 10, '9', '0', '1', 0, 0, '4', '43', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1973, 232, 1, 11, '9', '0', '1', 0, 0, '3', '52', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1974, 232, 1, 12, '9', '0', '1', 0, 0, '5', '18', '2024-06-26', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1975, 232, 1, 13, '7', '0', '0', 1, 0, '1', '1', '2021-05-19', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1976, 232, 1, 14, '10', '1', '0', 0, 0, '3', '13', '2025-03-11', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1977, 232, 1, 16, '10', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1978, 232, 1, 17, '8', '0', '1', 0, 0, '5', '2', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1979, 232, 1, 18, '10', '1', '0', 0, 0, '2', '30', '2023-12-06', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1980, 232, 1, 19, '8', '0', '1', 0, 0, '5', '11', '2023-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1981, 232, 1, 21, '9', '0', '1', 0, 0, '5', '28', '2024-11-13', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1982, 232, 1, 22, '8', '0', '1', 0, 0, '5', '9', '2024-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1983, 232, 1, 23, '9', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1984, 237, 1, 3, '7.50', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1985, 237, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1986, 237, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1987, 238, 1, 1, '8', '1', '0', 0, 0, '3', '7', '2022-03-25', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1988, 238, 1, 2, '6', '1', '0', 0, 0, '3', '9', '2022-08-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1989, 238, 1, 3, '7.25', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1990, 238, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1991, 238, 1, 5, '9', '0', '1', 0, 0, '3', '38', '2021-11-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1992, 238, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1993, 238, 1, 7, '7', '1', '0', 0, 0, '2', '38', '2021-12-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1994, 238, 1, 8, '9', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1995, 238, 1, 9, '9', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1996, 238, 1, 10, '7', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1997, 238, 1, 11, '7', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1998, 238, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (1999, 238, 1, 14, '8', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2000, 238, 1, 16, '9', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2001, 238, 1, 17, '8', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2002, 238, 1, 19, '7', '0', '1', 0, 0, '2', '13', '2022-12-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2003, 238, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2004, 238, 1, 21, '8', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2005, 238, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2006, 238, 1, 25, '7', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2007, 240, 1, 1, '8', '1', '0', 0, 0, '2', '43', '2021-12-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2008, 240, 1, 2, '6', '1', '0', 0, 0, '3', '1', '2022-03-21', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2009, 240, 1, 3, '9.12', '0', '1', 0, 0, '3', '25', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2010, 240, 1, 4, '8', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2011, 240, 1, 5, '8', '0', '1', 0, 0, '3', '38', '2021-11-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2012, 240, 1, 6, '8', '0', '1', 0, 0, '3', '19', '2021-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2013, 240, 1, 8, '9', '1', '0', 0, 0, '2', '44', '2021-12-15', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2014, 240, 1, 9, '8', '0', '1', 0, 0, '3', '51', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2015, 240, 1, 10, '10', '0', '1', 0, 0, '3', '44', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2016, 240, 1, 11, '8', '0', '1', 0, 0, '4', '1', '2021-11-17', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2017, 240, 1, 12, '7', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2018, 240, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2019, 240, 1, 16, '8', '0', '1', 0, 0, '4', '12', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2020, 240, 1, 17, '7', '0', '1', 0, 0, '4', '10', '2022-07-01', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2021, 240, 1, 18, '8', '0', '1', 0, 0, '4', '26', '2022-11-09', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2022, 240, 1, 20, '7', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2023, 240, 1, 25, '7', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '4');
INSERT INTO `tbl_alumnos_materias` VALUES (2024, 251, 1, 1, '8', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2025, 251, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2026, 251, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2027, 252, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2028, 252, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2029, 252, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2030, 252, 1, 4, '7', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2031, 252, 1, 5, '10', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2032, 252, 1, 6, '7', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2033, 252, 1, 7, '8', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2034, 252, 1, 8, '7', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2035, 252, 1, 9, '8', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2036, 252, 1, 10, '7', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2037, 252, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2038, 253, 1, 1, '8', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2039, 253, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2040, 253, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2041, 253, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2042, 253, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2043, 253, 1, 6, '7', '0', '0', 0, 1, '1', '20', '2019-09-23', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2044, 253, 1, 7, '8', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2045, 253, 1, 8, '9', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2046, 253, 1, 9, '7', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2047, 253, 1, 10, '8', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2048, 253, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2049, 254, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2050, 254, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2051, 254, 1, 3, '10', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2052, 254, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2053, 254, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2054, 254, 1, 6, '10', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2055, 254, 1, 7, '10', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2056, 254, 1, 8, '10', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2057, 254, 1, 9, '10', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2058, 254, 1, 10, '9', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2059, 254, 1, 11, '8', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2060, 254, 1, 12, '9', '1', '0', 0, 0, '1', '34', '2020-09-09', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2061, 254, 1, 14, '8', '1', '0', 0, 0, '1', '32', '2020-09-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2062, 254, 1, 17, '8', '0', '1', 0, 0, '3', '5', '2020-11-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2063, 255, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2064, 256, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2065, 256, 1, 2, '10', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2066, 256, 1, 3, '10', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2067, 256, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2068, 256, 1, 5, '10', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2069, 256, 1, 6, '10', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2070, 256, 1, 7, '9', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2071, 256, 1, 8, '10', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2072, 256, 1, 9, '9', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2073, 256, 1, 10, '9', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2074, 256, 1, 11, '9', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2075, 256, 1, 12, '10', '0', '0', 0, 1, '1', '39', '2020-12-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2076, 256, 1, 13, '4', '0', '0', 0, 1, '1', '35', '2020-12-11', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2077, 256, 1, 14, '10', '1', '0', 0, 0, '1', '44', '2020-03-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2078, 256, 1, 15, '4', '1', '0', 0, 0, '1', '53', '2021-10-22', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2079, 256, 1, 16, '10', '1', '0', 0, 0, '1', '33', '2020-09-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2080, 256, 1, 17, '8', '0', '1', 0, 0, '3', '5', '2020-11-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2081, 256, 1, 18, '8', '1', '0', 0, 0, '2', '18', '2023-03-23', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2082, 256, 1, 19, '8', '0', '1', 0, 0, '3', '45', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2083, 256, 1, 20, '10', '1', '0', 0, 0, '1', '51', '2021-09-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2084, 256, 1, 21, '8', '0', '1', 0, 0, '4', '28', '2022-11-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2085, 256, 1, 22, '10', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2086, 256, 1, 23, '7', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2087, 256, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2088, 256, 1, 25, '9', '0', '0', 0, 1, '1', '50', '2022-12-01', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2089, 256, 1, 26, '8', '1', '0', 0, 0, '1', '39', '2024-08-16', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2090, 257, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2091, 257, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2092, 257, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2093, 257, 1, 4, '9', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2094, 257, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2095, 257, 1, 6, '8', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2096, 258, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2097, 258, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2098, 258, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2099, 258, 1, 4, '9', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2100, 258, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2101, 258, 1, 6, '7', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2102, 258, 1, 7, '9', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2103, 258, 1, 9, '8', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2104, 258, 1, 10, '8', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2105, 258, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2106, 259, 1, 1, '9', '0', '1', 0, 0, '2', '18', '2019-09-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2107, 259, 1, 2, '8', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2108, 259, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2109, 259, 1, 4, '7', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2110, 259, 1, 5, '8', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2111, 259, 1, 6, '8', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2112, 259, 1, 7, '9', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2113, 259, 1, 8, '8', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2114, 259, 1, 9, '7', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2115, 259, 1, 10, '7', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2116, 259, 1, 11, '7', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2117, 260, 1, 1, '10', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2118, 260, 1, 2, '9', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2119, 260, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2120, 260, 1, 4, '10', '0', '1', 0, 0, '2', '27', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2121, 260, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2122, 260, 1, 6, '8', '0', '1', 0, 0, '2', '36', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2123, 260, 1, 7, '10', '0', '1', 0, 0, '2', '49', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2124, 260, 1, 8, '9', '0', '1', 0, 0, '2', '44', '2019-11-05', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2125, 260, 1, 9, '8', '0', '1', 0, 0, '2', '52', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2126, 260, 1, 10, '7', '0', '1', 0, 0, '2', '50', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2127, 260, 1, 11, '8', '0', '1', 0, 0, '1', '37', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2128, 15, 1, 1, '7', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2129, 15, 1, 2, '7', '0', '1', 0, 0, '2', '34', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2130, 15, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2131, 15, 1, 4, '10', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2132, 15, 1, 5, '9', '0', '1', 0, 0, '2', '29', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2133, 15, 1, 6, '7', '0', '1', 0, 0, '3', '18', '2021-07-01', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2134, 15, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2135, 15, 1, 8, '8', '1', '0', 0, 0, '2', '36', '2021-12-01', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2136, 15, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2137, 15, 1, 10, '10', '0', '1', 0, 0, '3', '43', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2138, 15, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2139, 15, 1, 12, '8', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2140, 15, 1, 13, '4', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2141, 15, 1, 14, '8', '0', '1', 0, 0, '4', '32', '2022-11-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2142, 15, 1, 15, '9', '1', '0', 0, 0, '2', '25', '2023-08-15', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2143, 15, 1, 16, '9', '0', '1', 0, 0, '4', '17', '2022-07-04', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2144, 15, 1, 17, '8', '0', '1', 0, 0, '4', '19', '2022-07-04', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2145, 15, 1, 18, '8', '1', '0', 0, 0, '2', '21', '2023-08-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2146, 15, 1, 19, '9', '0', '1', 0, 0, '3', '45', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2147, 15, 1, 20, '9', '1', '0', 0, 0, '2', '19', '2023-03-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2148, 15, 1, 21, '10', '0', '1', 0, 0, '5', '12', '2023-11-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2149, 15, 1, 22, '7', '0', '1', 0, 0, '5', '2', '2023-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2150, 15, 1, 23, '8', '0', '1', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2151, 15, 1, 24, '8', '0', '1', 0, 0, '5', '7', '2024-06-26', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2152, 15, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2153, 15, 1, 26, '6', '1', '0', 0, 0, '1', '36', '2023-12-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2154, 15, 1, 27, '10', '0', '1', 0, 0, '5', '10', '2024-11-11', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2155, 261, 1, 1, '7', '0', '1', 0, 0, '2', '18', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2156, 261, 1, 3, '7', '0', '1', 0, 0, '2', '32', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2157, 262, 1, 1, '9', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2158, 262, 1, 2, '10', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2159, 262, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2160, 262, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2161, 262, 1, 5, '9', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2162, 262, 1, 6, '10', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2163, 262, 1, 7, '9', '1', '0', 0, 0, '2', '9', '2020-12-11', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2164, 262, 1, 8, '9', '0', '1', 0, 0, '2', '48', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2165, 262, 1, 9, '9', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2166, 262, 1, 10, '7', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2167, 262, 1, 11, '8', '0', '1', 0, 0, '1', '40', '2019-11-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2168, 262, 1, 12, '9', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2169, 262, 1, 13, '10', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2170, 262, 1, 14, '9', '1', '0', 0, 0, '1', '24', '2020-08-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2171, 262, 1, 15, '10', '1', '0', 0, 0, '1', '25', '2020-08-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2172, 262, 1, 16, '9', '1', '0', 0, 0, '1', '28', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2173, 262, 1, 17, '10', '0', '1', 0, 0, '3', '6', '2020-11-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2174, 262, 1, 18, '8', '1', '0', 0, 0, '1', '38', '2020-12-15', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2175, 262, 1, 19, '9', '1', '0', 0, 0, '1', '26', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2176, 262, 1, 20, '10', '1', '0', 0, 0, '1', '27', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2177, 262, 1, 21, '10', '0', '1', 0, 0, '3', '11', '2020-11-19', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2178, 262, 1, 22, '9', '0', '1', 0, 0, '3', '32', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2179, 262, 1, 23, '8', '0', '1', 0, 0, '3', '48', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2180, 262, 1, 24, '8', '0', '1', 0, 0, '3', '33', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2181, 262, 1, 25, '9', '0', '1', 0, 0, '3', '34', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2182, 262, 1, 26, '9', '1', '0', 0, 0, '1', '29', '2021-11-30', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2183, 262, 1, 27, '8', '0', '1', 0, 0, '3', '46', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2184, 263, 1, 1, '10', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2185, 263, 1, 2, '10', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2186, 263, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2187, 263, 1, 4, '10', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2188, 263, 1, 5, '9', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2189, 263, 1, 6, '10', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2190, 263, 1, 7, '10', '0', '1', 0, 0, '2', '43', '2019-11-04', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2191, 263, 1, 8, '10', '0', '1', 0, 0, '2', '48', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2192, 263, 1, 9, '10', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2193, 263, 1, 10, '9', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2194, 263, 1, 11, '8', '0', '1', 0, 0, '1', '40', '2019-11-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2195, 263, 1, 12, '10', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2196, 263, 1, 13, '10', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2197, 263, 1, 14, '10', '1', '0', 0, 0, '1', '24', '2020-08-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2198, 263, 1, 15, '10', '1', '0', 0, 0, '1', '25', '2020-08-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2199, 263, 1, 16, '10', '1', '0', 0, 0, '1', '28', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2200, 263, 1, 17, '10', '0', '1', 0, 0, '3', '6', '2020-11-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2201, 263, 1, 18, '9', '1', '0', 0, 0, '1', '38', '2020-12-15', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2202, 263, 1, 19, '10', '1', '0', 0, 0, '1', '26', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2203, 263, 1, 20, '10', '1', '0', 0, 0, '1', '27', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2204, 263, 1, 21, '10', '0', '1', 0, 0, '3', '11', '2020-11-19', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2205, 263, 1, 22, '10', '0', '1', 0, 0, '3', '32', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2206, 263, 1, 23, '10', '0', '1', 0, 0, '3', '48', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2207, 263, 1, 24, '10', '0', '1', 0, 0, '3', '33', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2208, 263, 1, 25, '10', '0', '1', 0, 0, '3', '34', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2209, 263, 1, 26, '7', '1', '0', 0, 0, '1', '29', '2021-11-30', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2210, 263, 1, 27, '9', '0', '1', 0, 0, '3', '46', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2211, 264, 1, 1, '8', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2212, 264, 1, 2, '9', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2213, 264, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2214, 264, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2215, 264, 1, 5, '9', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2216, 264, 1, 6, '10', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2217, 264, 1, 7, '10', '0', '1', 0, 0, '2', '43', '2019-11-04', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2218, 264, 1, 8, '10', '0', '1', 0, 0, '2', '48', '2019-11-06', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2219, 264, 1, 9, '10', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2220, 264, 1, 10, '9', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2221, 264, 1, 11, '8', '0', '1', 0, 0, '1', '40', '2019-11-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2222, 264, 1, 12, '10', '1', '0', 0, 0, '1', '22', '2020-08-11', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2223, 264, 1, 13, '10', '1', '0', 0, 0, '1', '30', '2020-08-31', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2224, 264, 1, 14, '9', '1', '0', 0, 0, '1', '29', '2020-08-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2225, 264, 1, 15, '10', '1', '0', 0, 0, '1', '25', '2020-08-12', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2226, 264, 1, 16, '10', '1', '0', 0, 0, '1', '28', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2227, 264, 1, 17, '10', '0', '1', 0, 0, '3', '6', '2020-11-18', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2228, 264, 1, 18, '10', '1', '0', 0, 0, '1', '38', '2020-12-15', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2229, 264, 1, 19, '8', '1', '0', 0, 0, '1', '26', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2230, 264, 1, 20, '10', '1', '0', 0, 0, '1', '27', '2020-08-14', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2231, 264, 1, 21, '10', '0', '1', 0, 0, '3', '11', '2020-11-19', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2232, 264, 1, 22, '10', '0', '1', 0, 0, '3', '32', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2233, 264, 1, 23, '9', '0', '1', 0, 0, '3', '48', '2021-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2234, 264, 1, 24, '10', '0', '1', 0, 0, '3', '33', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2235, 264, 1, 25, '10', '0', '1', 0, 0, '3', '34', '2021-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2236, 264, 1, 26, '7', '0', '1', 0, 0, '3', '41', '2023-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2237, 264, 1, 27, '7', '0', '1', 0, 0, '3', '46', '2023-11-17', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2238, 265, 1, 9, '10', '0', '1', 0, 0, '2', '53', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2239, 265, 1, 10, '7', '0', '1', 0, 0, '2', '51', '2019-11-07', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2240, 266, 1, 1, '8', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2241, 266, 1, 2, '8', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2242, 266, 1, 3, '7', '0', '1', 0, 0, '2', '33', '2019-07-20', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2243, 266, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2244, 266, 1, 5, '8', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2245, 267, 1, 1, '8', '0', '1', 0, 0, '2', '19', '2019-06-25', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2246, 267, 1, 2, '10', '0', '1', 0, 0, '2', '35', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2247, 267, 1, 3, '9', '0', '1', 0, 0, '2', '33', '2019-07-02', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2248, 267, 1, 4, '7', '0', '1', 0, 0, '2', '28', '2019-06-28', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2249, 267, 1, 5, '8', '0', '1', 0, 0, '2', '23', '2019-06-27', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2250, 267, 1, 6, '9', '0', '1', 0, 0, '2', '37', '2019-07-03', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2251, 267, 1, 7, '10', '0', '1', 0, 0, '2', '43', '2019-11-04', NULL, NULL, '6');
INSERT INTO `tbl_alumnos_materias` VALUES (2284, 268, 1, 1, '9', '1', '0', 0, 0, '2', '15', '2020-12-21', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2285, 268, 1, 2, '9', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2286, 268, 1, 3, '10', '1', '0', 0, 0, '1', '45', '2020-09-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2287, 268, 1, 4, '10', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2288, 268, 1, 5, '10', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2289, 268, 1, 6, '10', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2290, 268, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2291, 268, 1, 8, '10', '0', '1', 0, 0, '3', '12', '2020-11-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2292, 268, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2293, 268, 1, 10, '10', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2294, 268, 1, 11, '8', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2295, 268, 1, 12, '9', '0', '1', 0, 0, '3', '27', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2296, 268, 1, 13, '6', '1', '0', 0, 0, '2', '5', '2021-12-13', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2297, 268, 1, 14, '10', '0', '1', 0, 0, '3', '36', '2021-11-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2298, 268, 1, 15, '9', '0', '1', 0, 0, '3', '20', '2021-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2299, 268, 1, 16, '10', '1', '0', 0, 0, '1', '52', '2021-09-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2300, 268, 1, 17, '10', '0', '1', 0, 0, '3', '26', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2301, 268, 1, 18, '9', '1', '0', 0, 0, '2', '8', '2022-03-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2302, 268, 1, 19, '9', '0', '1', 0, 0, '3', '45', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2303, 268, 1, 20, '9', '1', '0', 0, 0, '1', '54', '2021-10-25', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2304, 268, 1, 21, '9', '0', '1', 0, 0, '3', '47', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2305, 268, 1, 22, '10', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2306, 268, 1, 23, '10', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2307, 268, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2308, 268, 1, 25, '10', '0', '1', 0, 0, '4', '13', '2022-07-07', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2309, 268, 1, 26, '8', '0', '1', 0, 0, '4', '34', '2022-11-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2310, 268, 1, 27, '10', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2322, 269, 1, 1, '6', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2323, 269, 1, 2, '9', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2324, 269, 1, 3, '7', '1', '0', 0, 0, '1', '45', '2020-09-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2325, 269, 1, 4, '10', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2326, 269, 1, 5, '9', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2327, 269, 1, 6, '8', '1', '0', 0, 0, '2', '3', '2020-09-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2328, 269, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2329, 269, 1, 8, '8', '1', '0', 0, 0, '2', '14', '2020-12-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2330, 269, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2331, 269, 1, 10, '7', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2332, 269, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2333, 269, 1, 17, '7', '0', '1', 0, 0, '3', '26', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2334, 270, 1, 1, '4', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2335, 270, 1, 2, '6', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2336, 270, 1, 3, '7', '0', '0', 0, 1, '1', '28', '2020-09-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2337, 270, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2338, 270, 1, 5, '9', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2339, 270, 1, 7, '8', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2340, 270, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2341, 270, 1, 10, '8', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2342, 270, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2343, 270, 1, 12, '7', '0', '1', 0, 0, '3', '27', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2344, 271, 1, 2, '7', '1', '0', 0, 0, '1', '54', '2020-09-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2345, 271, 1, 5, '10', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2346, 271, 1, 7, '7', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2347, 271, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2348, 271, 1, 11, '10', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2349, 272, 1, 1, '4', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2350, 272, 1, 2, '6', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2351, 272, 1, 3, '4', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2352, 272, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2353, 272, 1, 5, '7', '0', '1', 0, 0, '3', '9', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2354, 272, 1, 7, '8', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2355, 272, 1, 9, '10', '0', '1', 0, 0, '3', '3', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2356, 272, 1, 10, '7', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2357, 272, 1, 11, '7', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2358, 272, 1, 16, '4', '1', '0', 0, 0, '1', '46', '2021-08-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2359, 272, 1, 20, '9', '1', '0', 0, 0, '1', '50', '2021-08-11', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2360, 273, 1, 2, '7', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2361, 273, 1, 7, '6', '1', '0', 0, 0, '2', '48', '2022-03-07', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2362, 273, 1, 10, '7', '0', '1', 0, 0, '3', '7', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2363, 275, 1, 3, '6', '1', '0', 0, 0, '1', '45', '2020-09-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2364, 275, 1, 4, '7', '0', '0', 0, 1, '1', '31', '2020-09-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2365, 275, 1, 5, '9', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2366, 275, 1, 7, '10', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2367, 275, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2368, 275, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2369, 275, 1, 11, '7', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2370, 276, 1, 1, '6', '1', '0', 0, 0, '1', '53', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2371, 276, 1, 2, '7', '1', '0', 0, 0, '2', '12', '2020-12-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2372, 276, 1, 3, '6', '1', '0', 0, 0, '2', '6', '2020-12-09', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2373, 276, 1, 4, '7', '1', '0', 0, 0, '2', '4', '2020-09-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2374, 276, 1, 5, '7', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2375, 276, 1, 7, '7', '1', '0', 0, 0, '2', '1', '2020-09-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2376, 276, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2377, 276, 1, 10, '7', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2378, 276, 1, 11, '9', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2379, 276, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2380, 276, 1, 14, '7', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2381, 276, 1, 16, '8', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2382, 276, 1, 19, '7', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2383, 276, 1, 20, '7', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2394, 69, 1, 1, '10', '1', '0', 0, 0, '3', '11', '2022-08-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2395, 69, 1, 2, '10', '1', '0', 0, 0, '2', '32', '2021-08-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2396, 69, 1, 3, '7', '0', '0', 0, 1, '1', '52', '2023-03-21', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2397, 69, 1, 4, '9', '1', '0', 0, 0, '1', '49', '2020-09-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2398, 69, 1, 5, '9', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2399, 69, 1, 6, '8', '1', '0', 0, 0, '3', '40', '2023-09-25', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2400, 69, 1, 8, '10', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2401, 69, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2402, 69, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2403, 69, 1, 11, '9', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2404, 69, 1, 12, '9', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2405, 69, 1, 13, '8', '0', '1', 0, 0, '5', '15', '2024-06-25', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2406, 69, 1, 14, '9', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2407, 69, 1, 15, '10', '0', '1', 0, 0, '5', '21', '2024-06-27', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2408, 69, 1, 16, '8', '0', '1', 0, 0, '5', '27', '2024-07-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2409, 69, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2410, 69, 1, 18, '8', '0', '1', 0, 0, '5', '25', '2024-11-13', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2411, 69, 1, 19, '7', '0', '1', 0, 0, '3', '29', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2412, 69, 1, 20, '10', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2413, 69, 1, 23, '9', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2435, 277, 1, 1, '10', '1', '0', 0, 0, '2', '15', '2020-12-21', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2436, 277, 1, 2, '10', '1', '0', 0, 0, '2', '16', '2021-03-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2437, 277, 1, 3, '9', '1', '0', 0, 0, '2', '22', '2021-03-23', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2438, 277, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2439, 277, 1, 5, '9', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2440, 277, 1, 6, '10', '1', '0', 0, 0, '2', '13', '2020-12-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2441, 277, 1, 7, '10', '1', '0', 0, 0, '2', '9', '2020-12-11', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2442, 277, 1, 8, '10', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2443, 277, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2444, 277, 1, 10, '9', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2445, 277, 1, 11, '9', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2446, 277, 1, 12, '9', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2447, 277, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2448, 277, 1, 14, '10', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2449, 277, 1, 15, '10', '0', '1', 0, 0, '3', '17', '2021-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2450, 277, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2451, 277, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2452, 277, 1, 18, '9', '0', '1', 0, 0, '2', '8', '2022-03-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2453, 277, 1, 19, '7', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2454, 277, 1, 20, '10', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2455, 277, 1, 21, '10', '0', '1', 0, 0, '4', '2', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2456, 277, 1, 22, '8', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2457, 277, 1, 23, '10', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2458, 277, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2459, 277, 1, 25, '9', '0', '1', 0, 0, '4', '13', '2022-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2460, 277, 1, 26, '10', '1', '0', 0, 0, '1', '32', '2022-12-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2461, 277, 1, 27, '10', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2462, 278, 1, 3, '9', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2463, 278, 1, 4, '7', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2464, 278, 1, 8, '8', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2465, 278, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2466, 278, 1, 10, '7', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2467, 278, 1, 11, '8', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2500, 279, 1, 1, '8', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2501, 279, 1, 2, '10', '1', '0', 0, 0, '1', '54', '2020-09-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2502, 279, 1, 3, '10', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2503, 279, 1, 4, '9', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2504, 279, 1, 5, '10', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2505, 279, 1, 6, '7', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2506, 279, 1, 7, '7', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2507, 279, 1, 8, '8', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2508, 279, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2509, 279, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2510, 279, 1, 11, '9', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2511, 279, 1, 12, '7', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2512, 279, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2513, 279, 1, 14, '8', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2514, 279, 1, 15, '5', '1', '0', 0, 0, '1', '53', '2021-10-22', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2515, 279, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2516, 279, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2517, 279, 1, 18, '9', '1', '0', 0, 0, '2', '8', '2022-03-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2518, 279, 1, 19, '7', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2519, 279, 1, 20, '8', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2520, 279, 1, 21, '10', '0', '1', 0, 0, '4', '2', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2521, 279, 1, 22, '8', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2522, 279, 1, 23, '9', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2523, 279, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2524, 279, 1, 25, '9', '0', '1', 0, 0, '4', '13', '2022-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2525, 279, 1, 26, '10', '1', '0', 0, 0, '1', '32', '2022-12-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2526, 279, 1, 27, '8', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2559, 280, 1, 1, '6', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2560, 280, 1, 2, '7', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2561, 280, 1, 3, '10', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2562, 280, 1, 4, '8', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2563, 280, 1, 5, '10', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2564, 280, 1, 6, '10', '1', '0', 0, 0, '1', '44', '2020-09-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2565, 280, 1, 7, '7', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2566, 280, 1, 8, '10', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2567, 280, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2568, 280, 1, 10, '9', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2569, 280, 1, 11, '8', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2570, 280, 1, 12, '8', '0', '1', 0, 0, '3', '30', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2571, 280, 1, 13, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2572, 280, 1, 14, '7', '0', '1', 0, 0, '3', '37', '2021-11-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2573, 280, 1, 15, '10', '0', '1', 0, 0, '3', '17', '2021-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2574, 280, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2575, 280, 1, 17, '10', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2576, 280, 1, 18, '5', '1', '0', 0, 0, '2', '1', '2021-12-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2577, 280, 1, 19, '9', '0', '1', 0, 0, '3', '42', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2578, 280, 1, 20, '9', '0', '1', 0, 0, '3', '29', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2579, 280, 1, 21, '10', '0', '1', 0, 0, '4', '2', '2021-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2580, 280, 1, 22, '9', '0', '1', 0, 0, '4', '11', '2022-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2581, 280, 1, 23, '9', '0', '1', 0, 0, '4', '41', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2582, 280, 1, 24, '10', '0', '1', 0, 0, '4', '18', '2022-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2583, 280, 1, 25, '10', '0', '1', 0, 0, '4', '13', '2022-07-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2584, 280, 1, 26, '9', '1', '0', 0, 0, '1', '33', '2022-12-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2585, 280, 1, 27, '10', '0', '1', 0, 0, '4', '29', '2022-11-14', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2586, 281, 1, 1, '6', '1', '0', 0, 0, '1', '43', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2587, 281, 1, 2, '7', '1', '0', 0, 0, '1', '42', '2020-08-31', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2588, 281, 1, 3, '9', '1', '0', 0, 0, '1', '51', '2020-09-08', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2589, 281, 1, 4, '7', '0', '1', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2590, 281, 1, 5, '10', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2591, 281, 1, 6, '9', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2592, 281, 1, 7, '10', '1', '0', 0, 0, '1', '40', '2020-08-27', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2593, 281, 1, 9, '10', '0', '1', 0, 0, '3', '4', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2594, 281, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2595, 281, 1, 11, '10', '0', '1', 0, 0, '3', '1', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2596, 281, 1, 13, '10', '0', '0', 0, 1, '1', '46', '2022-08-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2597, 281, 1, 16, '8', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2598, 34, 1, 2, '6', '1', '0', 0, 0, '1', '54', '2020-09-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2599, 34, 1, 4, '7', '0', '1', 0, 0, '3', '21', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2600, 34, 1, 5, '7', '0', '1', 0, 0, '3', '38', '2021-11-09', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2601, 34, 1, 7, '9', '0', '1', 0, 0, '5', '10', '2023-07-07', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2602, 34, 1, 10, '7', '0', '1', 0, 0, '4', '42', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2603, 34, 1, 11, '7', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2604, 34, 1, 13, '8', '0', '1', 0, 0, '4', '24', '2022-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2637, 84, 1, 1, '7', '1', '0', 0, 0, '2', '43', '2021-12-15', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2638, 84, 1, 2, '9', '1', '0', 0, 0, '1', '46', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2639, 84, 1, 3, '10', '0', '0', 0, 1, '1', '28', '2020-09-01', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2640, 84, 1, 4, '7', '1', '0', 0, 0, '1', '41', '2020-08-28', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2641, 84, 1, 5, '8', '0', '1', 0, 0, '3', '10', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2642, 84, 1, 6, '10', '1', '0', 0, 0, '1', '52', '2020-09-09', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2643, 84, 1, 7, '10', '1', '0', 0, 0, '1', '48', '2020-09-03', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2644, 84, 1, 8, '9', '0', '1', 0, 0, '3', '13', '2020-11-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2645, 84, 1, 9, '9', '1', '0', 0, 0, '3', '17', '2022-08-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2646, 84, 1, 10, '8', '0', '1', 0, 0, '3', '8', '2020-11-19', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2647, 84, 1, 11, '7', '0', '1', 0, 0, '3', '2', '2020-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2648, 84, 1, 12, '9', '0', '1', 0, 0, '4', '25', '2022-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2649, 84, 1, 13, '9', '0', '1', 0, 0, '3', '31', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2650, 84, 1, 14, '9', '0', '1', 0, 0, '4', '33', '2022-11-14', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2651, 84, 1, 15, '10', '1', '0', 0, 0, '2', '32', '2023-12-11', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2652, 84, 1, 16, '9', '0', '1', 0, 0, '3', '35', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2653, 84, 1, 17, '8', '0', '1', 0, 0, '3', '28', '2021-07-02', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2654, 84, 1, 18, '8', '0', '0', 0, 1, '2', '20', '2023-12-20', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2655, 84, 1, 19, '7', '0', '1', 0, 0, '4', '45', '2022-11-18', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2656, 84, 1, 20, '8', '0', '1', 0, 0, '4', '14', '2022-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2657, 84, 1, 21, '9', '0', '1', 0, 0, '4', '40', '2022-11-17', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2658, 84, 1, 22, '9', '1', '0', 0, 0, '1', '34', '2023-08-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2659, 84, 1, 23, '8', '1', '0', 0, 0, '5', '6', '2023-11-16', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2660, 84, 1, 24, '8', '0', '1', 0, 0, '5', '3', '2023-07-04', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2661, 84, 1, 25, '8', '0', '1', 0, 0, '5', '1', '2023-06-29', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2662, 84, 1, 26, '7', '0', '1', 0, 0, '2', '46', '2023-05-07', NULL, NULL, '5');
INSERT INTO `tbl_alumnos_materias` VALUES (2663, 84, 1, 27, '10', '0', '1', 0, 0, '5', '5', '2023-11-15', NULL, NULL, '5');

-- ----------------------------
-- Table structure for tbl_alumnos_mesa
-- ----------------------------
DROP TABLE IF EXISTS `tbl_alumnos_mesa`;
CREATE TABLE `tbl_alumnos_mesa`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `dni` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `celular` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `legajo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `anno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `materia` int NOT NULL DEFAULT 0,
  `turno` int NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL,
  `apellido` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `curso` int NOT NULL,
  `division` int NOT NULL,
  `cursado` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mesa` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_alumnos_mesa
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_carreras
-- ----------------------------
DROP TABLE IF EXISTS `tbl_carreras`;
CREATE TABLE `tbl_carreras`  (
  `Id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `resolucion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_carreras
-- ----------------------------
INSERT INTO `tbl_carreras` VALUES (1, 'TSDS', NULL);
INSERT INTO `tbl_carreras` VALUES (2, 'PEI', NULL);
INSERT INTO `tbl_carreras` VALUES (3, 'PEP', NULL);
INSERT INTO `tbl_carreras` VALUES (4, 'TST', NULL);
INSERT INTO `tbl_carreras` VALUES (5, 'TSM', NULL);

-- ----------------------------
-- Table structure for tbl_cursar
-- ----------------------------
DROP TABLE IF EXISTS `tbl_cursar`;
CREATE TABLE `tbl_cursar`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `mesa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `cerra_mesa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado_cerra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_cursar
-- ----------------------------
INSERT INTO `tbl_cursar` VALUES (1, 'cursar_agosto_2_semestre', '1', '2025-08-20 20:40:10', '0', '0');

-- ----------------------------
-- Table structure for tbl_materias
-- ----------------------------
DROP TABLE IF EXISTS `tbl_materias`;
CREATE TABLE `tbl_materias`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `semestre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `anno` int NULL DEFAULT NULL,
  `paracursar_regular` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'codigo materia :',
  `paracursar_rendido` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'codigo materia :',
  `pararendir` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'codigo materia :',
  `resolucion` int NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 145 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_materias
-- ----------------------------
INSERT INTO `tbl_materias` VALUES (1, 'Comprensión y producción de texto', '1', 1, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (2, 'Matemática I', '1', 1, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (3, 'Procesamiento de Datos', '1', 1, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (4, 'Sistemas de Información I', '1', 1, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (5, 'Inglés Técnico I', '1', 1, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (6, 'Programación I', '1', 1, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (7, 'Contexto socio-economico y productivo', '2', 1, 1, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (8, 'Programación II', '2', 1, 1, '6', '', '6', 1);
INSERT INTO `tbl_materias` VALUES (9, 'Ambiente Empresarial', '2', 1, 1, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (10, 'Practica Profesionalizante I', '2', 1, 1, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (11, 'Sistemas Operativos y Redes I', '2', 1, 1, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (12, 'Marco juridico especifico', '1', 1, 2, NULL, NULL, '', 1);
INSERT INTO `tbl_materias` VALUES (13, 'Matemática II', '2', 1, 2, '2', NULL, '2', 1);
INSERT INTO `tbl_materias` VALUES (14, 'Sistemas Administrativos', '2', 1, 2, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (15, 'Programación III', '1', 1, 2, '8', '2', '8', 1);
INSERT INTO `tbl_materias` VALUES (16, 'Base de Datos I', '1', 1, 2, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (17, 'Practica Profesionalizante II', '1', 1, 2, '', '10', '', 1);
INSERT INTO `tbl_materias` VALUES (18, 'Matemática III', '2', 1, 2, '13', '2', '13', 1);
INSERT INTO `tbl_materias` VALUES (19, 'Sistemas de Información II', '2', 1, 2, '', '4', NULL, 1);
INSERT INTO `tbl_materias` VALUES (20, 'Desarrollo de Software I', '1', 1, 2, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (21, 'Practica Profesionalizante III', '2', 1, 2, '', '17', '', 1);
INSERT INTO `tbl_materias` VALUES (22, 'Base de Datos II', '1', 1, 3, '16', NULL, '16', 1);
INSERT INTO `tbl_materias` VALUES (23, 'Sistemas Operativos y Redes II', '2', 1, 3, '', '11', NULL, 1);
INSERT INTO `tbl_materias` VALUES (24, 'Practica Profesionalizante IV', '1', 1, 3, '', '21', '', 1);
INSERT INTO `tbl_materias` VALUES (25, 'Desarrollo de Software II', '1', 1, 3, '', '20', NULL, 1);
INSERT INTO `tbl_materias` VALUES (26, 'Etica y deontologia profesional', '2', 1, 3, '', NULL, '1', 1);
INSERT INTO `tbl_materias` VALUES (27, 'Practica Profesionalizante V', '2', 1, 3, '', '24', '', 1);
INSERT INTO `tbl_materias` VALUES (28, 'Aporte de sociologia y antroprologia a la educacion', '1', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (29, 'Psicologia educacional', '1', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (30, 'Sujeto de nivel inicial I', '1', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (31, 'Practicas de lengua y literatura', '1', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (32, 'Teorias de la educacion', '2', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (33, 'Didactica y curriculum', '2', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (34, 'Iniciacion a las tic', '2', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (35, 'Sujeto de nivel inicial II', '2', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (36, 'Ciencias sociales', '2', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (37, 'Practica I', '1', 2, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (38, 'Historia social argentina y la latinoamericana', '1', 2, 2, '36', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (39, 'Didactica de nivel I', '1', 2, 2, '29:30:33', NULL, '30', 1);
INSERT INTO `tbl_materias` VALUES (40, 'Matematica', '1', 2, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (41, 'Ciencias naturales', '1', 2, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (42, 'Plástica y su didáctica', '2', 2, 2, '30:33', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (43, 'Estado, sociedad y educación', '2', 2, 2, '28:38', NULL, '28', 1);
INSERT INTO `tbl_materias` VALUES (44, 'Filosofía y conocimiento', '2', 2, 2, '28:32', NULL, '28', 1);
INSERT INTO `tbl_materias` VALUES (45, 'Didactica de nivel II', '2', 2, 2, '35:39', '33', '29:30:35', 1);
INSERT INTO `tbl_materias` VALUES (46, 'Literatura infantil', '2', 2, 2, '30:31:33', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (47, 'Expresión corporal y su didáctica', '2', 2, 2, '33:35', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (48, 'Practica II', '1', 2, 2, '28:30:33', '37', '33', 1);
INSERT INTO `tbl_materias` VALUES (49, 'Investigación educativa I', '1', 2, 3, '37:44', '28:', NULL, 1);
INSERT INTO `tbl_materias` VALUES (50, 'Didáctica de la lengua y la literatura infantil', '1', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (51, 'Didáctica  de las ciencias sociales', '1', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (52, 'Música y su didáctica', '1', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (53, 'Formación ética y ciudadana', '2', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (54, 'Comunicación, cultura y tic', '2', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (55, 'Didáctica de la matematica', '2', 2, 3, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (56, 'Didáctica de las ciencias naturales', '2', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (57, 'Educación física en el nivel inicial', '2', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (58, 'Práctica III', '1', 2, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (59, 'Investigación educativa II', '1', 2, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (60, 'UDI I', '1', 2, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (61, 'UDI II (CFE)', '2', 2, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (62, 'UDI III', '2', 2, 4, '', NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (63, 'Práctica IV', '1', 2, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (64, 'Aporte de sociología y antropología a la educación', '1', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (65, 'Psicologia educacional', '1', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (66, 'Matemática', '1', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (67, 'Ciencias naturales', '1', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (68, 'Teorías de la educación', '2', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (69, 'Iniciación a las TIC', '2', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (70, 'Lengua y literatura', '2', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (71, 'Ciencias sociales', '2', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (72, 'Sujeto de la educación primaria I', '2', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (73, 'Didactica y curriculum', '2', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (74, 'Práctica I', '1', 3, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (75, 'Historia social argentina y latinoamericana', '1', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (76, 'Alfabetización', '1', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (77, 'Didáctica de las ciencias sociales', '1', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (78, 'Sujeto de la educación primaria II', '1', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (79, 'Estado, sociedad y educación', '2', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (80, 'Filosofía y conocimiento', '2', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (81, 'Didáctica de la matematica I', '2', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (82, 'Didáctica de las ciencias Naturales I', '2', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (83, 'Práctica II', '1', 3, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (84, 'Investigación educativa I', '1', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (85, 'Didáctica de la matematica II', '1', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (86, 'Didáctica de las ciencias naturales II', '1', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (87, 'Educación tecnológica', '1', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (88, 'Formación Etica y ciudadana', '2', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (89, 'Comunicación, cultura y TIC', '2', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (90, 'Didáctica de la lengua y la literatura', '2', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (91, 'Didáctica de las ciencias sociales II', '2', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (92, 'Práctica III', '1', 3, 3, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (93, 'Investigación educativa II', '1', 3, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (94, 'UDI I', '1', 3, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (95, 'UDI II (CFE)', '2', 3, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (96, 'UDI III', '2', 3, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (97, 'Práctica IV', '1', 3, 4, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (98, 'Compresiòn y Producciòn de texto', NULL, -4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (99, 'Contexto Social Econòmico y Productivo', '', 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (100, 'Ingles Tècnico I', '', 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (101, 'Quimica Aplicada I', '', 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (102, 'Matematica Aplicada I', NULL, 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (103, 'Geologia', NULL, -4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (104, 'Compresion y Produccion de textos', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (105, 'Contexto Socio Economico y Productivo', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (106, 'Ingles Tecnico I', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (107, 'Quimica Aplicada I', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (108, 'Matematica Aplicada I', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (110, 'Quimica Aplicada II', NULL, 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (111, 'Matematica Aplicada II', NULL, 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (113, 'Ingles Tècnico II', NULL, 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (115, 'Tecnica de prospecciòn', NULL, 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (116, 'Fìsica Aplicada I', NULL, 4, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (117, 'Ingles Tecnico III', '1', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (118, 'Portugues Tecnico I', '1', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (119, 'Marketing de Servicios turisticos', '1', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (120, 'Oratoria', '1', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (121, 'Practica Profesionalizante III', '1', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (122, 'Portugues Tecnico II', '2', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (123, 'Informatica Aplicada', '2', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (124, 'Organizacion de Empresas Turisticas', '2', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (125, 'Geografia Turistica II', '2', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (126, 'Sistema de Transporte para el Turismo', '2', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (127, 'Practica Profesionalizante IV', '2', 4, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (128, 'Geologia', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (129, 'Ingles Tecnico II', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (130, 'Quimica Aplicada II', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (131, 'Matematica Aplicada II', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (132, 'Fisica Aplicada I', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (133, 'Tecnicas de Prospeccion', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (134, 'Practicas Profesionalizante I', NULL, 5, 1, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (135, 'Seguridad e Higiene', '1', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (136, 'Ingles Tecnico III', '1', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (137, 'Fisica Aplicada II', '1', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (138, 'Mineralogia y Petrografia', '1', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (139, 'Practica Profesionalizante II', '1', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (140, 'Explotacion Minera', '2', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (141, 'Prospeccion Geofisica I', '2', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (142, 'Prospeccion Geoquimica I', '2', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (143, 'Economia y Costos de Exploracion', '2', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (144, 'Gestion Ambiental Minera', '2', 5, 2, NULL, NULL, NULL, 1);
INSERT INTO `tbl_materias` VALUES (145, 'Practica Profesionalizante III', '2', 5, 2, NULL, NULL, NULL, 1);

-- ----------------------------
-- Table structure for tbl_materias_alumno
-- ----------------------------
DROP TABLE IF EXISTS `tbl_materias_alumno`;
CREATE TABLE `tbl_materias_alumno`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `alumno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `estado` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha_cancelacion` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_materias_alumno
-- ----------------------------
INSERT INTO `tbl_materias_alumno` VALUES (1, '22720764', 1, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00');
INSERT INTO `tbl_materias_alumno` VALUES (2, '22720764', 2, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00');
INSERT INTO `tbl_materias_alumno` VALUES (3, '22720764', 3, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00');
INSERT INTO `tbl_materias_alumno` VALUES (4, '22720764', 4, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00');
INSERT INTO `tbl_materias_alumno` VALUES (5, '22720764', 6, '2024-03-18 12:52:19', '', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for tbl_materias_alumno_cursa
-- ----------------------------
DROP TABLE IF EXISTS `tbl_materias_alumno_cursa`;
CREATE TABLE `tbl_materias_alumno_cursa`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `alumno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `observacion` varchar(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `estado` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha_cancelacion` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_materias_alumno_cursa
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_materias_alumno_mesa
-- ----------------------------
DROP TABLE IF EXISTS `tbl_materias_alumno_mesa`;
CREATE TABLE `tbl_materias_alumno_mesa`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `alumno` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `observacion` varchar(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `estado` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha_cancelacion` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_materias_alumno_mesa
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_mesas
-- ----------------------------
DROP TABLE IF EXISTS `tbl_mesas`;
CREATE TABLE `tbl_mesas`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `mesa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `cerra_mesa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado_cerra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_mesas
-- ----------------------------
INSERT INTO `tbl_mesas` VALUES (1, 'mesa_agosto_1_llamado', '1', '2025-07-16 20:40:10', '0', '0');

-- ----------------------------
-- Table structure for tbl_profesor_tiene_materias
-- ----------------------------
DROP TABLE IF EXISTS `tbl_profesor_tiene_materias`;
CREATE TABLE `tbl_profesor_tiene_materias`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `profesor` int NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `materia` int NULL DEFAULT NULL,
  `cursado` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `division` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_profesor_tiene_materias
-- ----------------------------
INSERT INTO `tbl_profesor_tiene_materias` VALUES (1, 1, 1, 16, '2023', '1');
INSERT INTO `tbl_profesor_tiene_materias` VALUES (3, 2, 1, 11, '', '1');
INSERT INTO `tbl_profesor_tiene_materias` VALUES (4, 1, 1, 24, '', '1');

-- ----------------------------
-- Table structure for tbl_profesores
-- ----------------------------
DROP TABLE IF EXISTS `tbl_profesores`;
CREATE TABLE `tbl_profesores`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `apellido` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dni` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `division` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_profesores
-- ----------------------------
INSERT INTO `tbl_profesores` VALUES (1, 'beron', 'fabian ruben', NULL, '22720764', 1, 1);

-- ----------------------------
-- Table structure for tbl_resoluciones
-- ----------------------------
DROP TABLE IF EXISTS `tbl_resoluciones`;
CREATE TABLE `tbl_resoluciones`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `carrera` int NULL DEFAULT NULL,
  `ano1` int NULL DEFAULT NULL,
  `ano2` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_resoluciones
-- ----------------------------
INSERT INTO `tbl_resoluciones` VALUES (1, 'res-8167', 1, 2017, 2019);
INSERT INTO `tbl_resoluciones` VALUES (2, 'res-10518', 2, 2015, 2019);
INSERT INTO `tbl_resoluciones` VALUES (3, 'res-10519', 3, 2015, 2019);
INSERT INTO `tbl_resoluciones` VALUES (4, 'res-274', 1, 2016, 2016);
INSERT INTO `tbl_resoluciones` VALUES (5, 'res-1515', 2, 2009, 2014);
INSERT INTO `tbl_resoluciones` VALUES (6, 'res-1514', 3, 2009, 2014);

-- ----------------------------
-- Table structure for tbl_tipos_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `tbl_tipos_usuarios`;
CREATE TABLE `tbl_tipos_usuarios`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_tipos_usuarios
-- ----------------------------
INSERT INTO `tbl_tipos_usuarios` VALUES (1, 'admin');
INSERT INTO `tbl_tipos_usuarios` VALUES (2, 'usuario');

-- ----------------------------
-- Table structure for tbl_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `tbl_usuarios`;
CREATE TABLE `tbl_usuarios`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `usuario` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `clave` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tipo` int NOT NULL,
  `pais` int NOT NULL,
  `provincia` int NOT NULL,
  `sexo` int NOT NULL,
  `avatar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_usuarios
-- ----------------------------
INSERT INTO `tbl_usuarios` VALUES (1, 'fabian beron', 'admin', '123456', 'frberon@gmail.com', '12321', 1, 1, 1, 1, 'avatar-mini2.jpg');
INSERT INTO `tbl_usuarios` VALUES (2, 'puesto1', 'usuario1', '123456', 'frberon@gmail.com', '121321', 2, 1, 2, 1, 'avatar-mini2.jpg');

SET FOREIGN_KEY_CHECKS = 1;
