-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 04, 2013 at 06:59 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `apprentidate`
--

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE IF NOT EXISTS `choices` (
  `idChoice` int(11) NOT NULL AUTO_INCREMENT,
  `idPoll` int(11) NOT NULL,
  `choice` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idChoice`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `idComment` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `author` text COLLATE utf8_bin NOT NULL,
  `date` int(11) NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idComment`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `idEvent` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_bin NOT NULL,
  `author` text COLLATE utf8_bin NOT NULL,
  `date` int(11) NOT NULL,
  `place` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `expirationdate` int(11) NOT NULL,
  PRIMARY KEY (`idEvent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE IF NOT EXISTS `guests` (
  `idGuest` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idGuest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `idPoll` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `expirationdate` int(11) NOT NULL,
  `title` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idPoll`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `choice` text COLLATE utf8_bin NOT NULL,
  `idPoll` int(11) NOT NULL,
  `name` char(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idPoll`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `email` text COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
