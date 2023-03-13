-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `missings`
--

CREATE TABLE `missings` (
  `id` int(100) NOT NULL,
  `borrower` int(100) NOT NULL,
  `dbtable` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dbid` int(100) NOT NULL,
  `when` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
