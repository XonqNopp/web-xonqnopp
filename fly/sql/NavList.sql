-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--

--
-- Tabellenstruktur für Tabelle `NavList`
--

CREATE TABLE `NavList` (
  `id` int(100) NOT NULL,
  `name` text NOT NULL,
  `MapUsed` varchar(10) DEFAULT NULL,
  `plane` int(100) DEFAULT NULL,
  `Power` int(2) DEFAULT NULL,
  `PowerManifold` float DEFAULT NULL,
  `PowerManifoldUnit` varchar(10) DEFAULT NULL,
  `PowerRPM` float DEFAULT NULL,
  `altitude` int(5) DEFAULT NULL,
  `variation` int(3) NOT NULL,
  `FrontMass` int(3) DEFAULT NULL,
  `RearMass` int(3) DEFAULT NULL,
  `LuggageMass` int(3) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
