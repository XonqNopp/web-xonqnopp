-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--

--
-- Tabellenstruktur für Tabelle `NavWaypoints`
--

CREATE TABLE `NavWaypoints` (
  `id` int(100) NOT NULL,
  `NavID` int(100) NOT NULL,
  `WPnum` int(100) NOT NULL,
  `waypoint` text NOT NULL,
  `TC` int(3) DEFAULT NULL,
  `distance` int(3) NOT NULL,
  `altitude` int(5) DEFAULT NULL,

  `windTC` int(3) DEFAULT NULL,
  `windSpeed` int(3) DEFAULT NULL,

  `notes` text DEFAULT NULL,

  `climbing` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE `NavWaypoints` DROP COLUMN `TCguess`;
