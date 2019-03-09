PRAGMA synchronous = OFF;
PRAGMA journal_mode = MEMORY;
BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `auth` (
  `token` text NOT NULL
,  `id` text NOT NULL
,  `expirationdate` integer NOT NULL
,  `ip` text NOT NULL
);
CREATE TABLE IF NOT EXISTS `events` (
  `idEvent` varchar(6) NOT NULL
,  `adminpass` text NOT NULL
,  `title` text NOT NULL
,  `author` text NOT NULL
,  `date` integer NOT NULL
,  `place` text NOT NULL
,  `description` text NOT NULL
,  `expirationdate` integer NOT NULL
,  PRIMARY KEY (`idEvent`)
);
CREATE TABLE IF NOT EXISTS `polls` (
  `idPoll` varchar(6) NOT NULL
,  `idEvent` varchar(6) DEFAULT NULL
,  `adminpass` text NOT NULL
,  `expirationdate` integer NOT NULL
,  `title` text NOT NULL
,  PRIMARY KEY (`idPoll`)
);
CREATE TABLE IF NOT EXISTS `choices` (
  `idChoice` integer NOT NULL PRIMARY KEY AUTOINCREMENT
,  `idPoll` varchar(6) NOT NULL
,  `choice` text NOT NULL
,  FOREIGN KEY (idPoll) REFERENCES polls(idPoll)
);
CREATE TABLE IF NOT EXISTS `comments` (
  `idComment` integer NOT NULL PRIMARY KEY AUTOINCREMENT
,  `idEvent` varchar(6) NOT NULL
,  `author` text NOT NULL
,  `date` integer NOT NULL
,  `content` text NOT NULL
,  FOREIGN KEY (idEvent) REFERENCES events(idEvent)
);
CREATE TABLE IF NOT EXISTS `guests` (
  `idGuest` integer NOT NULL PRIMARY KEY AUTOINCREMENT
,  `idEvent` varchar(6) NOT NULL
,  `name` text NOT NULL
,  FOREIGN KEY (idEvent) REFERENCES events(idEvent)
);
CREATE TABLE IF NOT EXISTS `results` (
  `choice` text NOT NULL
,  `idPoll` varchar(6) NOT NULL
,  `name` char(20) NOT NULL
,  PRIMARY KEY (`idPoll`,`name`)
,  FOREIGN KEY (idPoll) REFERENCES polls(idPoll)
);
END TRANSACTION;
