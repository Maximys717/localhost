-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 02 2014 г., 22:59
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `bd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `author` varchar(15) NOT NULL,
  `date` text NOT NULL,
  `date2` datetime NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `author`, `date`, `date2`, `text`) VALUES
(10, 'max123', '20:50:30 31/05/2014 ', '2014-05-31 20:50:30', 'privet!'),
(11, 'maxwell123', '09:58:53 01/06/2014 ', '2014-06-01 09:58:53', 'privet! maxwell123'),
(12, 'ksyuhik', '09:59:19 01/06/2014 ', '2014-06-01 09:59:19', 'privet,ksyuhik'),
(13, 'ksyuhik', '10:50:49 01/06/2014 ', '2014-06-01 10:50:49', 'ololo'),
(14, 'maxwell123', '10:51:29 01/06/2014 ', '2014-06-01 10:51:29', 'privet reb9ta'),
(19, 'ksyuhik', '15:20:19 01/06/2014 ', '2014-06-01 15:20:19', 'привет'),
(20, 'ksyuhik', '15:22:23 01/06/2014 ', '2014-06-01 15:22:23', 'privet123');

-- --------------------------------------------------------

--
-- Структура таблицы `readers`
--

CREATE TABLE IF NOT EXISTS `readers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(11) NOT NULL,
  `the_followed_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `readers`
--

INSERT INTO `readers` (`id`, `subscriber_id`, `the_followed_id`, `number`) VALUES
(11, 18, 19, 1),
(16, 19, 18, 1),
(17, 20, 18, 1),
(19, 20, 19, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(15) NOT NULL,
  `password` varchar(25) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `avatar`) VALUES
(18, 'max123', '54321', 'avatars/1401558660.jpg'),
(19, 'ksyuhik', '54321', 'avatars/1401558753.jpg'),
(20, 'maxwell123', '54321', 'avatars/net-avatara.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
