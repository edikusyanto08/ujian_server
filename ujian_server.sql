/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100129
 Source Host           : 127.0.0.1:3306
 Source Schema         : ujian_server

 Target Server Type    : MySQL
 Target Server Version : 100129
 File Encoding         : 65001

 Date: 02/07/2019 09:10:58
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_jenis_nilai
-- ----------------------------
DROP TABLE IF EXISTS `tb_jenis_nilai`;
CREATE TABLE `tb_jenis_nilai`  (
  `jn_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jn_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `jn_sing` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `jn_status` tinyint(4) NULL DEFAULT 1,
  `jn_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`jn_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_keahlian_kompetensi
-- ----------------------------
DROP TABLE IF EXISTS `tb_keahlian_kompetensi`;
CREATE TABLE `tb_keahlian_kompetensi`  (
  `kk_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kk_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kk_sing` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kk_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kk_status` tinyint(4) NULL DEFAULT 1,
  `kk_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `kk_urut` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`kk_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_mapel
-- ----------------------------
DROP TABLE IF EXISTS `tb_mapel`;
CREATE TABLE `tb_mapel`  (
  `mapel_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kk_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `mapel_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mapel_sing` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mapel_tingkat` int(11) NULL DEFAULT 10,
  `mapel_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mapel_status` tinyint(4) NULL DEFAULT 1,
  `mapel_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mapel_id`) USING BTREE,
  INDEX `kk_id`(`kk_id`) USING BTREE,
  CONSTRAINT `tb_mapel_ibfk_1` FOREIGN KEY (`kk_id`) REFERENCES `tb_keahlian_kompetensi` (`kk_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_media
-- ----------------------------
DROP TABLE IF EXISTS `tb_media`;
CREATE TABLE `tb_media`  (
  `media_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `media_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mapel_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `media_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `media_type` enum('image','sound','video') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'image',
  `media_status` tinyint(4) NULL DEFAULT 1,
  `media_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`media_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 250 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_pengawas
-- ----------------------------
DROP TABLE IF EXISTS `tb_pengawas`;
CREATE TABLE `tb_pengawas`  (
  `pn_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pn_nomor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pn_fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pn_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pn_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pn_status` tinyint(4) NULL DEFAULT 1,
  `pn_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pn_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_quiz
-- ----------------------------
DROP TABLE IF EXISTS `tb_quiz`;
CREATE TABLE `tb_quiz`  (
  `quiz_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jn_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `quiz_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `quiz_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `quiz_start` datetime(0) NULL DEFAULT NULL,
  `quiz_end` datetime(0) NULL DEFAULT NULL,
  `quiz_tapel` year NULL DEFAULT NULL,
  `quiz_timer` int(11) NULL DEFAULT 10,
  `quiz_jml_soal` int(11) NULL DEFAULT 1,
  `quiz_random_soal` tinyint(4) NULL DEFAULT 0,
  `quiz_random_pg` tinyint(4) NULL DEFAULT 0,
  `quiz_status` tinyint(4) NULL DEFAULT 1,
  `quiz_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`quiz_id`) USING BTREE,
  INDEX `jn_id`(`jn_id`) USING BTREE,
  CONSTRAINT `tb_quiz_ibfk_1` FOREIGN KEY (`jn_id`) REFERENCES `tb_jenis_nilai` (`jn_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_quiz_hasil
-- ----------------------------
DROP TABLE IF EXISTS `tb_quiz_hasil`;
CREATE TABLE `tb_quiz_hasil`  (
  `qh_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `soal_id` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pg_id` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `quiz_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `mapel_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `qh_score` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`qh_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28933 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_quiz_mapel
-- ----------------------------
DROP TABLE IF EXISTS `tb_quiz_mapel`;
CREATE TABLE `tb_quiz_mapel`  (
  `qm_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mapel_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `quiz_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `qm_status` tinyint(4) NULL DEFAULT 1,
  `qm_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`qm_id`) USING BTREE,
  INDEX `mapel_id`(`mapel_id`) USING BTREE,
  INDEX `quiz_id`(`quiz_id`) USING BTREE,
  CONSTRAINT `tb_quiz_mapel_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `tb_mapel` (`mapel_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_mapel_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `tb_quiz` (`quiz_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_quiz_nilai
-- ----------------------------
DROP TABLE IF EXISTS `tb_quiz_nilai`;
CREATE TABLE `tb_quiz_nilai`  (
  `qn_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `jn_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `mapel_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `sis_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `qn_tapel` year NULL DEFAULT NULL,
  `qn_smt` int(11) NULL DEFAULT 1,
  `qn_nilai` int(20) NULL DEFAULT NULL,
  `qn_status` tinyint(4) NULL DEFAULT 1,
  `qn_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `qn_rank` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`qn_id`) USING BTREE,
  INDEX `quiz_id`(`quiz_id`) USING BTREE,
  INDEX `jn_id`(`jn_id`) USING BTREE,
  INDEX `mapel_id`(`mapel_id`) USING BTREE,
  INDEX `sis_id`(`sis_id`) USING BTREE,
  CONSTRAINT `tb_quiz_nilai_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `tb_quiz` (`quiz_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_nilai_ibfk_2` FOREIGN KEY (`jn_id`) REFERENCES `tb_jenis_nilai` (`jn_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_nilai_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `tb_mapel` (`mapel_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_nilai_ibfk_4` FOREIGN KEY (`sis_id`) REFERENCES `tb_siswa` (`sis_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 724 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_quiz_soal
-- ----------------------------
DROP TABLE IF EXISTS `tb_quiz_soal`;
CREATE TABLE `tb_quiz_soal`  (
  `qs_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soal_id` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `qm_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `mapel_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `soal_nomor` int(11) NULL DEFAULT NULL,
  `qs_status` tinyint(4) NULL DEFAULT 1,
  `qs_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`qs_id`) USING BTREE,
  INDEX `soal_id`(`soal_id`) USING BTREE,
  INDEX `sis_id`(`sis_id`) USING BTREE,
  INDEX `qm_id`(`qm_id`) USING BTREE,
  INDEX `mapel_id`(`mapel_id`) USING BTREE,
  CONSTRAINT `tb_quiz_soal_ibfk_3` FOREIGN KEY (`qm_id`) REFERENCES `tb_quiz_mapel` (`qm_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_soal_ibfk_4` FOREIGN KEY (`mapel_id`) REFERENCES `tb_mapel` (`mapel_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_soal_ibfk_5` FOREIGN KEY (`soal_id`) REFERENCES `tb_soal` (`soal_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_soal_ibfk_6` FOREIGN KEY (`sis_id`) REFERENCES `tb_siswa` (`sis_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_quiz_soal_pg
-- ----------------------------
DROP TABLE IF EXISTS `tb_quiz_soal_pg`;
CREATE TABLE `tb_quiz_soal_pg`  (
  `qspg_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qspg_nomor` int(11) NULL DEFAULT 1,
  `qm_id` bigint(20) NULL DEFAULT NULL,
  `sis_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `qs_id` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pg_id` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `qspg_status` tinyint(4) NULL DEFAULT 1,
  `qspg_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`qspg_id`) USING BTREE,
  INDEX `pg_id`(`pg_id`) USING BTREE,
  INDEX `qs_id`(`qs_id`) USING BTREE,
  CONSTRAINT `tb_quiz_soal_pg_ibfk_2` FOREIGN KEY (`qs_id`) REFERENCES `tb_quiz_soal` (`qs_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_quiz_soal_pg_ibfk_3` FOREIGN KEY (`pg_id`) REFERENCES `tb_soal_pg` (`pg_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_ruang_member
-- ----------------------------
DROP TABLE IF EXISTS `tb_ruang_member`;
CREATE TABLE `tb_ruang_member`  (
  `rm_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sr_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `sis_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 's20190000_000001',
  `pn_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `rm_sesi` int(11) NULL DEFAULT 1,
  `rm_status` tinyint(4) NULL DEFAULT 1,
  `rm_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `rm_type` enum('pes','pen') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pes',
  PRIMARY KEY (`rm_id`) USING BTREE,
  INDEX `sr_id`(`sr_id`) USING BTREE,
  INDEX `sis_id`(`sis_id`) USING BTREE,
  INDEX `pn_id`(`pn_id`) USING BTREE,
  CONSTRAINT `tb_ruang_member_ibfk_1` FOREIGN KEY (`sr_id`) REFERENCES `tb_server_ruang` (`sr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_ruang_member_ibfk_2` FOREIGN KEY (`sis_id`) REFERENCES `tb_siswa` (`sis_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_ruang_member_ibfk_3` FOREIGN KEY (`pn_id`) REFERENCES `tb_pengawas` (`pn_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 365 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_school
-- ----------------------------
DROP TABLE IF EXISTS `tb_school`;
CREATE TABLE `tb_school`  (
  `sch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sch_logo_dinas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sch_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sch_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sch_kepsek` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sch_kepsek_nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sch_kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_server
-- ----------------------------
DROP TABLE IF EXISTS `tb_server`;
CREATE TABLE `tb_server`  (
  `server_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jn_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `server_kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `server_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `server_jml_client` int(11) NULL DEFAULT NULL,
  `server_tapel` year NULL DEFAULT NULL,
  `server_status` tinyint(4) NULL DEFAULT 1,
  `server_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `server_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `server_sync` tinyint(4) NULL DEFAULT 0,
  `server_activated` tinyint(4) NULL DEFAULT 0,
  PRIMARY KEY (`server_id`) USING BTREE,
  INDEX `jn_id`(`jn_id`) USING BTREE,
  CONSTRAINT `tb_server_ibfk_1` FOREIGN KEY (`jn_id`) REFERENCES `tb_jenis_nilai` (`jn_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_server_ruang
-- ----------------------------
DROP TABLE IF EXISTS `tb_server_ruang`;
CREATE TABLE `tb_server_ruang`  (
  `sr_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `sr_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sr_status` tinyint(4) NULL DEFAULT 1,
  `sr_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sr_id`) USING BTREE,
  INDEX `server_id`(`server_id`) USING BTREE,
  CONSTRAINT `tb_server_ruang_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `tb_server` (`server_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_siswa
-- ----------------------------
DROP TABLE IF EXISTS `tb_siswa`;
CREATE TABLE `tb_siswa`  (
  `sis_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `erapor_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `kk_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `sis_nopes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_tapel` year NULL DEFAULT NULL,
  `sis_kelas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_tingkat` int(11) NULL DEFAULT NULL,
  `sis_nis` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_sex` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'L',
  `sis_bdate` date NULL DEFAULT NULL,
  `sis_bplace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sis_agama` enum('Islam','Kristen','Protestan','Hindu','Buddha') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Islam',
  `sis_status` tinyint(4) NULL DEFAULT 1,
  `sis_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sis_id`) USING BTREE,
  INDEX `kk_id`(`kk_id`) USING BTREE,
  CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`kk_id`) REFERENCES `tb_keahlian_kompetensi` (`kk_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_soal
-- ----------------------------
DROP TABLE IF EXISTS `tb_soal`;
CREATE TABLE `tb_soal`  (
  `soal_id` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mapel_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `soal_nomor` int(11) NULL DEFAULT NULL,
  `soal_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `soal_type` enum('pg','uraian','singkat') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pg',
  `soal_score` int(11) NULL DEFAULT 1,
  `soal_status` tinyint(4) NULL DEFAULT 1,
  `soal_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `soal_tuntas_min` int(11) NULL DEFAULT 70,
  PRIMARY KEY (`soal_id`) USING BTREE,
  INDEX `mapel_id`(`mapel_id`) USING BTREE,
  CONSTRAINT `tb_soal_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `tb_mapel` (`mapel_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_soal_pg
-- ----------------------------
DROP TABLE IF EXISTS `tb_soal_pg`;
CREATE TABLE `tb_soal_pg`  (
  `pg_id` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soal_id` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pg_nomor` int(11) NULL DEFAULT NULL,
  `pg_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `pg_score` int(11) NULL DEFAULT 1,
  `pg_is_right` tinyint(4) NULL DEFAULT 0,
  `pg_status` tinyint(4) NULL DEFAULT 1,
  `pg_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pg_id`) USING BTREE,
  INDEX `soal_id`(`soal_id`) USING BTREE,
  CONSTRAINT `tb_soal_pg_ibfk_1` FOREIGN KEY (`soal_id`) REFERENCES `tb_soal` (`soal_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user`  (
  `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_level` tinyint(4) NULL DEFAULT 1,
  `user_status` tinyint(4) NULL DEFAULT 1,
  `user_created` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
