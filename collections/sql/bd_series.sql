-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `bd_series`
--

CREATE TABLE `bd_series` (
  `id` int(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `editor` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` set('BD','manga','comics','misc') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'BD',
  `Nalbums` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ALTER TABLE `bd_series` RENAME COLUMN `thumb` TO `editor`;
