-- ========================================================
-- DATABASE: db_dss (dengan trigger otomatis hapus evaluasi)
-- ========================================================

DROP DATABASE IF EXISTS db_dss;
CREATE DATABASE db_dss;
USE db_dss;

-- ===========================
-- TABLE: saw_alternatives
-- ===========================
CREATE TABLE IF NOT EXISTS `saw_alternatives` (
  `id_alternative` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_alternative`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO `saw_alternatives` (`id_alternative`, `name`) VALUES
	(1, 'PT Alpha Tech'),
	(2, 'PT Beta Solusindo'),
	(3, 'PT Gamma Digital'),
	(4, 'PT Delta Corp'),
	(5, 'PT Epsilon Media'),
	(6, 'PT Zeta Systems'),
	(7, 'PT Theta Creative'),
	(8, 'PT Omega Labs');

-- ===========================
-- TABLE: saw_criterias
-- ===========================
CREATE TABLE IF NOT EXISTS `saw_criterias` (
  `id_criteria` TINYINT(3) UNSIGNED NOT NULL,
  `criteria` VARCHAR(100) NOT NULL,
  `weight` FLOAT NOT NULL,
  `attribute` ENUM('benefit','cost') DEFAULT NULL,
  PRIMARY KEY (`id_criteria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `saw_criterias` (`id_criteria`, `criteria`, `weight`, `attribute`) VALUES
	(1, 'Kualitas Produk', 2.5, 'benefit'),
	(2, 'Pelayanan Pelanggan', 2.8, 'benefit'),
	(3, 'Inovasi Teknologi', 1.5, 'benefit'),
	(4, 'Harga Produk', 2.0, 'cost'),
	(5, 'Waktu Pengiriman', 2.8, 'cost');

-- ===========================
-- TABLE: saw_evaluations
-- ===========================
CREATE TABLE IF NOT EXISTS `saw_evaluations` (
  `id_alternative` SMALLINT(5) UNSIGNED NOT NULL,
  `id_criteria` TINYINT(3) UNSIGNED NOT NULL,
  `value` FLOAT NOT NULL CHECK (`value` >= 0 AND `value` <= 5),
  PRIMARY KEY (`id_alternative`, `id_criteria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `saw_evaluations` (`id_alternative`, `id_criteria`, `value`) VALUES
	(1, 1, 5),
	(1, 2, 4),
	(1, 3, 5),
	(1, 4, 3),
	(1, 5, 2),
	(2, 1, 4),
	(2, 2, 3),
	(2, 3, 4),
	(2, 4, 2),
	(2, 5, 3),
	(3, 1, 3),
	(3, 2, 4),
	(3, 3, 3),
	(3, 4, 4),
	(3, 5, 4),
	(4, 1, 4),
	(4, 2, 2),
	(4, 3, 5),
	(4, 4, 3),
	(4, 5, 2),
	(5, 1, 5),
	(5, 2, 5),
	(5, 3, 4),
	(5, 4, 2),
	(5, 5, 3),
	(6, 1, 3),
	(6, 2, 3),
	(6, 3, 4),
	(6, 4, 5),
	(6, 5, 4),
	(7, 1, 4),
	(7, 2, 4),
	(7, 3, 3),
	(7, 4, 4),
	(7, 5, 3),
	(8, 1, 5),
	(8, 2, 3),
	(8, 3, 4),
	(8, 4, 3),
	(8, 5, 2);

-- ===========================
-- TABLE: saw_users
-- ===========================
CREATE TABLE IF NOT EXISTS `saw_users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) DEFAULT NULL,
  `password` VARCHAR(150) DEFAULT NULL,
  `role` ENUM('admin','alternatif') DEFAULT 'alternatif',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `saw_users` (`username`, `password`, `role`) VALUES
('admin', MD5('admin'), 'admin'),
('alternatif', MD5('12345'), 'alternatif');

-- ======================================================
-- TRIGGER: otomatis hapus data evaluasi jika alternatif dihapus
-- ======================================================

DELIMITER $$

CREATE TRIGGER `hapus_evaluasi_otomatis`
AFTER DELETE ON `saw_alternatives`
FOR EACH ROW
BEGIN
  DELETE FROM `saw_evaluations` WHERE `id_alternative` = OLD.id_alternative;
END $$

DELIMITER ;

-- ===========================
-- END OF FILE
-- ===========================
