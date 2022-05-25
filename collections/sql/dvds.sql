-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `dvds`
--

CREATE TABLE `dvds` (
  `id` int(100) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `director` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `actors` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `languages` set('fr','en','de','it','zz') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtitles` set('fr','en','de','it','zz') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `duration` int(4) DEFAULT NULL,
  `serie` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` int(4) DEFAULT NULL,
  `category` enum('movie','animation','tvserie','doc','humor','music','memory') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'movie',
  `summary` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `burnt` tinyint(1) NOT NULL DEFAULT 0,
  `format` enum('dvd','blu','avi') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'dvd',
  `borrowed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
