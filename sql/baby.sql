-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `baby`
--

CREATE TABLE `baby` (
  `id` int(10) NOT NULL,
  `what` text NOT NULL,
  `howmuch` int(10) NOT NULL,
  `who` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
