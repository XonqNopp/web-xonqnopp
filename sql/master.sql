-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `master`
--

CREATE TABLE `master` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('T','P','E','C') NOT NULL,
  `day` enum('monday','tuesday','wednesday','thursday','friday') NOT NULL,
  `timestart` int(10) NOT NULL,
  `timestop` int(10) NOT NULL,
  `location` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
