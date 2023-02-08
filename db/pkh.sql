-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Feb 2023 pada 04.59
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pkh`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `view_nilai` (IN `no_kk` VARCHAR(16), IN `tanggal` DATE)  NO SQL
BEGIN

SET @sql = NULL;
  SET @kepala = 'KEPALA KELUARGA';
  SET @no_kk = no_kk;
  SET @tanggal = tanggal;
  
  CREATE TEMPORARY TABLE table_nilai(id_penilaian_detail int(11),kode_penilaian varchar(10),kode_kriteria varchar(10), nama_kriteria_show varchar(100),bobot_nilai_kriteria decimal(10,2));
INSERT into table_nilai (id_penilaian_detail,kode_penilaian,kode_kriteria,nama_kriteria_show,bobot_nilai_kriteria)
SELECT A.id_penilaian_detail,A.kode_penilaian,A.kode_kriteria, REPLACE(B.nama_kriteria,' ','_') AS NAMA,A.bobot_nilai_kriteria
FROM penilaian_detail A 
INNER JOIN kriteria B ON B.kode_kriteria=A.kode_kriteria;

SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'MAX(IF(pa.nama_kriteria_show = ''',
      nama_kriteria_show,
      ''', pa.bobot_nilai_kriteria, NULL)) AS ',
      nama_kriteria_show
    )
  ) INTO @sql
FROM table_nilai;

SET @sql = CONCAT('SELECT p.kode_penilaian\r\n                    , p.no_kk\r\n                    ,p.tgl_penilaian,pc.nama_keluarga', IF(@sql IS NULL, '',CONCAT(',',@sql)),',sum(pa.bobot_nilai_kriteria) as total\r\n                   FROM penilaian p\r\n                   LEFT JOIN table_nilai AS pa \r\n                    ON p.kode_penilaian = pa.kode_penilaian\r\n   \r\nINNER JOIN (select kk.nama_keluarga,kk.no_kk from kk_detail AS kk where KK.status="',@kepala,'") pc on pc.no_kk=p.no_kk\r\n \r\n                  where p.no_kk LIKE "%',@no_kk,'%" AND p.tgl_penilaian LIKE "%',@tanggal,'%" GROUP BY p.kode_penilaian\r\nORDER BY sum(pa.bobot_nilai_kriteria) DESC');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_nilai_akhir` (IN `no_kk` VARCHAR(16), IN `tanggal` DATE)  NO SQL
BEGIN

SET @sql = NULL;
  SET @kepala = 'KEPALA KELUARGA';
  SET @no_kk = no_kk;
  SET @tanggal = tanggal;
  
  CREATE TEMPORARY TABLE table_nilai(id_nilai_akhir int(11),id_penilaian_detail int(11),kode_penilaian varchar(10),kode_kriteria varchar(10), nama_kriteria_show varchar(100),nilai_akhirs decimal(18,4));
INSERT into table_nilai 
(id_nilai_akhir,id_penilaian_detail,kode_penilaian,kode_kriteria,nama_kriteria_show,nilai_akhirs)
SELECT A.id_nilai_akhir,A.id_penilaian_detail,B.kode_penilaian,B.kode_kriteria,REPLACE(C.nama_kriteria,' ','_') AS NAMA_KRITERIA,A.nilai_akhir FROM nilai_akhir A 
INNER JOIN penilaian_detail B ON B.id_penilaian_detail=A.id_penilaian_detail
INNER JOIN kriteria C ON C.kode_kriteria=B.kode_kriteria;

SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'MAX(IF(pa.nama_kriteria_show = ''',
      nama_kriteria_show,
      ''', pa.nilai_akhirs, NULL)) AS ',
      nama_kriteria_show
    )
  ) INTO @sql
FROM table_nilai;

SET @sql = CONCAT('SELECT p.kode_penilaian\r\n                    , p.no_kk\r\n                    ,p.tgl_penilaian,pc.nama_keluarga', IF(@sql IS NULL, '',CONCAT(',',@sql)),',sum(pa.nilai_akhirs) as total\r\n                   FROM penilaian p\r\n                   INNER JOIN table_nilai AS pa \r\n                    ON p.kode_penilaian = pa.kode_penilaian\r\n   \r\nINNER JOIN (select kk.nama_keluarga,kk.no_kk from kk_detail AS kk where KK.status="',@kepala,'") pc on pc.no_kk=p.no_kk\r\n \r\n                  where p.no_kk LIKE "%',@no_kk,'%" and p.tgl_penilaian LIKE "%',@tanggal,'%" GROUP BY p.kode_penilaian\r\nORDER BY sum(pa.nilai_akhirs) DESC');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_nilai_akhir2` (IN `no_kk` VARCHAR(16), IN `tanggal` DATE)  NO SQL
BEGIN

SET @sql = NULL;
  SET @kepala = 'KEPALA KELUARGA';
  SET @no_kk = no_kk;
  SET @tanggal = tanggal;
  
  CREATE TEMPORARY TABLE table_nilai(id_nilai_akhir int(11),id_penilaian_detail int(11),kode_penilaian varchar(10),kode_kriteria varchar(10), nama_kriteria_show varchar(100),nilai_akhirs decimal(18,4),nama_kriteria_detail varchar(100));
INSERT into table_nilai 
(id_nilai_akhir,id_penilaian_detail,kode_penilaian,kode_kriteria,nama_kriteria_show,nilai_akhirs,nama_kriteria_detail)
SELECT A.id_nilai_akhir,A.id_penilaian_detail,B.kode_penilaian,B.kode_kriteria,REPLACE(C.nama_kriteria,' ','_') AS NAMA_KRITERIA,A.nilai_akhir,D.nama_kriteria_detail FROM nilai_akhir A 
INNER JOIN penilaian_detail B ON B.id_penilaian_detail=A.id_penilaian_detail
INNER JOIN kriteria C ON C.kode_kriteria=B.kode_kriteria
INNER JOIN kriteria_detail D ON D.id_kriteria_detail=B.id_kriteria_detail;

SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'MAX(IF(pa.nama_kriteria_show = ''',
      nama_kriteria_show,
      ''', pa.nama_kriteria_detail, NULL)) AS ',
      nama_kriteria_show
    )
  ) INTO @sql
FROM table_nilai;

SET @sql = CONCAT('SELECT p.kode_penilaian\r\n                    , p.no_kk\r\n                    ,p.tgl_penilaian,pc.nama_keluarga', IF(@sql IS NULL, '',CONCAT(',',@sql)),',sum(pa.nilai_akhirs) as total\r\n                   FROM penilaian p\r\n                   INNER JOIN table_nilai AS pa \r\n                    ON p.kode_penilaian = pa.kode_penilaian\r\n   \r\nINNER JOIN (select kk.nama_keluarga,kk.no_kk from kk_detail AS kk where KK.status="',@kepala,'") pc on pc.no_kk=p.no_kk\r\n \r\n                  where p.no_kk LIKE "%',@no_kk,'%" and p.tgl_penilaian LIKE "%',@tanggal,'%" GROUP BY p.kode_penilaian\r\nORDER BY sum(pa.nilai_akhirs) DESC');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kk_detail`
--

CREATE TABLE `kk_detail` (
  `id_kk_detail` int(11) NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama_keluarga` varchar(50) NOT NULL,
  `Tgl_Lahir` date NOT NULL,
  `Pekerjaan` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `user_input` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `kode_kriteria` varchar(10) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot_kriteria` decimal(10,2) NOT NULL,
  `user_input` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`kode_kriteria`, `nama_kriteria`, `bobot_kriteria`, `user_input`) VALUES
('KK1907001', 'PENDAPATAN', '30.00', '1'),
('KK1907003', 'PEKERJAAN KEPALA KELUARGA', '15.00', '1'),
('KK1907004', 'KEPEMILIKAN RUMAH', '15.00', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria_detail`
--

CREATE TABLE `kriteria_detail` (
  `id_kriteria_detail` int(11) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `nama_kriteria_detail` varchar(100) NOT NULL,
  `nilai` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kriteria_detail`
--

INSERT INTO `kriteria_detail` (`id_kriteria_detail`, `kode_kriteria`, `nama_kriteria_detail`, `nilai`) VALUES
(21, 'KK1907001', '< 1000.000', '100.00'),
(22, 'KK1907001', '1000.000 - 3000.000', '75.00'),
(23, 'KK1907001', '3000.000 - 5000.000', '50.00'),
(24, 'KK1907001', '> 5000.000', '0.00'),
(29, 'KK1907003', 'KARYAWAN', '30.00'),
(30, 'KK1907003', 'WIRASWASTA', '50.00'),
(31, 'KK1907003', 'PETANI', '75.00'),
(32, 'KK1907003', 'TIDAK BEKERJA', '100.00'),
(33, 'KK1907004', 'RUMAH PRIBADI', '25.00'),
(34, 'KK1907004', 'RUMAH NGONTRAK', '75.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria_keluarga`
--

CREATE TABLE `kriteria_keluarga` (
  `id_kriteria_keluarga` int(11) NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `id_kriteria_detail` int(11) NOT NULL,
  `user_input` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_akhir`
--

CREATE TABLE `nilai_akhir` (
  `id_nilai_akhir` int(11) NOT NULL,
  `id_penilaian_detail` int(11) NOT NULL,
  `nilai_akhir` decimal(10,4) NOT NULL,
  `user_input` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `nilai_akhir`
--

INSERT INTO `nilai_akhir` (`id_nilai_akhir`, `id_penilaian_detail`, `nilai_akhir`, `user_input`) VALUES
(74, 39, '0.2250', 'US1910021'),
(75, 42, '0.3000', 'US1910021'),
(76, 40, '0.1500', 'US1910021'),
(77, 43, '0.1125', 'US1910021'),
(78, 41, '0.1500', 'US1910021'),
(79, 44, '0.1500', 'US1910021'),
(90, 45, '0.2250', 'US2005022'),
(91, 46, '0.1500', 'US2005022'),
(92, 47, '0.1500', 'US2005022'),
(93, 48, '0.3000', 'US2005022'),
(94, 49, '0.1125', 'US2005022'),
(95, 50, '0.1500', 'US2005022');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `kode_penilaian` varchar(10) NOT NULL,
  `no_kk` varchar(16) NOT NULL,
  `tgl_penilaian` date NOT NULL,
  `user_input` varchar(10) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`kode_penilaian`, `no_kk`, `tgl_penilaian`, `user_input`, `status`) VALUES
('KN1910001', '3603051601111785', '2019-01-01', 'US1910020', 1),
('KN1910002', '9876543211234567', '2019-01-01', 'US1910020', 1),
('KN2005003', '3603051601111785', '2020-01-01', 'US1910020', 1),
('KN2005004', '9876543211234567', '2020-01-01', 'US1910020', 1),
('KN2005005', '', '2020-01-01', 'US1910020', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian_detail`
--

CREATE TABLE `penilaian_detail` (
  `id_penilaian_detail` int(11) NOT NULL,
  `kode_penilaian` varchar(10) NOT NULL,
  `id_kriteria_detail` int(11) NOT NULL,
  `nama_kriteria_show` varchar(200) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `bobot_nilai_kriteria` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penilaian_detail`
--

INSERT INTO `penilaian_detail` (`id_penilaian_detail`, `kode_penilaian`, `id_kriteria_detail`, `nama_kriteria_show`, `kode_kriteria`, `bobot_nilai_kriteria`) VALUES
(39, 'KN1910001', 22, 'PENDAPATAN', 'KK1907001', '22.50'),
(40, 'KN1910001', 32, 'PEKERJAAN_KEPALA_KELUARGA', 'KK1907003', '15.00'),
(41, 'KN1910001', 33, 'KEPEMILIKAN_RUMAH', 'KK1907004', '3.75'),
(42, 'KN1910002', 21, 'PENDAPATAN', 'KK1907001', '30.00'),
(43, 'KN1910002', 31, 'PEKERJAAN_KEPALA_KELUARGA', 'KK1907003', '11.25'),
(44, 'KN1910002', 33, 'KEPEMILIKAN_RUMAH', 'KK1907004', '3.75'),
(45, 'KN2005003', 22, 'PENDAPATAN', 'KK1907001', '22.50'),
(46, 'KN2005003', 32, 'PEKERJAAN_KEPALA_KELUARGA', 'KK1907003', '15.00'),
(47, 'KN2005003', 33, 'KEPEMILIKAN_RUMAH', 'KK1907004', '3.75'),
(48, 'KN2005004', 21, 'PENDAPATAN', 'KK1907001', '30.00'),
(49, 'KN2005004', 31, 'PEKERJAAN_KEPALA_KELUARGA', 'KK1907003', '11.25'),
(50, 'KN2005004', 33, 'KEPEMILIKAN_RUMAH', 'KK1907004', '3.75'),
(51, 'KN2005005', 21, 'PENDAPATAN', 'KK1907001', '30.00'),
(52, 'KN2005005', 31, 'PEKERJAAN_KEPALA_KELUARGA', 'KK1907003', '11.25'),
(53, 'KN2005005', 33, 'KEPEMILIKAN_RUMAH', 'KK1907004', '3.75');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbuser`
--

CREATE TABLE `tbuser` (
  `kode_user` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbuser`
--

INSERT INTO `tbuser` (`kode_user`, `username`, `password`, `level`) VALUES
('', 'admin', 'aaa', 'admin'),
('US1910018', 'Sri Repiyanti Sari', '827ccb0eea8a706c4c34a16891f84e7b', 'ADMIN'),
('US1910019', 'Hesti', 'e10adc3949ba59abbe56e057f20f883e', 'ADMIN'),
('US1910020', 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'ADMIN'),
('US1910021', 'Hj Uum Umyanah', '827ccb0eea8a706c4c34a16891f84e7b', 'KADES'),
('US2005022', 'kades', '827ccb0eea8a706c4c34a16891f84e7b', 'KADES');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kk`
--

CREATE TABLE `tb_kk` (
  `no_kk` varchar(16) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `user_input` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  `qty` decimal(18,2) NOT NULL,
  `qty_set` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `test`
--

INSERT INTO `test` (`id`, `nomor`, `qty`, `qty_set`) VALUES
(1, 1, '2.00', '0.00'),
(2, 2, '4.00', '0.00'),
(3, 4, '1.00', '0.00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kk_detail`
--
ALTER TABLE `kk_detail`
  ADD PRIMARY KEY (`id_kk_detail`),
  ADD KEY `no_kk` (`no_kk`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`kode_kriteria`);

--
-- Indeks untuk tabel `kriteria_detail`
--
ALTER TABLE `kriteria_detail`
  ADD PRIMARY KEY (`id_kriteria_detail`),
  ADD KEY `kode_kriteria` (`kode_kriteria`);

--
-- Indeks untuk tabel `kriteria_keluarga`
--
ALTER TABLE `kriteria_keluarga`
  ADD PRIMARY KEY (`id_kriteria_keluarga`),
  ADD KEY `no_kk` (`no_kk`),
  ADD KEY `id_kriteria_detail` (`id_kriteria_detail`),
  ADD KEY `user_input` (`user_input`);

--
-- Indeks untuk tabel `nilai_akhir`
--
ALTER TABLE `nilai_akhir`
  ADD PRIMARY KEY (`id_nilai_akhir`),
  ADD KEY `id_penilaian_detail` (`id_penilaian_detail`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`kode_penilaian`),
  ADD KEY `no_kk` (`no_kk`);

--
-- Indeks untuk tabel `penilaian_detail`
--
ALTER TABLE `penilaian_detail`
  ADD PRIMARY KEY (`id_penilaian_detail`),
  ADD KEY `kode_penilaian` (`kode_penilaian`),
  ADD KEY `kode_kriteria` (`kode_kriteria`);

--
-- Indeks untuk tabel `tbuser`
--
ALTER TABLE `tbuser`
  ADD PRIMARY KEY (`kode_user`);

--
-- Indeks untuk tabel `tb_kk`
--
ALTER TABLE `tb_kk`
  ADD PRIMARY KEY (`no_kk`);

--
-- Indeks untuk tabel `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kk_detail`
--
ALTER TABLE `kk_detail`
  MODIFY `id_kk_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `kriteria_detail`
--
ALTER TABLE `kriteria_detail`
  MODIFY `id_kriteria_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `kriteria_keluarga`
--
ALTER TABLE `kriteria_keluarga`
  MODIFY `id_kriteria_keluarga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `nilai_akhir`
--
ALTER TABLE `nilai_akhir`
  MODIFY `id_nilai_akhir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT untuk tabel `penilaian_detail`
--
ALTER TABLE `penilaian_detail`
  MODIFY `id_penilaian_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kk_detail`
--
ALTER TABLE `kk_detail`
  ADD CONSTRAINT `kk_detail_ibfk_1` FOREIGN KEY (`no_kk`) REFERENCES `tb_kk` (`no_kk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kriteria_detail`
--
ALTER TABLE `kriteria_detail`
  ADD CONSTRAINT `kriteria_detail_ibfk_1` FOREIGN KEY (`kode_kriteria`) REFERENCES `kriteria` (`kode_kriteria`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kriteria_keluarga`
--
ALTER TABLE `kriteria_keluarga`
  ADD CONSTRAINT `kriteria_keluarga_ibfk_1` FOREIGN KEY (`no_kk`) REFERENCES `kk_detail` (`no_kk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai_akhir`
--
ALTER TABLE `nilai_akhir`
  ADD CONSTRAINT `nilai_akhir_ibfk_1` FOREIGN KEY (`id_penilaian_detail`) REFERENCES `penilaian_detail` (`id_penilaian_detail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penilaian_detail`
--
ALTER TABLE `penilaian_detail`
  ADD CONSTRAINT `penilaian_detail_ibfk_1` FOREIGN KEY (`kode_penilaian`) REFERENCES `penilaian` (`kode_penilaian`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
