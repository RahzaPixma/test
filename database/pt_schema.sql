-- Baseline database restore for the Prayer Time display app.
-- Import with: mysql -u root -p < database/pt_schema.sql

CREATE DATABASE IF NOT EXISTS `pt` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pt`;

CREATE TABLE IF NOT EXISTS `tbm_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` int(11) NOT NULL DEFAULT 431,
  `lokasi` varchar(255) NOT NULL DEFAULT 'Kuala Lumpur',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbm_zone` (`id`, `zone`, `lokasi`) VALUES (1, 431, 'Kuala Lumpur')
ON DUPLICATE KEY UPDATE `zone`=VALUES(`zone`), `lokasi`=VALUES(`lokasi`);

CREATE TABLE IF NOT EXISTS `tbm_duration` (
  `item` varchar(50) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbm_duration` (`item`, `duration`) VALUES
('slide', 10), ('worldclock', 10), ('taqwim', 10), ('jadualkuliah', 10), ('countdown', 10), ('blinking', 60)
ON DUPLICATE KEY UPDATE `duration`=VALUES(`duration`);

CREATE TABLE IF NOT EXISTS `tbm_hijrioffset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hijri_offset` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `tbm_hijrioffset` (`id`, `hijri_offset`) VALUES (1, 0)
ON DUPLICATE KEY UPDATE `hijri_offset`=VALUES(`hijri_offset`);

CREATE TABLE IF NOT EXISTS `tbm_anim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anim` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `tbm_anim` (`id`, `anim`) VALUES (1, 1)
ON DUPLICATE KEY UPDATE `anim`=VALUES(`anim`);

CREATE TABLE IF NOT EXISTS `tbm_mazhab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `tbm_mazhab` (`id`, `type`) VALUES (1, 1)
ON DUPLICATE KEY UPDATE `type`=VALUES(`type`);

CREATE TABLE IF NOT EXISTS `tbm_scroller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `speed` int(11) NOT NULL DEFAULT 15,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `tbm_scroller` (`id`, `text`, `speed`) VALUES (1, 'Selamat datang', 15)
ON DUPLICATE KEY UPDATE `text`=VALUES(`text`), `speed`=VALUES(`speed`);

CREATE TABLE IF NOT EXISTS `tbm_azan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imsak` varchar(255) NOT NULL DEFAULT 'beepbeep.mp4',
  `subuh` varchar(255) NOT NULL DEFAULT 'azansubuhmekah.mp3',
  `syuruk` varchar(255) NOT NULL DEFAULT 'beepbeep.mp4',
  `zohor` varchar(255) NOT NULL DEFAULT 'azanmekah.mp3',
  `asar` varchar(255) NOT NULL DEFAULT 'azanmekah.mp3',
  `maghrib` varchar(255) NOT NULL DEFAULT 'azanmekah.mp3',
  `isyak` varchar(255) NOT NULL DEFAULT 'azanmekah.mp3',
  `jumaat` varchar(255) NOT NULL DEFAULT 'azanmekah.mp3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `tbm_azan` (`id`) VALUES (1) ON DUPLICATE KEY UPDATE `id`=`id`;

CREATE TABLE IF NOT EXISTS `tbm_solat` (
  `hari` varchar(20) NOT NULL,
  `subuh` int(11) NOT NULL DEFAULT 10,
  `zohor` int(11) NOT NULL DEFAULT 10,
  `asar` int(11) NOT NULL DEFAULT 10,
  `maghrib` int(11) NOT NULL DEFAULT 10,
  `isyak` int(11) NOT NULL DEFAULT 10,
  `screen` varchar(20) NOT NULL DEFAULT 'Standard',
  `beep` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`hari`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbm_iqomah` (
  `hari` varchar(20) NOT NULL,
  `subuh` int(11) NOT NULL DEFAULT 10,
  `zohor` int(11) NOT NULL DEFAULT 10,
  `asar` int(11) NOT NULL DEFAULT 10,
  `maghrib` int(11) NOT NULL DEFAULT 10,
  `isyak` int(11) NOT NULL DEFAULT 10,
  PRIMARY KEY (`hari`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbm_solat` (`hari`) VALUES ('AHAD'),('ISNIN'),('SELASA'),('RABU'),('KHAMIS'),('JUMAAT'),('SABTU')
ON DUPLICATE KEY UPDATE `hari`=VALUES(`hari`);
INSERT INTO `tbm_iqomah` (`hari`) VALUES ('AHAD'),('ISNIN'),('SELASA'),('RABU'),('KHAMIS'),('JUMAAT'),('SABTU')
ON DUPLICATE KEY UPDATE `hari`=VALUES(`hari`);

CREATE TABLE IF NOT EXISTS `sleep_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ulang` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `sleep_event` (`id`, `startdate`, `enddate`, `ulang`) VALUES (1, '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0)
ON DUPLICATE KEY UPDATE `id`=`id`;

CREATE TABLE IF NOT EXISTS `taqwim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kodlokasi` int(11) NOT NULL,
  `tarikh` date NOT NULL,
  `imsak` varchar(5) NOT NULL,
  `subuh` varchar(5) NOT NULL,
  `syuruk` varchar(5) NOT NULL,
  `zohor` varchar(5) NOT NULL,
  `asar` varchar(5) NOT NULL,
  `maghrib` varchar(5) NOT NULL,
  `isyak` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_taqwim_lokasi_tarikh` (`kodlokasi`, `tarikh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `template_kuliah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(255) NOT NULL DEFAULT '', `tarikh` date DEFAULT NULL, `tajuk` varchar(255) NOT NULL DEFAULT '',
  `hari` varchar(20) NOT NULL DEFAULT '', `waktu` varchar(50) NOT NULL DEFAULT '', `penceramah` varchar(255) NOT NULL DEFAULT '',
  `tempat` varchar(255) NOT NULL DEFAULT '', `catatan` text, `susunan` int(11) NOT NULL DEFAULT 0, `status` int(11) NOT NULL DEFAULT 1,
  `filetemplate` varchar(255) NOT NULL DEFAULT '', `show_slide` varchar(20) NOT NULL DEFAULT '', `file_slide` varchar(255) NOT NULL DEFAULT '',
  `batal` int(11) NOT NULL DEFAULT 0, `autodelete` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `template_kuliah_tetap` LIKE `template_kuliah`;
ALTER TABLE `template_kuliah_tetap` MODIFY `tarikh` date DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `countdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(255) NOT NULL DEFAULT '',
  `tarikh` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `autohide` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tmp_flag_first` (`id` int(11) NOT NULL AUTO_INCREMENT, `flag` tinyint(1) NOT NULL DEFAULT 0, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `tmp_flag_first` (`id`, `flag`) VALUES (1, 0) ON DUPLICATE KEY UPDATE `flag`=VALUES(`flag`);

CREATE TABLE IF NOT EXISTS `tmp_indexfiles` (`id` int(11) NOT NULL, `folder` varchar(255) NOT NULL, `filename` varchar(255) NOT NULL, `flag` tinyint(1) NOT NULL DEFAULT 0, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `tmp_video_indexfiles` LIKE `tmp_indexfiles`;

CREATE TABLE IF NOT EXISTS `crud_users` (`id` int(11) NOT NULL AUTO_INCREMENT, `firstname` varchar(100) NOT NULL DEFAULT '', `lastname` varchar(100) NOT NULL DEFAULT '', `phone` varchar(50) NOT NULL DEFAULT '', `email` varchar(255) NOT NULL DEFAULT '', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
