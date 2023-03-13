-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `books`
--

CREATE TABLE `books` (
  `id` int(100) NOT NULL,
  `isbn` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `author` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `serie` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` int(100) DEFAULT NULL,
  `publisher` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `language` enum('fr','en','it','de','??') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` enum('novel','doc') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'novel',
  `summary` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrowed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
