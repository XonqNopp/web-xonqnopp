-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `mariage`
--

CREATE TABLE `mariage` (
  `id` int(16) NOT NULL,
  `what` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `howmuch` int(16) NOT NULL,
  `who` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
