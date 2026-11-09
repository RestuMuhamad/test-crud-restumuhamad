-- Database: db_tes_karyawan (MySQL 5.7, InnoDB)
CREATE DATABASE IF NOT EXISTS db_tes_karyawan
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE db_tes_karyawan;

DROP TABLE IF EXISTS cuti;
DROP TABLE IF EXISTS karyawan;
DROP TABLE IF EXISTS departemen;

CREATE TABLE departemen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_departemen VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE karyawan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nik VARCHAR(16) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  gender ENUM('Laki-laki','Perempuan') NOT NULL,
  tanggal_lahir DATE NOT NULL,
  departemen_id INT NOT NULL,
  tanggal_masuk DATE NOT NULL,
  FOREIGN KEY (departemen_id) REFERENCES departemen(id)
) ENGINE=InnoDB;

CREATE TABLE cuti (
  id INT AUTO_INCREMENT PRIMARY KEY,
  karyawan_id INT NOT NULL,
  tanggal_mulai DATE NOT NULL,
  tanggal_selesai DATE NOT NULL,
  lama_cuti INT NOT NULL,
  alasan VARCHAR(255) NOT NULL,
  status ENUM('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  FOREIGN KEY (karyawan_id) REFERENCES karyawan(id)
) ENGINE=InnoDB;
INSERT INTO departemen (nama_departemen) VALUES
  ('Teknologi Informasi'),
  ('Sumber Daya Manusia'),
  ('Keuangan'),
  ('Pemasaran'),
  ('Operasional');

INSERT INTO karyawan (nik, nama, gender, tanggal_lahir, departemen_id, tanggal_masuk) VALUES
  ('3201234567890001', 'Andi Pratama',     'Laki-laki', '1990-05-12', 1, '2015-06-01'),
  ('3201234567890002', 'Budi Santoso',     'Laki-laki', '1988-11-23', 3, '2014-03-17'),
  ('3201234567890003', 'Citra Lestari',    'Perempuan', '1992-02-08', 2, '2017-09-04'),
  ('3201234567890004', 'Dewi Anggraini',   'Perempuan', '1995-07-30', 4, '2019-01-14'),
  ('3201234567890005', 'Eko Wijaya',       'Laki-laki', '1991-12-05', 5, '2016-08-22'),
  ('3201234567890006', 'Fitri Rahmawati',  'Perempuan', '1993-04-18', 1, '2018-11-05');

INSERT INTO cuti (karyawan_id, tanggal_mulai, tanggal_selesai, lama_cuti, alasan, status) VALUES
  (1, '2026-01-05', '2026-01-07', 3, 'Keperluan keluarga', 'Disetujui'),
  (2, '2026-02-10', '2026-02-11', 2, 'Sakit',              'Disetujui'),
  (3, '2026-03-02', '2026-03-02', 1, 'Urusan pribadi',     'Menunggu'),
  (4, '2026-04-15', '2026-04-18', 4, 'Liburan',            'Ditolak'),
  (5, '2026-05-20', '2026-05-22', 3, 'Acara keluarga',     'Disetujui'),
  (6, '2026-06-01', '2026-06-03', 3, 'Keperluan pribadi',  'Menunggu');
