-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 09, 2015 at 05:48 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `isit_v3-duhovka`
--

-- --------------------------------------------------------

--
-- Table structure for table `backup_schedules`
--

DROP TABLE IF EXISTS `backup_schedules`;
CREATE TABLE IF NOT EXISTS `backup_schedules` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `computer_id` int(5) NOT NULL,
  `nas_id` int(5) NOT NULL,
  `_day` int(2) NOT NULL,
  `_hour` int(2) NOT NULL,
  `_min` int(2) NOT NULL,
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `device_id` int(5) NOT NULL,
  `device_folder` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=777 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`dbid`, `id`, `device_id`, `device_folder`, `poznamka`, `aktivni`) VALUES
(635, 2, 0, '_printers', 'superhnustná tiskárna', 1),
(636, 3, 1, '_computers', 'it-ucebna 3NP, dvi-lcd', 1),
(637, 4, 2, '_computers', '3NP	it-ucebna	dvi-lcd', 1),
(638, 5, 3, '_computers', '3NP	it-ucebna', 1),
(639, 6, 4, '_computers', '3NP	it-ucebna', 1),
(640, 7, 5, '_computers', '3NP	it-ucebna', 1),
(641, 8, 6, '_computers', '3NP	it-ucebna', 1),
(642, 9, 7, '_computers', '3NP	it-ucebna	dvi-lcd', 1),
(643, 10, 8, '_computers', '3NP	it-ucebna	dvi-lcd', 1),
(644, 11, 9, '_computers', '3NP	it-ucebna', 1),
(645, 12, 10, '_computers', '3NP	it-ucebna', 1),
(646, 13, 11, '_computers', '3NP	it-ucebna', 1),
(647, 14, 12, '_computers', '3NP	it-ucebna', 1),
(648, 15, 13, '_computers', '3NP	it-ucebna	dvi-lcd', 1),
(649, 16, 14, '_computers', '3NP	it-ucebna	dvi-lcd', 1),
(650, 17, 15, '_computers', '3NP	it-ucebna', 1),
(651, 18, 16, '_computers', '3NP	it-ucebna', 1),
(652, 19, 17, '_computers', '3NP	it-ucebna', 1),
(653, 20, 18, '_computers', '3NP	it-ucebna', 1),
(654, 21, 19, '_computers', '2NP 	serverovna	rezerva', 1),
(655, 22, 19, '_computers', 'mozna prejmenovano na duhgymdohled', 1),
(657, 24, 21, '_computers', '2NP	sborovna	ZTe', 1),
(658, 25, 22, '_computers', '2NP	kabinet		EKu', 1),
(659, 26, 23, '_computers', '3NP	sborovna	asistenti', 1),
(660, 27, 24, '_computers', '3NP	sborovna	LNe', 1),
(661, 28, 25, '_computers', '3NP	sborovna	MKr', 1),
(662, 29, 26, '_computers', '3NP	sborovna	OKp', 1),
(663, 30, 27, '_computers', '3NP	sborovna	HBe', 1),
(665, 32, 28, '_computers', '2NP	sborovna	ARa', 1),
(666, 33, 29, '_computers', '2NP	sborovna	HDr_erarani', 1),
(667, 34, 30, '_computers', '1NP	3A', 1),
(668, 35, 31, '_computers', '1NP	3B', 1),
(669, 36, 32, '_computers', '2015-03-13 prenesen nan chodbu; pripraven por deti; nutne doinstalovat skener', 1),
(670, 37, 32, '_computers', '1NP	- chodba', 1),
(671, 38, 33, '_computers', '1NP	1B', 1),
(672, 39, 34, '_computers', '1NP	2A', 1),
(673, 40, 34, '_computers', '! dve cisla v teamveiewer 796922366	898875063 ', 1),
(674, 41, 35, '_computers', '1NP	1A', 1),
(675, 42, 36, '_computers', '1NP	jazyk', 1),
(676, 43, 36, '_computers', '! dve cisla teamviewer 898875063 796922366', 1),
(677, 44, 37, '_computers', '1NP 3A', 1),
(678, 45, 37, '_computers', 'mac: b8 6b 23 4d 6f 84', 1),
(679, 46, 37, '_computers', 'wifi: 34 de 1a 2d fe d3', 1),
(680, 47, 38, '_computers', '1NP 2B', 1),
(681, 48, 38, '_computers', 'mac: b8 6b 23 d1 6c 84', 1),
(682, 49, 38, '_computers', 'wifi: 34 de 1a 2d 93 71', 1),
(683, 50, 39, '_computers', '1NP 3B', 1),
(684, 51, 39, '_computers', 'mac: b8 6b 23 a2 6d 84', 1),
(685, 52, 39, '_computers', 'wifi: 24 de 1a 2b 69 cf', 1),
(686, 53, 40, '_computers', '1NP 2A', 1),
(687, 54, 40, '_computers', 'mac: b8 6b 23 22 6d 84', 1),
(688, 55, 40, '_computers', 'wifi: 34 de 1a 2d 98 1c', 1),
(689, 56, 41, '_computers', '1NP 1A', 1),
(690, 57, 41, '_computers', 'mac: b8 6b 23 26 6d 84', 1),
(691, 58, 41, '_computers', 'wifi: 34 de 1a 2d 85 4d', 1),
(692, 59, 42, '_computers', '1NP jazykova ucebna', 1),
(693, 60, 42, '_computers', 'mac: b8 6b 23 52 6d 84', 1),
(694, 61, 42, '_computers', 'wifi: 34 de 1a 2d 97 d6', 1),
(695, 62, 43, '_computers', '1NP 1B', 1),
(696, 63, 43, '_computers', 'mac: b8 6b 23 e0 6c 84', 1),
(697, 64, 43, '_computers', 'wifi: 34 de 1a 2d 85 66', 1),
(698, 65, 8, '_computers', '! kolize inv, nebo ser cisel s duhgym01', 1),
(699, 66, 44, '_computers', '3NP	it-ucebna	ucitel_pc	dvi-lcd', 1),
(700, 67, 44, '_computers', '! kolize inv, nebo ser cisel s duhgym09', 1),
(701, 68, 45, '_computers', '2NP	serverovna	rezerva	win7	DL22', 1),
(702, 69, 46, '_computers', '2NP	serverovna	rezerva	win7', 1),
(703, 70, 47, '_computers', '2NP	serverovna	rezerva	win7	kopie win neni spravna', 1),
(704, 71, 48, '_computers', '2NP	serverovna	rezerva	win7', 1),
(705, 72, 48, '_computers', '! neni teamviewer cislo', 1),
(706, 73, 49, '_computers', '2NP	serverovna	rezerva	win7	mrtva baterka', 1),
(707, 74, 50, '_computers', '2NP	serverovna	rezerva	win7', 1),
(708, 75, 50, '_computers', '! neni teamviewer cislo', 1),
(709, 76, 51, '_computers', '2NP	serverovna	rezerva	win7', 1),
(710, 77, 52, '_computers', '2NP	serverovna	rezerva	win7	problem se startem win asi driver ati', 1),
(711, 78, 52, '_computers', '! neni teamviewer cislo', 1),
(712, 79, 53, '_computers', '2NP	serverovna	rezerva	win7', 1),
(713, 80, 53, '_computers', '! neni teamviewer cislo', 1),
(714, 81, 54, '_computers', '2015-03-12 predano sekretariat gympl (+ ext. cd/dvd)', 1),
(715, 82, 54, '_computers', '! vyradit, nebo preinstalovat na lubuntu pro PC ucebnu', 1),
(716, 83, 55, '_computers', '2015-03-12 predano sekretariat gympl', 1),
(717, 84, 55, '_computers', '! vyradit, nebo reinstalovat na lubuntu pro IT ucebnu', 1),
(718, 85, 1, '_printers', '2NP	serverovna	puvodne_2NP_chodba', 1),
(719, 86, 2, '_printers', '2NP	serverovna	puvodne_2NP_sborovna', 1),
(720, 87, 3, '_printers', '3NP	sborovna	puvodne_3NP_chodba', 1),
(721, 88, 4, '_printers', '2NP	serverovna	rezerva			tisk06', 1),
(722, 89, 5, '_printers', '2NP	serverovna	rezerva			tisk05', 1),
(723, 90, 6, '_printers', '2NP	serverovna	rezerva', 1),
(724, 91, 7, '_printers', '2NP	serverovna	rezerva', 1),
(725, 92, 8, '_printers', '2NP	serverovna	rezerva', 1),
(726, 93, 9, '_printers', '2NP	serverovna	rezerva', 1),
(727, 94, 2, '_printers', 'LAN', 1),
(729, 95, 1, '_printers', 'mac(lan): 	b4 b5 2f f2 36 f3', 1),
(730, 96, 1, '_printers', 'mac(wifi):	e0 06 e6 19 f2 f6', 1),
(731, 97, 1, '_printers', '1NP - chodba - wifi', 1),
(732, 98, 2, '_printers', '1NP - kabinet', 1),
(733, 99, 10, '_printers', '2NP - sekretariat - LAN', 1),
(734, 100, 10, '_printers', 'mac(lan): 	24 be 05 ee f3 5b', 1),
(735, 101, 10, '_printers', 'mac(wifi):	c0 18 85 92 f8 2d', 1),
(736, 102, 10, '_printers', 'email:			292adye68ufbl@hpeprint.com', 1),
(738, 104, 11, '_printers', 'mac(lan): 	2c 27 d7 10 4f 15', 1),
(739, 105, 11, '_printers', 'mac(wifi):	c0 f8 da 33 53 51', 1),
(740, 106, 11, '_printers', '3NP - chodba - WIFI', 1),
(741, 107, 12, '_printers', '2NP - sborovna - LAN', 1),
(744, 108, 28, '_computers', 'puvodne Bol-Duhovka30 K5', 1),
(745, 109, 51, '_computers', '2015-03-25 presunuto do sekretariat', 1),
(746, 110, 51, '_computers', 'puovdni nazev dl-duhovka19', 1),
(747, 111, 13, '_printers', 'Group 1NP - sklenik', 1),
(748, 112, 14, '_printers', 'Group 1NP - kancelar honza', 1),
(749, 113, 14, '_printers', 'georgina', 1),
(750, 114, 34, '_computers', '2015-03-30 vony pro presun na chodbu', 1),
(751, 115, 33, '_computers', '2015-03-30 vony pro presun na chodbu', 1),
(752, 116, 35, '_computers', '2015-03-30 vony pro presun na chodbu', 1),
(753, 117, 30, '_computers', '2015-03-30 vony pro presun na chodbu', 1),
(754, 118, 56, '_computers', '3NP 5A - rohova trida', 1),
(756, 120, 20, '_computers', '2NP	sborovna', 1),
(757, 121, 57, '_computers', 'Gympl 3NP 4A', 1),
(758, 122, 58, '_computers', 'Gympl 3NP 4B', 1),
(759, 123, 59, '_computers', 'Gympl 3NP 3A', 1),
(760, 124, 60, '_computers', '! neni teamviewer cislo', 1),
(761, 125, 60, '_computers', 'Gympl 3NP 6A', 1),
(762, 126, 61, '_computers', 'Gympl 3NP jazykovka', 1),
(763, 127, 62, '_computers', 'Gympl 3NP 7A', 1),
(764, 128, 35, '_computers', '2015-04-13 presunut na chodbu', 1),
(765, 129, 35, '_computers', 'odisntalovano: glboard, google drive, geogebra 4.4, one drive, whiteboard callibration 3.3', 1),
(766, 130, 35, '_computers', '! nainstalovany coreldraw x5 - odebrat ?', 1),
(767, 131, 34, '_computers', '2015-04-13 presunut na chodbu 1NP, overeno tmcislo 796922366', 1),
(770, 134, 30, '_computers', '2015-04-13 presun do sborovna 2NP - pripraven jako erar', 1),
(771, 135, 33, '_computers', '2015-04-13 presun do sborovna 2NP - pripraven jako erar', 1),
(772, 136, 45, '_computers', '2015-04-13 presun do 3NP sborovna, ucitelka spanelstiny, zapojen, pripraven', 1),
(773, 137, 63, '_computers', 'Gympl barbora.glaserova', 1),
(774, 138, 63, '_computers', 'drivers: http://www.asus.com/sg/Notebooks_Ultrabooks/ASUS_ZENBOOK_UX303LN/HelpDesk_Download/', 1),
(775, 139, 46, '_computers', '2015-04-22 presunut na chodbu 1NP', 1),
(776, 140, 46, '_computers', 'odinstalovat? corelDraw X5, glary utitlities 2.48,  ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

DROP TABLE IF EXISTS `computers`;
CREATE TABLE IF NOT EXISTS `computers` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `seriove_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `evidencni_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `model` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `datum_porizeni` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  `pc_name` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `teamviewer` int(12) NOT NULL,
  `location` int(11) NOT NULL,
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `seriove_cislo` (`seriove_cislo`,`evidencni_cislo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=382 ;

--
-- Dumping data for table `computers`
--

INSERT INTO `computers` (`dbid`, `id`, `seriove_cislo`, `evidencni_cislo`, `model`, `datum_porizeni`, `aktivni`, `pc_name`, `teamviewer`, `location`) VALUES
(314, 0, '0', '0', 'toshiba', '0', 1, '', 0, 0),
(315, 1, 'GD000795', 'GD000795', 'msi_de500', '0', 1, 'duhgym02', 769542692, 0),
(316, 2, 'GD000796', 'GD000796', 'msi_de500', '0', 1, 'duhgym03', 769569950, 0),
(317, 3, 'GD000803', 'GD000803', 'msi_de500', '0', 1, 'duhgym04', 752061501, 0),
(318, 4, 'GD000793', 'GD000793', 'msi_de500', '0', 1, 'duhgym05', 248683875, 0),
(319, 5, 'GD000800', 'GD000800', 'msi_de500', '0', 1, 'duhgym06', 752329860, 0),
(320, 6, 'GD000801', 'GD000801', 'msi_de500', '0', 1, 'duhgym07', 752270885, 0),
(321, 7, 'GD000802', 'GD000802', 'msi_de500', '0', 1, 'duhgym08', 764892158, 0),
(322, 8, 'GD000799', 'GD000799', 'msi_de500', '0', 1, 'duhgym09', 764476666, 0),
(323, 9, 'GD000794', 'GD000794', 'msi_de500', '0', 1, 'duhgym10', 764652541, 0),
(324, 10, 'GD000790', 'GD000790', 'msi_de500', '0', 1, 'duhgym11', 764775622, 0),
(325, 11, 'GD000792', 'GD000792', 'msi_de500', '0', 1, 'duhgym12', 765087738, 0),
(326, 12, 'GD000178', 'GD000178', 'msi_de500', '0', 1, 'duhgym13', 765132566, 0),
(327, 13, 'GD000789', 'GD000789', 'msi_de500', '0', 1, 'duhgym14', 765253456, 0),
(328, 14, 'GD000791', 'GD000791', 'msi_de500', '0', 1, 'duhgym15', 765337421, 0),
(329, 15, 'GD000186', 'GD000186', 'msi_de500', '0', 1, 'duhgym16', 769506747, 0),
(330, 16, 'GD000798', 'GD000798', 'msi_de500', '0', 1, 'duhgym17', 765623042, 0),
(331, 17, 'GD000177', 'GD000177', 'msi_de500', '0', 1, 'duhgym18', 765517657, 0),
(332, 18, 'GD000185', 'GD000185', 'msi_de500', '0', 1, 'duhgym19', 765562177, 0),
(336, 19, 'GD000727', 'GD000727', 'noname_twr', '0', 1, 'bol-duhovka19', 0, 0),
(337, 20, 'GD001025', 'GD001025', 'noname_twr', '0', 1, 'duhgym28', 806637852, 0),
(338, 21, 'GD001083', 'GD001083', 'noname_twr', '0', 1, 'duhgym27', 661193084, 0),
(339, 22, 'GD000927', 'GD000927', 'hp_dc7700', '0', 1, 'duhgym52', 920536523, 0),
(340, 23, 'GD000004', 'GD000004', 'hp_dc7700', '0', 1, 'duhgym53', 183754941, 0),
(341, 24, 'GD000264', 'GD000264', 'dell_gx620_twr', '0', 1, 'duhgym26', 777520155, 0),
(342, 25, 'GD000271', 'GD000271', 'msi_de500', '0', 1, 'duhgym24', 293003364, 0),
(343, 26, 'GD000270', 'GD000270', 'msi_de500', '0', 1, 'duhgym23', 292997295, 0),
(344, 27, 'GD000263', 'GD000263', 'dell_745_twr', '0', 1, 'duhgym32', 792031127, 0),
(345, 28, 'GD000723', 'gd000723', 'hp_dc7700', '0', 1, 'duhgym30', 615405586, 0),
(346, 29, 'GD000731', 'GD000731', 'hp_dc7700', '0', 1, 'duhgym29', 615325814, 0),
(347, 30, 'GD000191', 'GD000191', 'msi_ms-6676', '0', 1, 'duhgym35', 774080652, 0),
(348, 31, 'GD000045', 'GD000045', 'hp_dc7800', '0', 1, 'duhgym42', 271747048, 0),
(349, 32, 'GD000984', 'GD000984', 'hp_dc7800', '0', 1, 'duhgym41', 267732996, 0),
(350, 33, 'GD000192', 'GD000192', 'msi_ms-6676', '0', 1, 'duhgym37', 774418710, 0),
(351, 34, 'GD000990', 'gd000990', 'msi_ms-6676', '0', 1, 'duhgym31', 796922366, 0),
(352, 35, 'GD000722', 'GD000722', 'msi_ms-6676', '0', 1, 'duhgym38', 774449685, 0),
(353, 36, 'GD000888', 'GD000888', 'msi_ms-6676', '0', 1, 'duhgym31', 0, 0),
(354, 37, 'XE084280H', 'xe084280h', 'toshiba satillite pro r50-B-11c', '2015-03-01', 1, 'duhgymntb01', 626544318, 0),
(355, 38, 'xe084200h', '02', 'toshiba satillite pro r50-B-11c', '2015-03-01', 1, 'duhgymntb02', 626635521, 0),
(356, 39, 'xe084226h', '03', 'toshiba satillite pro r50-B-11c', '0', 1, 'duhgymntb03', 626562875, 0),
(357, 40, 'xe084210h', '04', 'toshiba satillite pro r50-B-11c', '2015-03-01', 1, 'duhgymntb04', 626601277, 0),
(358, 41, 'xe084211h', '05', 'toshiba satillite pro r50-B-11c', '2015-03-01', 1, 'duhgymntb05', 626591162, 0),
(359, 42, 'xe084216h', '06', 'toshiba satillite pro r50-B-11c', '2015-03-01', 1, 'duhgymntb06', 626577421, 0),
(360, 43, 'xe084202h', '07', 'toshiba satillite pro r50-B-11c', '2015-03-01', 1, 'duhgymntb07', 626622011, 0),
(362, 44, 'GD000799-2', 'GD000799-2', 'msi_de500', '0', 1, 'duhgym01', 683025962, 0),
(363, 45, 'GD000006', 'GD000006', 'hp_dc7700', '0', 1, 'dl-duhovka22', 183740451, 0),
(364, 46, 'GD000012', 'GD000012', 'hp_dc7700', '0', 1, 'dl-duhovka20', 870597354, 0),
(365, 47, 'GD000005', 'GD000005', 'hp_dc7700', '0', 1, 'dl-duhovka23', 183746982, 0),
(366, 48, 'GD000003', 'GD000003', 'hp_dc7700', '0', 1, 'dl-duhovka24', 0, 0),
(367, 49, 'GD000002', 'GD000002', 'hp_dc7700', '0', 1, 'dl-duhovka21', 188258313, 0),
(368, 50, 'GD000926', 'GD000926', 'hp_dc7700', '0', 1, 'bol-duhovka27', 0, 0),
(369, 51, 'GD000013', 'gd000013', 'hp_dc7700', '0', 1, 'DuhGym55', 496257557, 0),
(370, 52, 'GD000925', 'GD000925', 'hp_dc7700', '0', 1, 'bol-duhovka29', 0, 0),
(371, 53, 'GD000923', 'GD000923', 'hp_dc7700', '0', 1, 'bol-duhovka32', 0, 0),
(372, 54, '951064j', 'GD001084', 'dell-latitude-d430', '0', 1, 'ntb-duhovka19', 0, 0),
(373, 55, 'ft2hj3j', 'ft2hj3j', 'dell-latitude-d430', '0', 1, 'ntb-duhovka20', 0, 0),
(374, 56, 'GD000982', 'gd000982', 'hp_dc7800', '0', 1, 'duhgym40', 268194268, 0),
(375, 57, 'gd000190', 'gd000190', 'msi_de500', '0', 1, 'duhgym36', 774143981, 0),
(376, 58, 'dg000189', 'dg000189', 'msi_de500', '0', 1, 'duhgym34', 774160459, 0),
(377, 59, 'gd000884', 'gd000884', 'msi_de500', '0', 1, 'duhgym33', 779119861, 0),
(378, 60, 'gd000048', 'gd000048', 'hp_dc7800', '0', 1, 'duhgym49', 0, 0),
(379, 61, 'gd000046', 'gd000046', 'hp_dc7800', '0', 1, 'duhgym44', 271740153, 0),
(380, 62, 'gd000983', 'gd000983', 'hp_dc7800', '0', 1, 'duhgym39', 267949639, 0),
(381, 63, 'F2N0CJ018265067', 'duhgymntb08', 'asus zenbook UX303L', '30.03.2015', 1, 'duhgymntb08', 237450087, 0);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `nadpis` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazit_od` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `zobrazit_do` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `nadpis` (`nadpis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=49 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`dbid`, `id`, `nadpis`, `text`, `zobrazit_od`, `zobrazit_do`, `aktivni`) VALUES
(44, 1, '2015-03-24 soukromy notebook do VLAN2', '              - protoze nejde nastavit kerio ip z vlan3 do vlan1 na tiskarny, docasne pusten jeden notebook do vlan2 (mazlik)\r\n\r\n- ip 10.0.112.65              \r\n            \r\n            ', '1427151600', '1461448800', 1),
(45, 2, 'Jaroslav Dostal     734646595', '<br>Jaroslav Dostal  734646595       ', '1427410800', '1461708000', 1),
(46, 3, 'adela haluzova pc', '470 441 700 koclduhskolka03              \r\n            ', '1427666400', '1461967200', 1),
(47, 4, 'NTBDUHOGROUP05699395412galuskova', '\r\n            ', '1428962400', '1463263200', 1),
(48, 5, 'Gympl net provider', 'gymnasiumduhovka db7_3j<br>\r\n211 151 151<br>\r\nwia              \r\n            ', '1429135200', '1431813600', 1);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `addr` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `popis` text COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  `_name` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `addr` (`addr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`dbid`, `id`, `addr`, `popis`, `aktivni`, `_name`) VALUES
(16, 1, 'https://10.0.111.1:4081/admin/', '              \r\n            ', 1, 'Gympl Kerio'),
(17, 2, 'https://10.0.111.10:9443/login?url=/manage', '              \r\n            ', 1, 'Gympl AP Management'),
(18, 3, 'https://10.0.2.1:4081/admin/', 'wan: 193.86.148.189<br>\r\nlan: 10.0.2.1<br>\r\nmask: 255.255.255.255<br>\r\ndns: 193.85.1.100;193.85.2.100;8.8.8.8;8.8.4.4\r\n           \r\n            ', 1, 'Group Kerio'),
(19, 4, 'http://www.pocitacezababku.cz/clanky/Kontaktni-informace.html', 'repasovane pocitace, nekdy se vyplati koupit ne uplne nova, moznost zaruky, os pro skola, oem office            \r\n            ', 1, 'Dodavatel Pocitace za babku'),
(20, 5, 'www.pachner.cz ', 'ms office, ms windows pro skoly<br>\r\n<p>\r\n<br>Zuzana Kadlčková\r\n<br>PACHNER, vzdělávací software, s.r.o.\r\n<br>Tikovská 2684/33, 193 00 Praha 9\r\n<br>tel: 233 374 058, 233 378 801, 605 408 789\r\n<br>e-mail: obchod@pachner.cz\r\n<br>web: www.pachner.cz\r\n<br>IČ: 27223809, DIČ: CZ27223809 \r\n</p>', 1, 'Dodavatel PACHNER, vzdělávací software, s.r.o. '),
(21, 6, 'https://login.live.com/login.srf?wa=wsignin1.0&rpsnv=12&ct=1427461280&rver=6.6.6575.0&wp=MBI_SSL_SA&', '              \r\n            ', 1, 'MS Volume Licencing');

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

DROP TABLE IF EXISTS `persons`;
CREATE TABLE IF NOT EXISTS `persons` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `osobni_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `pobocka` int(3) NOT NULL,
  `full_name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `login` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `osobni_cislo` (`osobni_cislo`,`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=200 ;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`dbid`, `id`, `osobni_cislo`, `pobocka`, `full_name`, `login`, `aktivni`) VALUES
(198, 0, '0', 0, 'Lukáš Doubrav', 'lukas.doubrav', 1),
(199, 1, '0', 0, 'Galušková Martina', 'martina.galuskova', 1);

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

DROP TABLE IF EXISTS `printers`;
CREATE TABLE IF NOT EXISTS `printers` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `seriove_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `evidencni_cislo` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `model` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `datum_porizeni` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  `ip` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `_mac` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`dbid`,`id`),
  UNIQUE KEY `seriove_cislo` (`seriove_cislo`,`evidencni_cislo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=184 ;

--
-- Dumping data for table `printers`
--

INSERT INTO `printers` (`dbid`, `id`, `seriove_cislo`, `evidencni_cislo`, `model`, `datum_porizeni`, `aktivni`, `ip`, `_mac`) VALUES
(168, 0, '0', '0', 'hp clj', '0', 1, '0.0.0.0', '000000000000'),
(169, 1, 'GD000015', 'gd000015', 'hp_clj_cm1415fnw', '0', 1, '10.0.111.4', 'b4b52ff236f3'),
(170, 2, 'GD000182', 'gd000182', 'hp_clj_cm1312nfi', '0', 1, '10.0.111.3', '78e7d1aa3794'),
(171, 3, 'GD000262', 'GD000262', 'canon_iR3320a', '0', 1, '10.0.111.81', '000000000000'),
(172, 4, 'GD000721', 'GD000721', 'hp_lj_1606', '0', 1, '0.0.0.0', '000000000000'),
(173, 5, 'tisk05', 'tisk05', 'hp_lj_p1005', '0', 1, '0.0.0.0', '000000000000'),
(174, 6, '10.0.111.2', '10.0.111.2', 'xerox_work_centre_6015', '0', 1, '10.0.0.2', '000000000000'),
(175, 7, 'GD000272', 'GD000272', 'hp_lj_1022n', '0', 1, '0.0.0.0', '000000000000'),
(176, 8, 'tiskarna3525', 'tiskarna3525', 'hp_dj_ink_3525', '0', 1, '0.0.0.0', '000000000000'),
(177, 9, 'GD000274', 'GD000274', 'samsung_scx-4299', '0', 1, '0.0.0.0', '000000000000'),
(178, 10, 'tisk-sekr', 'tisk-sekr', 'hp_clj_400	', '0', 1, '10.0.111.5', '24be05eef35b'),
(179, 11, 'c0 f8 da 33 53 ', 'c0 f8 da 33 53 ', 'hp_clj_cm1415fnw', '0', 1, '10.0.111.6', 'c0f8da335351'),
(181, 12, 'sborovna2np', 'sborovna2np', 'canon_iR3035i', '0', 1, '10.0.111.2', '000000000000'),
(182, 13, 'ZS000294', 'zs000294', 'hp lj pro mfp m521dw', '0', 1, '10.0.2.35', '000000000000'),
(183, 14, 'ZS001229', 'ZS001229', 'hp clj pro mfp m 475dw', '0', 1, '10.0.1.32', '000000000000');

-- --------------------------------------------------------

--
-- Table structure for table `printer_uses`
--

DROP TABLE IF EXISTS `printer_uses`;
CREATE TABLE IF NOT EXISTS `printer_uses` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `printer_id` int(5) NOT NULL,
  `person_id` int(5) NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=301 ;

--
-- Dumping data for table `printer_uses`
--

INSERT INTO `printer_uses` (`dbid`, `id`, `printer_id`, `person_id`, `poznamka`) VALUES
(300, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

DROP TABLE IF EXISTS `requirements`;
CREATE TABLE IF NOT EXISTS `requirements` (
  `dbid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(5) NOT NULL,
  `obj_id` int(5) NOT NULL,
  `obj_folder` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci NOT NULL,
  `aktivni` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dbid`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`dbid`, `id`, `obj_id`, `obj_folder`, `poznamka`, `aktivni`) VALUES
(21, 0, 0, '_persons', 'Pozadavek na novy neco', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
