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
  `plane` int(100) DEFAULT NULL,

  `variation` int(3) NOT NULL,

  `FrontMass` int(3) DEFAULT NULL,

  `Rear0Mass` int(3) DEFAULT NULL,
  `Rear1Mass` int(3) DEFAULT NULL,

  `Luggage0Mass` int(3) DEFAULT NULL,
  `Luggage1Mass` int(3) DEFAULT NULL,
  `Luggage2Mass` int(3) DEFAULT NULL,
  `Luggage3Mass` int(3) DEFAULT NULL,

  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
