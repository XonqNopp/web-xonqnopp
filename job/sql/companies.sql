-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/

--
-- Tabellenstruktur für Tabelle `companies`
--

CREATE TABLE `companies` (
  `id` int(100) NOT NULL,
  `name` text NOT NULL,
  `location` text DEFAULT NULL,
  `car_time` int(100) DEFAULT NULL,
  `train_time` int(100) DEFAULT NULL,
  `fields` text DEFAULT NULL,
  `physicist` text DEFAULT NULL,
  `contact` text DEFAULT NULL,
  `HRname` text DEFAULT NULL,
  `people` int(100) DEFAULT NULL,
  `peopleCH` int(100) DEFAULT NULL,
  `peopleRD` int(100) DEFAULT NULL,
  `competitors` text DEFAULT NULL,
  `website` text DEFAULT NULL,
  `ranking` tinyint(2) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
