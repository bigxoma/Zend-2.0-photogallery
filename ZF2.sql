-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 30, 2013 at 10:34 AM
-- Server version: 5.1.40
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ZF2`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT 'Название альбома',
  `description` varchar(200) NOT NULL COMMENT 'Описание альбома',
  `preview` varchar(15) DEFAULT 'picture.jpg' COMMENT 'Превьюшка альбома',
  `author` varchar(50) NOT NULL COMMENT 'Автор',
  `mail` varchar(200) DEFAULT NULL COMMENT 'mail',
  `phone` varchar(30) DEFAULT NULL COMMENT 'Телефон',
  `created` datetime NOT NULL COMMENT 'Создан',
  `update` datetime DEFAULT NULL COMMENT 'Обновлен',
  `amount` int(10) NOT NULL DEFAULT '0' COMMENT 'Количество фотографий',
  `added` datetime DEFAULT NULL COMMENT 'время добавления последней фотографии ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `title`, `description`, `preview`, `author`, `mail`, `phone`, `created`, `update`, `amount`, `added`) VALUES
(9, 'Животные', 'Братья наши меньшие', '1375163102.jpg', 'Алексей', 'Gorillaz@inbox.ru', '+7 (910) 883-10-24', '2013-07-30 09:36:00', '2013-07-30 09:40:00', 6, '2013-07-30 09:45:00'),
(10, 'Города', 'Мировые мегаполисы', '1375163763.jpg', 'Виктория', 'vika@mail.ru', '+7 (888) 888-88-88', '2013-07-30 09:48:00', '2013-07-30 09:51:00', 6, '2013-07-30 09:56:00'),
(11, 'Космос', 'Безграничные просторы вселенной', '1375165808.jpg', 'Гречко Георгий', '', '', '2013-07-30 09:57:00', NULL, 7, '2013-07-30 10:30:00'),
(12, 'Природа', 'Красивый ландшафт', '1375164935.jpg', 'Вася', 'vasya@gmail.com', '', '2013-07-30 10:09:00', '2013-07-30 10:10:00', 6, '2013-07-30 10:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE IF NOT EXISTS `pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(50) NOT NULL COMMENT 'Заголовок фото',
  `album` int(11) DEFAULT NULL COMMENT 'Альбом фото',
  `address` varchar(200) DEFAULT NULL COMMENT 'Адрес фотосъемки',
  `src` varchar(15) DEFAULT NULL COMMENT 'Имя файла',
  `added` datetime DEFAULT NULL COMMENT 'Добавлено',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=149 ;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `title`, `album`, `address`, `src`, `added`) VALUES
(120, 'Птичка', 9, 'Дятловы горы', '1375162857.jpg', '2013-07-30 09:40:00'),
(121, 'Бабочка', 9, 'Ботанический сад в Дубёнках', '1375162894.jpg', '2013-07-30 09:41:00'),
(122, 'Дельфины', 9, 'Черное море', '1375163040.jpg', '2013-07-30 09:44:00'),
(127, 'Майями Бич', 10, 'Соединённые Штаты Америки', '1375163561.jpg', '2013-07-30 09:52:00'),
(130, 'Венеция', 10, 'Италия', '1375163703.jpg', '2013-07-30 09:55:00'),
(129, 'Париж', 10, 'Франция', '1375163644.jpg', '2013-07-30 09:54:00'),
(128, 'Москва', 10, 'Матушка Россия', '1375163584.jpg', '2013-07-30 09:53:00'),
(131, 'Храм Василия Блаженного', 10, 'Москва, Красная площадь', '1375163763.jpg', '2013-07-30 09:56:00'),
(126, 'Гонк-Конг', 10, 'Китайская Народная Республика', '1375163467.jpg', '2013-07-30 09:51:00'),
(125, 'Волк', 9, 'Северный Кавказ', '1375163102.jpg', '2013-07-30 09:45:00'),
(124, 'Панды', 9, 'Московский зоопарк', '1375163077.jpg', '2013-07-30 09:44:00'),
(123, 'Кролик', 9, 'Солнечная поляна', '1375163059.jpg', '2013-07-30 09:44:00'),
(132, 'Галактика', 11, 'Марс', '1375164059.jpg', '2013-07-30 10:01:00'),
(133, 'Земля', 11, 'Междунаро́дная космическая ста́нция', '1375164142.jpg', '2013-07-30 10:02:00'),
(134, 'Юпитер и Солнце', 11, 'Сатурн', '1375164264.jpg', '2013-07-30 10:04:00'),
(135, 'Вспышка', 11, 'Space', '1375164303.jpg', '2013-07-30 10:05:00'),
(136, 'Happy end', 11, 'Шаттл', '1375164356.jpg', '2013-07-30 10:05:00'),
(148, 'Астронавт', 11, 'МКС', '1375165808.jpg', '2013-07-30 10:30:00'),
(138, 'Земной шарик', 11, 'Луна', '1375164426.jpg', '2013-07-30 10:07:00'),
(141, 'Осень', 12, 'Болдино', '1375164706.jpg', '2013-07-30 10:11:00'),
(142, 'Озеро', 12, 'Германия', '1375164738.jpg', '2013-07-30 10:12:00'),
(143, 'Сарезское озеро', 12, 'Памире, Таджикистан', '1375164796.jpg', '2013-07-30 10:13:00'),
(144, 'Ландшафт', 12, 'Германия', '1375164842.jpg', '2013-07-30 10:14:00'),
(145, 'Горы', 12, 'Швейцария', '1375164866.jpg', '2013-07-30 10:14:00'),
(146, 'Летнее время', 12, 'Сахалин', '1375164935.jpg', '2013-07-30 10:15:00');
