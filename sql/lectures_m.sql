-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `lectures_m`
--

CREATE TABLE `lectures_m` (
  `id` int(100) NOT NULL,
  `lecture` varchar(100) NOT NULL,
  `students` text NOT NULL,
  `howmanydates` tinyint(4) NOT NULL DEFAULT 1,
  `date1` date NOT NULL,
  `date2` date DEFAULT NULL,
  `date3` date DEFAULT NULL,
  `date4` date DEFAULT NULL,
  `date5` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
