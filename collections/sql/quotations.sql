-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `quote` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `authorlast` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorfirst` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `place` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `fav` tinyint(1) NOT NULL DEFAULT 0,
  `amour` tinyint(1) NOT NULL DEFAULT 0,
  `argent` tinyint(1) NOT NULL DEFAULT 0,
  `cuisine` tinyint(1) NOT NULL DEFAULT 0,
  `environnement` tinyint(1) NOT NULL DEFAULT 0,
  `EPFL` tinyint(1) NOT NULL DEFAULT 0,
  `humour` tinyint(1) NOT NULL DEFAULT 0,
  `informatique` tinyint(1) NOT NULL DEFAULT 0,
  `litterature` tinyint(1) NOT NULL DEFAULT 0,
  `medecine` tinyint(1) NOT NULL DEFAULT 0,
  `militaire` tinyint(1) NOT NULL DEFAULT 0,
  `musique` tinyint(1) NOT NULL DEFAULT 0,
  `philosophie` tinyint(1) NOT NULL DEFAULT 0,
  `politique` tinyint(1) NOT NULL DEFAULT 0,
  `religions` tinyint(1) NOT NULL DEFAULT 0,
  `sciences` tinyint(1) NOT NULL DEFAULT 0,
  `sexe` tinyint(1) NOT NULL DEFAULT 0,
  `sports` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
